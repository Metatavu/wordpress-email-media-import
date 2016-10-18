<?php

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
	  $client = new GuzzleHttp\Client();
	  
	  $response = $client->get("http://localhost:8080/wp-json/wp/v2/pages");
	  echo "Body: " . $response->getBody();
	  echo "Link: " . $page->link;
	  
	  $response = $client->get("http://localhost:8080/wp-json/wp/v2/posts");
	  echo "Body: " . $response->getBody();
	  echo "Link: " . $page->link;
	}
	
	function getRequest($url) {
	  return  file_get_contents($url, false, null);
	}
	
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
