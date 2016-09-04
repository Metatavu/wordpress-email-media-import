<?php

require_once("../../../wp-config.php");
require_once("constants.php");
require_once(ABSPATH . 'wp-admin/includes/admin.php');

$options = get_option(MAILGUN_MEDIA_IMPORT_SETTINGS);
$fooGalleryEnabled = $options['foogalleryEnabled'] == 'true';
$fooGalleryId = $options['foogalleryGalleryId'];
$mailgunKey = $options['mailgunKey'];

if ($fooGalleryEnabled) {
  require_once(ABSPATH . 'wp-content/plugins/foogallery/includes/constants.php');
}

function downloadAsAttachment($key, $subject, $bodyPlain, $attachmentUrl, $attachmentName, $attachmentSize, $attachmentType) {
  $auth = base64_encode("api:$key");
  $context = stream_context_create(array(
	'http' => array (
	  'method' => "GET",
	  'header' => "Authorization: Basic $auth"
	)
  ));

  $file = fopen($attachmentUrl, 'r', false, $context);
  try {
	$data = fread($file, $attachmentSize);
	$uploadDir = wp_upload_dir();
	$imagePath = urldecode($uploadDir['path'] . '/' . $attachmentName);
	$imageUrl = urldecode($uploadDir['url'] . '/' . $attachmentName);
	file_put_contents($imagePath, $data);
	$attachment = array(
	  'guid' => $imageUrl,
	  'post_mime_type' => $attachmentType,
	  'post_title' => $subject,
	  'post_content' => $bodyPlain,
      'post_status' => 'inherit'
	);

	$attachmentId = wp_insert_attachment($attachment, $imagePath);
	$attachmentMeta = wp_generate_attachment_metadata($attachmentId, $imagePath);
	wp_update_attachment_metadata($attachmentId, $attachmentMeta);

	return $attachmentId;
  } finally {
	fclose($file);
  }
}

function attachToFooGallery($galleryId, $attachmentId) {
  $attachmentIds = get_post_meta($galleryId, FOOGALLERY_META_ATTACHMENTS, true );
  if (empty($attachmentIds)) {
	$attachmentIds = array();
  }
  
  $attachmentIds[] = $attachmentId;
  
  update_post_meta($galleryId, FOOGALLERY_META_ATTACHMENTS, $attachmentIds);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $timestamp = $_POST['timestamp'];
  $token = $_POST['token'];
  $signature = $_POST['signature'];
  
  if (!isset($timestamp) || !isset($token) || !isset($signature)) {
  	error_log("Missing $timestamp, $token or $signature");
  	echo "Bad Request";
  	http_response_code(400);
  	die;
  }
  
  if (hash_hmac('sha256', $signature . $token, $key) === $signature) {
  	error_log("$signature does not match");
  	echo "Forbidden";
  	http_response_code(403);
  	die;
  }
  
  $subject = $_POST['subject'];
  $bodyPlain = $_POST['body-plain'];
  $attachments = $_POST['attachments'];
  
  if (!isset($attachments)) {
  	error_log("Attachments could not be found from the request body");
  	echo "Attachments could not be found from the request body";
  	http_response_code(400);
  	die;
  }
  
  $attachmentsDecoded = (Array) json_decode(stripcslashes($attachments));

  for ($idx = 0; $idx < count($attachmentsDecoded); $idx++){
  	$attachmentDecoded = (Array) $attachmentsDecoded[$idx];
    $attachmentUrl = $attachmentDecoded['url'];
    if (empty($attachmentUrl)) {
	  error_log("Attachment url is missing from " . print_r($attachmentDecoded, true));
      echo "Attachment url is missing";
	  http_response_code(400);
      die;
    }
    
    $attachmentName = $attachmentDecoded['name'];
	$attachmentSize = $attachmentDecoded['size'];
	$attachmentType = $attachmentDecoded['content-type'];
	$attachmentId = downloadAsAttachment($mailgunKey, $subject, $bodyPlain, $attachmentUrl, $attachmentName, $attachmentSize, $attachmentType);
	
	if ($fooGalleryEnabled) {
	attachToFooGallery($fooGalleryId, $attachmentId);
	}
  }
  
}

?>