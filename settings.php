<?php
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
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
  	 
  	if (is_plugin_active('foogallery/foogallery.php')) {
      add_settings_section('foogallery', __("Foo Gallery settings", EMAIL_MEDIA_IMPORT_I18N_DOMAIN), null, EMAIL_MEDIA_IMPORT_SETTINGS_PAGE);
      add_settings_field('foogalleryGalleryId', __('Add imported images into', EMAIL_MEDIA_IMPORT_I18N_DOMAIN), 'emailMediaImportFooGalleryGalleryId', EMAIL_MEDIA_IMPORT_SETTINGS_PAGE, 'foogallery');
  	}
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
  
  function emailMediaImportFooGalleryEnabled() {
  	$options = get_option(EMAIL_MEDIA_IMPORT_SETTINGS);
  	$checked = $options['foogalleryEnabled'] == 'true' ? 'checked' : '';
  	echo "<input id='foogalleryEnabled' name='" . EMAIL_MEDIA_IMPORT_SETTINGS . "[foogalleryEnabled]' value='true' type='checkbox' $checked/>";
  }
  
  function emailMediaImportFooGalleryGalleryId() {
  	$galleries = get_posts(array('post_type' => 'foogallery',
      'post_status' => 'publish',
  	  'suppress_filters' => true
  	));
  	
  	$options = get_option(EMAIL_MEDIA_IMPORT_SETTINGS);
  	$galleryId = $options['foogalleryGalleryId'];
  	$noneTitle = __('None', EMAIL_MEDIA_IMPORT_I18N_DOMAIN);
  	
  	if (count($galleries) > 0) {
  	  $defaultTitle = __(sprintf("Default (%s)", $galleries[0]->post_title), EMAIL_MEDIA_IMPORT_I18N_DOMAIN);
  		
      echo '<select id="foogalleryGalleryId" name="' . EMAIL_MEDIA_IMPORT_SETTINGS . '[foogalleryGalleryId]">';
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
      echo '<select id="foogalleryGalleryId" disabled="disabled" name="' . EMAIL_MEDIA_IMPORT_SETTINGS . '[foogalleryGalleryId]">';
      echo '<option value="none">' . $noneTitle . '</option>';
      echo '</select>';
    }
  }
  
?>