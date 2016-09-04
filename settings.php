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

  	if (is_plugin_active('foogallery/foogallery.php')) {
      add_settings_section('foogallery', __("Foo Gallery settings", MAILGUN_MEDIA_IMPORT_I18N_DOMAIN), null, MAILGUN_MEDIA_IMPORT_SETTINGS_PAGE);
      add_settings_field('foogalleryGalleryId', __('Add imported images into', MAILGUN_MEDIA_IMPORT_I18N_DOMAIN), 'mailgunMediaImportFooGalleryGalleryId', MAILGUN_MEDIA_IMPORT_SETTINGS_PAGE, 'foogallery');
  	}
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
  	$galleries = get_posts(array('post_type' => 'foogallery',
      'post_status' => 'publish',
  	  'suppress_filters' => true
  	));
  	
  	$options = get_option(MAILGUN_MEDIA_IMPORT_SETTINGS);
  	$galleryId = $options['foogalleryGalleryId'];
  	$noneTitle = __('None', MAILGUN_MEDIA_IMPORT_I18N_DOMAIN);
  	
  	if (count($galleries) > 0) {
  	  $defaultTitle = __(sprintf("Default (%s)", $galleries[0]->post_title), MAILGUN_MEDIA_IMPORT_I18N_DOMAIN);
  		
      echo '<select id="foogalleryGalleryId" name="' . MAILGUN_MEDIA_IMPORT_SETTINGS . '[foogalleryGalleryId]">';
      echo '<option value="none"' . ($galleryId == 'none' ? ' selected="selected"' : '') . '>' . $noneTitle . '</option>';
      echo '<option value="default"' . ($galleryId == 'default' ? ' selected="selected"' : '') . '>' . $defaultTitle . '</option>';
	  	
  	  foreach ($galleries as $gallery) {
	      if ($galleryId == $gallery->ID) {
	        echo '<option value="' . $gallery->ID . '" selected="selected">' . $gallery->post_title . '</option>';
	      } else {
	  	    echo '<option value="' . $gallery->ID . '">' . $gallery->post_title . '</option>';	
	      }
	    }
	  
	    echo '</select>';
  	} else {
      echo '<select id="foogalleryGalleryId" disabled="disabled" name="' . MAILGUN_MEDIA_IMPORT_SETTINGS . '[foogalleryGalleryId]">';
      echo '<option value="none">' . $noneTitle . '</option>';
      echo '</select>';
    }
  }
  
?>