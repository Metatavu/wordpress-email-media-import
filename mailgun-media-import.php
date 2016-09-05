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

add_action( 'init', 'mailgunMediaImportRegisterRewrite' );

function mailgunMediaImportRegisterRewrite() {
  global $wp_rewrite;
  $plugin_url = plugins_url('import.php', __FILE__);
  $wp_rewrite->add_external_rule('mailgun-media-import.php$', $plugin_url);
  $wp_rewrite->flush_rules();
}

?>