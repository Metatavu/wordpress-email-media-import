<?php
/*
  Created on Sep 3, 2016
  Plugin Name: Mailgun Media Import
  Description: Plugin for importing attachments into media library
  Version: 0.1
  Author: Antti Leppä / Metatavu Oy
*/

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require_once("settings.php");
require_once(ABSPATH . 'wp-includes/pluggable.php');

$path = $_SERVER['REQUEST_URI'];

if ($path == "/mailgun-media-import") {
  wp_redirect(plugins_url('import.php', __FILE__));
  exit;
}

?>