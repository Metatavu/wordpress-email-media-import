<?php
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

  require_once("constants.php");
  
  class MediaImportter {
  	
  	private $uploadDir;
  	
  	public function __construct() {
  	  $this->uploadDir = wp_upload_dir();
  	}
  	
  	public function createImage($name, $data, $type, $title, $description) {
      $imagePath = urldecode($this->uploadDir['path'] . '/' . $name);
  	  $imageUrl = urldecode($this->uploadDir['url'] . '/' . $name);
  	  file_put_contents($imagePath, $data);
  	  $attachment = array(
  	    'guid' => $imageUrl,
  	    'post_mime_type' => $type,
  	    'post_title' => $title,
  	    'post_content' => $description,
     	'post_status' => 'inherit'
  	  );
  	  
  	  $attachmentId = wp_insert_attachment($attachment, $imagePath);
  	  $attachmentMeta = wp_generate_attachment_metadata($attachmentId, $imagePath);
  	  wp_update_attachment_metadata($attachmentId, $attachmentMeta);
  	
  	  return $attachmentId;
  	}
  	
  }

?>