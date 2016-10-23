<?php
/*
  Created on Oct 23, 2016
  Plugin Name: FooGallery REST plugin
  Description: Adds REST API support into FooGallery (used in automatic tests)
  Version: 0.1
  Author: Antti Leppä / Metatavu Oy
*/

if (!defined('ABSPATH')) { 
  exit;
}

add_action('init', 'fooGalleryRestSupport', 25);

function fooGalleryRestSupport() {
  error_log("fooGalleryRestSupport:1");
	
  global $wp_post_types;
  $postType = 'foogallery';

  error_log("fooGalleryRestSupport:2");
  
  if (isset( $wp_post_types[ $postType ] ) ) {
    error_log("fooGalleryRestSupport:3");
    $wp_post_types[$postType]->show_in_rest = true;
	$wp_post_types[$postType]->rest_base = $postType;
	$wp_post_types[$postType]->rest_controller_class = 'WP_REST_Posts_Controller';
    error_log("fooGalleryRestSupport:4");
  }

  error_log("fooGalleryRestSupport:5");
}

function fooGalleryRestImages($object) { 
  error_log("fooGalleryRestImages:1");
  if ($object) {
    error_log("fooGalleryRestImages:2, " . print_r($object));
  	$id = $object['id']; 
    if ($id) {
      $meta = get_post_meta($id, 'foogallery_attachments', true);
  	  if (!empty($meta)) {
        return array_filter($meta);
  	  }
    }
    
    error_log("fooGalleryRestImages: 3");
  }

  error_log("fooGalleryRestImages: 4");
  
  return [];
}

add_action('rest_api_init', function () {
  error_log("rest_api_init: 1");
  
  $postType = 'foogallery';
  register_rest_field($postType, "images", array(
    'get_callback' => 'fooGalleryRestImages'
  ));
  
  error_log("rest_api_init: 2");
});

?>