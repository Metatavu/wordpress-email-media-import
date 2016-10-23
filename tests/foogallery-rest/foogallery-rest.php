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
  global $wp_post_types;
  $postType = 'foogallery';
  
  if (isset( $wp_post_types[ $postType ] ) ) {
    $wp_post_types[$postType]->show_in_rest = true;
	$wp_post_types[$postType]->rest_base = $postType;
	$wp_post_types[$postType]->rest_controller_class = 'WP_REST_Posts_Controller';
  }
}

function fooGalleryRestImages($object) {
  $id = $object['id']; 
  if ($id) {
  	$meta = get_post_meta($id, 'foogallery_attachments', true);
  	if (!empty($meta)) {
      return array_filter($meta);
  	}
  }
  
  return [];
}

add_action('rest_api_init', function () {
  $postType = 'foogallery';
  register_rest_field($postType, "images", array(
    'get_callback' => 'fooGalleryRestImages'
  ));
});

?>