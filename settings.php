<?php
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once("constants.php");
  
  if (is_admin()) {
  	add_action('admin_menu', 'mailgunMediaImportSettingsMenu');
  	add_action('admin_init', 'mailgunMediaImportRegisterSettings');
  }
  
  /* Settings Page */
  
  function mailgunMediaImportSettingsMenu() {
  	add_options_page (__( "Mailgun Media Import", $MAILGUN_MEDIA_IMPORT_I18N_DOMAIN ), __( "Mailgun Media Import", $MAILGUN_MEDIA_IMPORT_I18N_DOMAIN ), 'manage_options', 'mailgun_media_import_settings', 'mailgunMediaImportSettingsPage' );
  }
  
  function mailgunMediaImportSettingsPage() {
  	if (!current_user_can('manage_options')) {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  	}
  	
  	echo '<div class="wrap">';
  	echo "<h2>" . __( "Mailgun Media Import Settings", MAILGUN_MEDIA_IMPORT_I18N_DOMAIN) . "</h2>";
  	echo '<form action="options.php" method="POST">';
  	
  	settings_fields(MAILGUN_MEDIA_IMPORT_SETTINGS_GROUP);
  	do_settings_sections(MAILGUN_MEDIA_IMPORT_SETTINGS_PAGE);
  	submit_button();
  	
  	echo "</form>";
  	echo "</div>";
  }
  
  /* Settings */
  
  function mailgunMediaImportRegisterSettings() {
  	register_setting(MAILGUN_MEDIA_IMPORT_SETTINGS_GROUP, MAILGUN_MEDIA_IMPORT_SETTINGS);
  	add_settings_section('core', __('Mailgun Media Import Settings', MAILGUN_MEDIA_IMPORT_I18N_DOMAIN), null, MAILGUN_MEDIA_IMPORT_SETTINGS_PAGE);
  	add_settings_field('mailgunKey', __('Mailgun API Key', MAILGUN_MEDIA_IMPORT_I18N_DOMAIN), 'mailgunMediaImportMailgunKey', MAILGUN_MEDIA_IMPORT_SETTINGS_PAGE, 'core');

  	add_settings_section('foogallery', __("Foo Gallery settings", MAILGUN_MEDIA_IMPORT_I18N_DOMAIN), null, MAILGUN_MEDIA_IMPORT_SETTINGS_PAGE);
  	add_settings_field('foogalleryEnabled', __('Add images to Foo Gallery', MAILGUN_MEDIA_IMPORT_I18N_DOMAIN), 'mailgunMediaImportFooGalleryEnabled', MAILGUN_MEDIA_IMPORT_SETTINGS_PAGE, 'foogallery');
  	add_settings_field('foogalleryGalleryId', __('Gallery Id', MAILGUN_MEDIA_IMPORT_I18N_DOMAIN), 'mailgunMediaImportFooGalleryGalleryId', MAILGUN_MEDIA_IMPORT_SETTINGS_PAGE, 'foogallery');
  }

  function mailgunMediaImportMailgunKey() {
  	$options = get_option(MAILGUN_MEDIA_IMPORT_SETTINGS);
  	echo "<input id='mailgunKey' name='" . MAILGUN_MEDIA_IMPORT_SETTINGS . "[mailgunKey]' size='42' type='text' value='{$options['mailgunKey']}' />";
  }

  function mailgunMediaImportFooGalleryEnabled() {
  	$options = get_option(MAILGUN_MEDIA_IMPORT_SETTINGS);
  	$checked = $options['foogalleryEnabled'] == 'true' ? 'checked' : '';
  	echo "<input id='foogalleryEnabled' name='" . MAILGUN_MEDIA_IMPORT_SETTINGS . "[foogalleryEnabled]' value='true' type='checkbox' $checked/>";
  }
  
  function mailgunMediaImportFooGalleryGalleryId() {
  	$options = get_option(MAILGUN_MEDIA_IMPORT_SETTINGS);
  	echo "<input id='fooGalleryId' min='1' name='" . MAILGUN_MEDIA_IMPORT_SETTINGS . "[fooGalleryId]' size='5' type='number' value='{$options['fooGalleryId']}' />";
  }
  
?>