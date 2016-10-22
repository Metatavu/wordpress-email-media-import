<?php
/*
  Created on Sep 3, 2016
  Plugin Name: Email Media Import
  Description: Plugin for importing media via email
  Version: 0.7
  Author: Antti Leppä / Metatavu Oy
*/

if (!defined('ABSPATH')) { 
  exit;
}

require_once(ABSPATH . 'wp-admin/includes/admin.php');
require_once(ABSPATH . 'wp-includes/user.php');
require_once(ABSPATH . 'wp-includes/pluggable.php');
require_once(ABSPATH . 'wp-includes/media.php');
require_once(ABSPATH . 'wp-includes/functions.php');

require_once("settings.php");
require_once("mailgun-downloader.php");
require_once("foogallery-importter.php");
require_once("media-importter.php");
require_once("image-editor.php");
require_once("text-processor.php");
  
function emailMediaImportShortCode($attrs) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  	$fooGalleryId = null;
  	if ($attrs) {
  	  $fooGalleryId = array_key_exists("foo-gallery-id", $attrs) ? $attrs['foo-gallery-id'] : null;
  	}
  	
    $options = get_option(EMAIL_MEDIA_IMPORT_SETTINGS);
    
    wp_set_current_user($options && $options['importUser'] ? $options['importUser'] : 0);
    $maxWidth = $options && $options['maxWidth'] ? $options['maxWidth'] : 1280;
    $maxHeight = $options && $options['maxHeight'] ? $options['maxHeight'] : 1280;
    $titleTag = $options && $options['titleTag'] ? $options['titleTag'] : 'title';
    $descriptionTag = $options && $options['descriptionTag'] ? $options['descriptionTag'] : 'description';
    
    // Validate that timestamp, token and signature are present and in correct format
    
    $timestamp = sanitize_text_field($_POST['timestamp']);
    $token = sanitize_text_field($_POST['token']);
    $signature = sanitize_text_field($_POST['signature']);
    
    if (empty($timestamp) || empty($token) || empty($signature) || !is_numeric($timestamp)) {
      error_log("Timestamp, token or signature invalid or missing");
      echo "Bad Request";
      http_response_code(400);
      die;
    }
    
    // Ensure authenticity of the requestn 
    
    $mailgunDownlader = new Metatavu\EmailMediaImport\MailgunDownloader();
    if (!$mailgunDownlader->checkSignature($timestamp, $signature, $token)) {
      error_log("$signature does not match");
      echo "Forbidden";
      http_response_code(403);
      die;
    }
    
    // Check that attachments are present

    $attachments = sanitize_text_field($_POST['attachments']);
    if (!isset($attachments)) {
      error_log("Attachments could not be found from the request body");
      echo "Attachments could not be found from the request body";
      http_response_code(400);
      die;
    }
    
    // Download the file

    $downloaded = $mailgunDownlader->downloadFirstAttachment($attachments);
    if (!isset($downloaded)) {
      error_log("Could not download file");
      echo "Could not download file";
      http_response_code(500);
      die;
    }
    
    // Fix image orientation and scale it down if necessary 

    $imageEditor = new Metatavu\EmailMediaImport\ImageEditor($downloaded);
    $imageEditor->fixOrientation();
    $imageEditor->scaleImage($maxWidth, $maxHeight);
    $saved = $imageEditor->save();
    
    // Subject and body may be empty but they should not contain any html
    
    $bodyPlain = sanitize_text_field($_POST['body-plain']);
    $textProcessor = new Metatavu\EmailMediaImport\TextProcessor($bodyPlain, $titleTag, $descriptionTag);
    
    // Import media into media library

    $mediaImportter = new Metatavu\EmailMediaImport\MediaImportter();
    $importtedImageId = $mediaImportter->createImage($saved, $textProcessor->getTitle(), $textProcessor->getDescription());
    if (!isset($importtedImageId)) {
      error_log("Could not import image");
      echo "Could not import image";
      http_response_code(500);
      die;
    }
    
    // Attach to gallery if id is specified

    if ($fooGalleryId) {
      $fooGalleryImporter = new Metatavu\EmailMediaImport\FooGalleryImporter();
      $fooGalleryImporter->importImage($fooGalleryId, $importtedImageId);
    }
  }
}

add_shortcode('email_media_import', 'emailMediaImportShortCode');

?>