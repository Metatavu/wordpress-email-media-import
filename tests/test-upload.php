<?php

use Vnn\WpApiClient\Auth\WpBasicAuth;
use Vnn\WpApiClient\Http\GuzzleAdapter;
use Vnn\WpApiClient\WpClient;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Class UploadTest
 *
 * @package Email_Media_Import
 */

/**
 * Upload test case.
 */
class UploadTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * Tests image uploadn
	 */
	function testUpload() {
	  $wpClient = new WpClient(new GuzzleAdapter(new GuzzleHttp\Client()), 'http://localhost:8080');
	  $wpClient->setCredentials(new WpBasicAuth('admin', 'password'));
	  $user = $wpClient->users()->get(1);
	  var_dump($user);
		/**
		
	  $pageId = $this->factory->post->create(array(
        'post-type' => 'page',
	    'post_title' => 'import',
	    'post_content' => '[email_media_import]',
	    'post_status' => 'published'
	  ));
		
	  $this->assertNotNull($pageId);
	  
	  var_dump($this->factory->post->get_object_by_id($pageId));
	  
		// $_POST = $this->createPostRequestData("ruumis", "123", "123", "123", "image/png", "http://fake.example.com/file.png", 12345);
		// var_dump($_POST);
		 *
		 */
	}
	
	function getRequest($url) {
	  return  file_get_contents($url, false, null);
	}
	
	
	/**
	function mockWebHook() {
	  $url = "http://localhost";
	  
		$url = 'http://server.com/path';
		$data = array('key1' => 'value1', 'key2' => 'value2');
		
		// use key 'http' even if you send the request to https://...
		$options = array(
				'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => http_build_query($data)
				)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { Handle error  }
		
		var_dump($result);	}
		**/
	function createPostRequestData($bodyPlain, $token, $signature, $imageName, $imageType, $imageUrl, $imageSize) {
	  $fromName = "Someone Special";
	  $fromMail = "someone-special@example.com";
	  $from = "\"$fromName\" <$fromMail>";
	  $bodyHtml = '<html><head><meta http-equiv=\"Content-Type\" content=\"text/html charset=us-ascii\"></head><body' . $bodyPlain . '</body></html>';
	  
	  return Array(
        'domain' => 'fake.mailgun.example',
	  	'subject' => 'test',
	  	'from' => $from,
	  	'content-id-map' => array("<ABCDEFG0-1234-5678-9ABC-DEFG01234567>" => "https://so.api.mailgun.net/v3/domains/example.com/messages/1234567890==/attachments/0"),
  		'message-url' => 'https://so.api.mailgun.net/v3/domains/example.com/messages/1234567890==',
	  	'recipient' => 'recipient@example.com',
	    'sender' => 'sender@example.com',
	  	'timestamp' => 1474726730,
	  	'token' => $token,
	  	'signature' => $signature,
	  	'body-plain' => $bodyPlain,
	  	'body-html' => $bodyHtml,
  		'stripped-plain' => $bodyPlain,
  		'stripped-html' => $bodyHtml,
	  	'attachments' => [
	  	  'url' => $imageUrl,
	  	  'content-type' => $imageType,
	  	  "name" => $imageName,
	  	  "size" => $imageSize
	  	]
      );
	}
	
}
