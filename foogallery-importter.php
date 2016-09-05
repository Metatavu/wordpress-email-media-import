<?php
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

  require_once("constants.php");
  
  if (is_plugin_active('foogallery/foogallery.php')) {
    require_once(ABSPATH . 'wp-content/plugins/foogallery/includes/constants.php');
  }
  
  class FooGalleryImporter {
  	
  	private $galleryId;
  	private $enabled;
  	
  	public function __construct() {
  	  $options = get_option(MAILGUN_MEDIA_IMPORT_SETTINGS);
      $this->galleryId = $options['foogalleryGalleryId'];
      $this->enabled = $this->galleryId && $this->galleryId != 'none';
      
      if ($this->enabled && $this->galleryId == "default") {
      	$galleries = get_posts(array(
      	  'post_type' => 'foogallery',
      	  'post_status' => 'publish',
      	  'suppress_filters' => true
      	));
      	
      	$this->galleryId = $galleries[0]->ID;
      }
  	}
  	
  	function isEnabled() {
      if (is_plugin_active('foogallery/foogallery.php')) {
  	    return $this->enabled;
      }
      
      return false;
  	}
  	
  	function importImage($attachmentId) {
  	  $attachmentIds = get_post_meta($this->galleryId, FOOGALLERY_META_ATTACHMENTS, true );
  	  if (empty($attachmentIds)) {
  	    $attachmentIds = array();
  	  }	
  	  
  	  $attachmentIds[] = $attachmentId;
  	  
  	  update_post_meta($this->galleryId, FOOGALLERY_META_ATTACHMENTS, $attachmentIds);
  	}
  	
  }

?>