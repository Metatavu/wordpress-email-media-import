<?php
  namespace Metatavu\EmailMediaImport;

  if (!defined('ABSPATH')) {
  	exit;
  }
  
  require_once("constants.php");
  
  class MailgunDownloader {
  	
  	private $mailgunKey;
  	
  	public function __construct() {
  	  $options = get_option(EMAIL_MEDIA_IMPORT_SETTINGS);
      $this->mailgunKey = $options['mailgunKey'];
  	}
  	
  	public function checkSignature($timestamp, $signature, $token) {
  	  return hash_hmac('sha256', $timestamp . $token, $this->mailgunKey) === $signature;
  	}
  	
  	public function downloadFirstAttachment($attachments) {
  	  $attachmentsDecoded = (Array) json_decode(stripcslashes($attachments));
  	  if (count($attachmentsDecoded) > 0) {
  	    $attachmentDecoded = (Array) $attachmentsDecoded[0];
  	    return array(
          'name' => $attachmentDecoded['name'],
  	      'contentType' => $attachmentDecoded['content-type'],
  	      'data' => $this->getFileData($attachmentDecoded['url'], $attachmentDecoded['size'])
  	    );
  	  }
  	  
  	  return null;
  	}
  	
  	private function getFileData($attachmentUrl, $attachmentSize) {
  	  $auth = base64_encode("api:$this->mailgunKey");
  	  $context = stream_context_create(array(
  	    'http' => array (
  		  'method' => "GET",
  		  'header' => "Authorization: Basic $auth"
  		)
  	  ));
  		
  	  $file = fopen($attachmentUrl, 'r', false, $context);
  	  try {
  	  	$data = '';
  	  	while (!feof($file)) {
  	      $data .= fread($file, 4096);
  	  	}
  	  	return $data;
  	  } finally {
  	    fclose($file);
  	  }
  	}
  	
  }

?>