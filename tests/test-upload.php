<?php
/**
 * Class SampleTest
 *
 * @package Email_Media_Import
 */

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase {
	
	/**
	 * A single example test.
	 */
	function test_sample() {
		// Replace this with some actual testing code.
		$this->assertTrue( true );
	}
	

	function createPostData($bodyPlain, $token, $signature, $imageName, $imageType, $imageUrl, $imageSize) {
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
