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
  	
  	function isEnabled() {
      if (is_plugin_active('foogallery/foogallery.php')) {
  	    return $this->enabled;
      }
      
      return false;
  	}
  	
  	function importImage($galleryId, $attachmentId) {
  	  $attachmentIds = get_post_meta($galleryId, 'foogallery_attachments', true );
  	  if (empty($attachmentIds)) {
  	    $attachmentIds = array();
  	  }	
  	  
  	  $attachmentIds[] = $attachmentId;
  	  
  	  update_post_meta($galleryId, 'foogallery_attachments', $attachmentIds);
  	}
  	
  }

?>