<?php
  namespace Metatavu\EmailMediaImport;
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  require_once("constants.php");
  
  class FooGalleryImporter {
  	
  	private $enabled;
  	
  	public function __construct() {
  	}
  	
  	public function isEnabled() {
      if (is_plugin_active('foogallery/foogallery.php')) {
  	    return $this->enabled;
      }
      
      return false;
  	}
  	
  	public function importByTitles($galleryTitles, $imageId) {
  	  foreach ($galleryTitles as $galleryTitle) {
  	    $fooGalleryId = $this->findGalleryIdByTitle($galleryTitle);
        if ($fooGalleryId) {
  		    $this->importImage($fooGalleryId, $imageId);
  		  } else {
  		    error_log("Could not find foogallery with title: '$galleryTitle'");
  		  }
  	  }
  	}
  	
  	private function findGalleryIdByTitle($title) {
  	  $result = get_page_by_title($title, "OBjECT", "foogallery");
  	  if (!empty($result)) {
  	  	return $result->ID;
  	  }
  	  
  	  return null;
  	}
  	 
  	public function importImage($galleryId, $attachmentId) {
  	  $attachmentIds = get_post_meta($galleryId, 'foogallery_attachments', true );
  	  if (empty($attachmentIds)) {
  	    $attachmentIds = array();
  	  }	
  	  
  	  $attachmentIds[] = $attachmentId;
  	  
  	  update_post_meta($galleryId, 'foogallery_attachments', $attachmentIds);
  	}
  	
  }

?>