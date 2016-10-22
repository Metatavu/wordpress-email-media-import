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
	
  private $wpDir = "tests/wp"; 
	
  /**
   * @before
   */
   public function setupTimezone() {
     date_default_timezone_set('UTC');
   }
  
  /**
   * Tests image uploadn
   */
  public function testUpload() {
  	$uploadFolder = $this->getUploaFolder();
  	$uploadedFile = "$uploadFolder/test.png";
  	$this->assertFileNotExists($uploadedFile);	
  	$this->mockWebHook("", "test.png", "image/png", "https://upload.wikimedia.org/wikipedia/commons/d/d9/Test.png", 3118);
  	$this->assertFileExists($uploadedFile);
	$this->assertStringNotEqualsFile($uploadedFile, "");
	
	$this->deleteFolder($uploadFolder);
  }
  
  private function deleteFolder($folder) {
  	array_map('unlink', glob("$folder*"));
  	rmdir($folder);
  }
  
  private function getUploaFolder() {
  	$dateFolder = date("Y/m");
  	return "$this->wpDir/wp-content/uploads/$dateFolder/";
  }
  
  private function findPage($search) {
    $client = new GuzzleHttp\Client();
    $response = $client->get("http://localhost:1234/wp-json/wp/v2/pages?search=$search");
    $this->assertNotNull($response);
    $body = $response->getBody();
    $this->assertNotNull($body);
     
    $pages = json_decode($body);
    $this->assertCount(1, $pages);
    
    return $pages[0];
  }
  
  private function mockWebHook($body, $imageName, $imageType, $imageUrl, $imageSize) {
  	$importPage = $this->findPage("import");
  	$importUrl = $importPage->link;
  	$this->assertNotNull($importUrl);
  	
  	$client = new GuzzleHttp\Client();
    $timestamp = 12345;
  	$token = "token";
  	$key = "key";
  	$signature = hash_hmac('sha256', $timestamp . $token, $key);
  	
  	$json = $this->createPostRequestData($body, $timestamp, $token, $signature, $imageName, $imageType, $imageUrl, $imageSize);
  	
  	$client->post($importUrl, [
      "form_params" => $json
  	]);
  }
  
  function getRequest($url) {
    return  file_get_contents($url, false, null);
  }
  
  private function createPostRequestData($bodyPlain, $timestamp, $token, $signature, $imageName, $imageType, $imageUrl, $imageSize) {
    $fromName = "Someone Special";
    $fromMail = "someone-special@example.com";
    $from = "\"$fromName\" <$fromMail>";
    $bodyHtml = '<html><head><meta http-equiv=\"Content-Type\" content=\"text/html charset=us-ascii\"></head><body' . $bodyPlain . '</body></html>';
    
    return [
      'domain' => 'fake.mailgun.example',
      'subject' => 'test',
      'from' => $from,
      'content-id-map' => array("<ABCDEFG0-1234-5678-9ABC-DEFG01234567>" => "https://so.api.mailgun.net/v3/domains/example.com/messages/1234567890==/attachments/0"),
      'message-url' => 'https://so.api.mailgun.net/v3/domains/example.com/messages/1234567890==',
      'recipient' => 'recipient@example.com',
      'sender' => 'sender@example.com',
      'timestamp' => $timestamp,
      'token' => $token,
      'signature' => $signature,
      'body-plain' => $bodyPlain,
      'body-html' => $bodyHtml,
      'stripped-plain' => $bodyPlain,
      'stripped-html' => $bodyHtml,
      'attachments' => json_encode([[
        'url' => $imageUrl,
        'content-type' => $imageType,
        "name" => $imageName,
        "size" => $imageSize
      ]])
    ];
  }
  
}
