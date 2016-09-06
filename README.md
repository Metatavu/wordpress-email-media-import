# wordpress-mailgun-media-import

wordpress-mailgun-media-import plugin allows users to upload images into Wordpress Media Gallery by sending emails into specific email address.

Plugin does this by utilizing Mailgun's (http://www.mailgun.com/) routes (https://documentation.mailgun.com/api-routes.html) feature.

Plugin also supports adding uploaded images to be added into Foo Gallery (https://wordpress.org/plugins/foogallery/) if the the Foo Gallery plugin is active in the installation.

Key Features:

  * Allows users to upload image via e-mail
  * Supports automatic orientation fixing 
  * Supports automatic scaling
  * Supports adding uploaded images into Foo Gallery
  
## Installation

  * Upload folder into /wp-content/plugins -directory
  * Activate the plugin in 'Plugins' menu
  * Change 'Mailgun API Key' to match your Mailgun's private key in Mailgun Media Import Settings
  * Create new route in Mailgun administration panel, check the "store and notify" box and type in https://www.mywordpress.com/mailgun-media-import as notification address


