<?php
  if (!defined('ABSPATH')) {
  	exit;
  }
  
  require_once("constants.php");
  
  if (is_admin()) {
  	add_action('admin_menu', 'emailMediaImportSettingsMenu');
  	add_action('admin_init', 'emailMediaImportRegisterSettings');
  }
  
  /* Settings Page */
  
  function emailMediaImportSettingsMenu() {
  	add_options_page (__( "Email Media Import", EMAIL_MEDIA_IMPORT_I18N_DOMAIN ), __( "Email Media Import", EMAIL_MEDIA_IMPORT_I18N_DOMAIN ), 'manage_options', 'email_media_import_settings', 'emailMediaImportSettingsPage' );
  }
  
  function emailMediaImportSettingsPage() {
  	if (!current_user_can('manage_options')) {
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  	}
  	
  	echo '<div class="wrap">';
  	echo "<h2>" . __( "Email Media Import Settings", EMAIL_MEDIA_IMPORT_I18N_DOMAIN) . "</h2>";
  	echo '<form action="options.php" method="POST">';
  	
  	settings_fields(EMAIL_MEDIA_IMPORT_SETTINGS_GROUP);
  	do_settings_sections(EMAIL_MEDIA_IMPORT_SETTINGS_PAGE);
  	submit_button();
  	
  	echo "</form>";
  	echo "</div>";
  }
  
  /* Settings */
  
  function emailMediaImportRegisterSettings() {
  	register_setting(EMAIL_MEDIA_IMPORT_SETTINGS_GROUP, EMAIL_MEDIA_IMPORT_SETTINGS);
  	add_settings_section('core', __('Email Media Import Settings', EMAIL_MEDIA_IMPORT_I18N_DOMAIN), null, EMAIL_MEDIA_IMPORT_SETTINGS_PAGE);
  	add_settings_field('mailgunKey', __('Mailgun API Key', EMAIL_MEDIA_IMPORT_I18N_DOMAIN), 'emailMediaImportMailgunKey', EMAIL_MEDIA_IMPORT_SETTINGS_PAGE, 'core');
  	add_settings_field('maxWidth', __('Max image width', EMAIL_MEDIA_IMPORT_I18N_DOMAIN), 'emailMediaImportMaxWidth', EMAIL_MEDIA_IMPORT_SETTINGS_PAGE, 'core');
  	add_settings_field('maxHeight', __('Max image height', EMAIL_MEDIA_IMPORT_I18N_DOMAIN), 'emailMediaImportMaxHeight', EMAIL_MEDIA_IMPORT_SETTINGS_PAGE, 'core');
  	add_settings_field('importUser', __('Importing user id', EMAIL_MEDIA_IMPORT_I18N_DOMAIN), 'emailMediaImportImportUser', EMAIL_MEDIA_IMPORT_SETTINGS_PAGE, 'core');
  	add_settings_section('tags', __('Email Media Tag Settings', EMAIL_MEDIA_IMPORT_I18N_DOMAIN), null, EMAIL_MEDIA_IMPORT_SETTINGS_PAGE);
  	add_settings_field('titleTag', __('Title tag', EMAIL_MEDIA_IMPORT_I18N_DOMAIN), 'emailMediaImportTitleTag', EMAIL_MEDIA_IMPORT_SETTINGS_PAGE, 'tags');
  	add_settings_field('descriptionTag', __('Description tag', EMAIL_MEDIA_IMPORT_I18N_DOMAIN), 'emailMediaImportDescriptionTag', EMAIL_MEDIA_IMPORT_SETTINGS_PAGE, 'tags');
  	add_settings_field('galleryTag', __('Gallery tag', EMAIL_MEDIA_IMPORT_I18N_DOMAIN), 'emailMediaImportGalleryTag', EMAIL_MEDIA_IMPORT_SETTINGS_PAGE, 'tags');
  }

  function emailMediaImportMailgunKey() {
  	$options = get_option(EMAIL_MEDIA_IMPORT_SETTINGS);
  	echo "<input id='mailgunKey' name='" . EMAIL_MEDIA_IMPORT_SETTINGS . "[mailgunKey]' size='42' type='text' value='{$options['mailgunKey']}' />";
  }

  function emailMediaImportMaxWidth() {
  	$options = get_option(EMAIL_MEDIA_IMPORT_SETTINGS);
  	echo "<input id='maxWidth' name='" . EMAIL_MEDIA_IMPORT_SETTINGS . "[maxWidth]' size='8' type='number' value='{$options['maxWidth']}' />";
  }

  function emailMediaImportMaxHeight() {
  	$options = get_option(EMAIL_MEDIA_IMPORT_SETTINGS);
  	echo "<input id='maxHeight' name='" . EMAIL_MEDIA_IMPORT_SETTINGS . "[maxHeight]' size='8' type='number' value='{$options['maxHeight']}' />";
  }

  function emailMediaImportImportUser() {
  	$options = get_option(EMAIL_MEDIA_IMPORT_SETTINGS);
  	echo "<input id='importUser' name='" . EMAIL_MEDIA_IMPORT_SETTINGS . "[importUser]' size='8' type='number' value='{$options['importUser']}' />";
  }

  function emailMediaImportTitleTag() {
  	$options = get_option(EMAIL_MEDIA_IMPORT_SETTINGS);
  	echo "<input id='titleTag' name='" . EMAIL_MEDIA_IMPORT_SETTINGS . "[titleTag]' size='42' type='text' value='{$options['titleTag']}' placeholder='title'/>";
  }

  function emailMediaImportDescriptionTag() {
  	$options = get_option(EMAIL_MEDIA_IMPORT_SETTINGS);
  	echo "<input id='descriptionTag' name='" . EMAIL_MEDIA_IMPORT_SETTINGS . "[descriptionTag]' size='42' type='text' value='{$options['descriptionTag']}' placeholder='description'/>";
  }

  function emailMediaImportGalleryTag() {
  	$options = get_option(EMAIL_MEDIA_IMPORT_SETTINGS);
  	echo "<input id='galleryTag' name='" . EMAIL_MEDIA_IMPORT_SETTINGS . "[galleryTag]' size='42' type='text' value='{$options['galleryTag']}' placeholder='gallery'/>";
  }
  
  
?>