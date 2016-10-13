<?php
  namespace Metatavu\EmailMediaImport;

  if (!defined('ABSPATH')) {
  	exit;
  }
  
  require_once("constants.php");
  
  class MediaImportter {
  	
  	private $uploadDir;
  	
  	public function __construct() {
  	  $this->uploadDir = wp_upload_dir();
  	}
  	
  	public function createImage($image, $title, $description) {
  	  $path = $image['path'];
  	  $type = $image['mime-type'];
  	  $imageUrl = urldecode(untrailingslashit($this->uploadDir['url']) . '/' . substr($path, strlen($this->uploadDir['path']) + 1));
  	  
  	  $attachment = array(
  	    'guid' => $imageUrl,
  	    'post_mime_type' => $type,
  	    'post_title' => $title,
  	    'post_content' => $description,
     	'post_status' => 'inherit',
  	  	'post_excerpt' => $title
  	  );
  	  
  	  $attachmentId = wp_insert_attachment($attachment, $path);
  	  $attachmentMeta = wp_generate_attachment_metadata($attachmentId, $path);
  	  wp_update_attachment_metadata($attachmentId, $attachmentMeta);
  	
  	  return $attachmentId;
  	}
  	
  }

?>