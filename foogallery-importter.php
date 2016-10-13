<?php
  namespace Metatavu\EmailMediaImport;
  
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

  require_once("constants.php");
  
  if (is_plugin_active('foogallery/foogallery.php')) {
    require_once(ABSPATH . 'wp-content/plugins/foogallery/includes/constants.php');
  }
  
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
  	  $attachmentIds = get_post_meta($galleryId, FOOGALLERY_META_ATTACHMENTS, true );
  	  if (empty($attachmentIds)) {
  	    $attachmentIds = array();
  	  }	
  	  
  	  $attachmentIds[] = $attachmentId;
  	  
  	  update_post_meta($galleryId, FOOGALLERY_META_ATTACHMENTS, $attachmentIds);
  	}
  	
  }

?>