<?php
/*
  Created on Sep 3, 2016
  Plugin Name: Email Media Import
  Description: Plugin for importing media via email
  Version: 0.6
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

function emailMediaImportShortCode($attrs) {
  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  	echo "Invalid request method " . $_SERVER['REQUEST_METHOD'];
  	http_response_code(400);
  	die;  	
  } else {
  	$fooGalleryId = $attrs['foo-gallery-id'];
  	
    $options = get_option(EMAIL_MEDIA_IMPORT_SETTINGS);
    
    wp_set_current_user($options && $options['importUser'] ? $options['importUser'] : 0);
    $maxWidth = $options && $options['maxWidth'] ? $options['maxWidth'] : 1280;
    $maxHeight = $options && $options['maxHeight'] ? $options['maxHeight'] : 1280;
    
    // Validate that timestamp, token and signature are present and in correct format
    
    $timestamp = $_POST['timestamp'];
    $token = $_POST['token'];
    $signature = $_POST['signature'];
    
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
    
    // Check that attachments is an non zero-length array

    $attachments = $_POST['attachments'];

    if (!isset($attachments) || !is_array($attachments)) {
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
    
    // Subject and body may be empty, so no validation is needed
    
    $subject = $_POST['subject'];
    $bodyPlain = $_POST['body-plain'];
    
    // Import media into media library

    $mediaImportter = new Metatavu\EmailMediaImport\MediaImportter();
    $importtedImageId = $mediaImportter->createImage($saved, $subject, $bodyPlain);
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