=== Email Media Import ===
Contributors: metatavu
Tags: mailgun, media, email
Requires at least: 4.6
Tested up to: 4.6
Stable tag: 0.0.3
License: GPLv2 or later.

== Description ==

wordpress-email-media-import plugin allows users to upload images into Wordpress Media Gallery by sending emails into specific email address.

Plugin does this by utilizing Mailgun's (http://www.mailgun.com/) routes (https://documentation.mailgun.com/api-routes.html) feature.

Plugin also supports adding uploaded images to be added into Foo Gallery (https://wordpress.org/plugins/foogallery/) if the the Foo Gallery plugin is active in the installation.

Key Features:

  * Allows users to upload image via e-mail
  * Supports automatic orientation fixing 
  * Supports automatic scaling
  * Supports adding uploaded images into Foo Gallery
  
== Installation ==

  * Upload folder into /wp-content/plugins -directory
  * Activate the plugin in 'Plugins' menu
  * Change 'Mailgun API Key' to match your Mailgun's private key in Mailgun Media Import Settings
  * Create new route in Mailgun administration panel, check the "store and notify" box and type in https://www.mywordpress.com/mailgun-media-import as notification address

== Changelog ==

= 0.4 =
* Renamed plugin into Email media plugin

= 0.3 =
* Support for autoscaling images
* Support for auto-orientating images

= 0.2 =
* Added support for adding images into Foo Gallery

= 0.1 =
* Initial version