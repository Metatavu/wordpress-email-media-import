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
   public function setUpTest() {
   	 echo "page count now :" . count($this->listPages());
   	
   	 date_default_timezone_set('UTC');
     $this->createPage("import", "[email_media_import]");
     $this->createFooGallery("firstgallery", "");
     $this->createFooGallery("secondgallery", "");
     $this->createFooGallery("thirdgallery", "");
     $this->assertCount(3, $this->listFooGalleries());
   }
   
   /**
    * @after
    */
   public function tearDownTest() {
   	 echo "delete :" . count($this->listPages()) . ' pages.'; 
   	
     $this->deletePages($this->listPages());
     $this->deleteFooGalleries($this->listFooGalleries());
     
     echo "now pages :" . count($this->listPages()) . ' pages.';
   }
  
  /**
   * Tests image upload
   */
  public function testUpload() {
  	$this->assertCount(0, $this->listMedias());
  	$uploadFolder = $this->getUploaFolder();
  	$uploadedFile = "$uploadFolder/test.png";
  	$this->assertFileNotExists($uploadedFile);	
  	$this->mockWebHook("", "test.png", "image/png", "https://upload.wikimedia.org/wikipedia/commons/d/d9/Test.png", 3118);
  	$this->assertFileExists($uploadedFile);
	$this->assertStringNotEqualsFile($uploadedFile, "");
	$this->assertCount(1, $this->listMedias());
	$this->assertEquals("", $this->listMedias()[0]->title->rendered);
	$this->assertEquals("", $this->listMedias()[0]->description);
	$this->deleteMedias($this->listMedias());
	
	$this->assertGalleryImageCount("firstgallery", 0);
	$this->assertGalleryImageCount("secondgallery", 0);
	$this->assertGalleryImageCount("thirdgallery", 0);
  }
  
  /**
   * Tests image title
   */
  public function testUploadTitle() {
  	$title = "image title";
  	
  	$this->assertCount(0, $this->listMedias());
  	$uploadFolder = $this->getUploaFolder();
  	$uploadedFile = "$uploadFolder/test.png";
  	$this->assertFileNotExists($uploadedFile);
  	$this->mockWebHook("[title]" . $title . "[/title]", "test.png", "image/png", "https://upload.wikimedia.org/wikipedia/commons/d/d9/Test.png", 3118);
  	$this->assertFileExists($uploadedFile);
  	$this->assertStringNotEqualsFile($uploadedFile, "");
  	$this->assertCount(1, $this->listMedias());
  	$this->assertEquals($title, $this->listMedias()[0]->title->rendered);
	$this->assertEquals("", $this->listMedias()[0]->description);
  	$this->deleteMedias($this->listMedias());
  }
  
  /**
   * Tests image description
   */
  public function testUploadDescription() {
  	$description = "image description";
  	
  	$this->assertCount(0, $this->listMedias());
  	$uploadFolder = $this->getUploaFolder();
  	$uploadedFile = "$uploadFolder/test.png";
  	$this->assertFileNotExists($uploadedFile);
  	$this->mockWebHook("[description]" . $description . "[/description]", "test.png", "image/png", "https://upload.wikimedia.org/wikipedia/commons/d/d9/Test.png", 3118);
  	$this->assertFileExists($uploadedFile);
  	$this->assertStringNotEqualsFile($uploadedFile, "");
  	$this->assertCount(1, $this->listMedias());
	$this->assertEquals("", $this->listMedias()[0]->title->rendered);
  	$this->assertEquals($description, $this->listMedias()[0]->description);
  	$this->deleteMedias($this->listMedias());
  }
  
  /**
   * Tests image title and description
   */
  public function testUploadTitleAndDescription() {
  	$title = "image title";
  	$description = "image description";
  	
  	$this->assertCount(0, $this->listMedias());
  	$uploadFolder = $this->getUploaFolder();
  	$uploadedFile = "$uploadFolder/test.png";
  	$this->assertFileNotExists($uploadedFile);
  	$this->mockWebHook("[title]" . $title . "[/title][description]" . $description . "[/description]", "test.png", "image/png", "https://upload.wikimedia.org/wikipedia/commons/d/d9/Test.png", 3118);
  	$this->assertFileExists($uploadedFile);
  	$this->assertStringNotEqualsFile($uploadedFile, "");
  	$this->assertCount(1, $this->listMedias());
  	$this->assertEquals($title, $this->listMedias()[0]->title->rendered);
  	$this->assertEquals($description, $this->listMedias()[0]->description);
  	$this->deleteMedias($this->listMedias());
  }
  
  /**
   * Tests image gallery, title and description
   */
  public function testUploadGalleryTitleAndDescription() {
  	$title = "image title";
  	$description = "image description";
  	
  	$this->assertCount(0, $this->listMedias());
  	$uploadFolder = $this->getUploaFolder();
  	$uploadedFile = "$uploadFolder/test.png";
  	$this->assertFileNotExists($uploadedFile);
  	$this->mockWebHook("[title]" . $title . "[/title][description]" . $description . "[/description][gallery]firstgallery[/gallery]", "test.png", "image/png", "https://upload.wikimedia.org/wikipedia/commons/d/d9/Test.png", 3118);
  	$this->assertFileExists($uploadedFile);
  	$this->assertStringNotEqualsFile($uploadedFile, "");
  	$this->assertCount(1, $this->listMedias());
  	$this->assertEquals($title, $this->listMedias()[0]->title->rendered);
  	$this->assertEquals($description, $this->listMedias()[0]->description);
    $this->assertGalleryImageCount("firstgallery", 1);
    $this->assertGalleryImageCount("secondgallery", 0);
    $this->assertGalleryImageCount("thirdgallery", 0);
  	$this->deleteMedias($this->listMedias());
  }

  /**
   * Tests multiple galleries import
   */
  public function testUploadMultipleGalleries() {
  	$this->assertCount(0, $this->listMedias());
  	$uploadFolder = $this->getUploaFolder();
  	$uploadedFile = "$uploadFolder/test.png";
  	$this->assertFileNotExists($uploadedFile);
  	$this->mockWebHook("[gallery]firstgallery[/gallery][gallery]thirdgallery[/gallery]", "test.png", "image/png", "https://upload.wikimedia.org/wikipedia/commons/d/d9/Test.png", 3118);
  	$this->assertFileExists($uploadedFile);
  	$this->assertStringNotEqualsFile($uploadedFile, "");
  	$this->assertCount(1, $this->listMedias());
  	$this->assertGalleryImageCount("firstgallery", 1);
  	$this->assertGalleryImageCount("secondgallery", 0);
  	$this->assertGalleryImageCount("thirdgallery", 1);
  	$this->deleteMedias($this->listMedias());
  }
  
  private function getUploaFolder() {
  	$dateFolder = date("Y/m");
  	return "$this->wpDir/wp-content/uploads/$dateFolder/";
  }
  
  private function createPage($title, $content) {
  	$client = new GuzzleHttp\Client();
  	$response = $client->post("http://localhost:1234/wp-json/wp/v2/pages", [
  	  "json" => [
	    "title" => $title,
	    "content" => $content,
	    "status" => "publish"
  	  ],
  	  "auth" => ["admin", "password"]
  	]);
  	
  	$this->assertNotNull($response);
  	$this->assertEquals(200, $response->getStatusCode());
  	
  	$body = $response->getBody();
  	$this->assertNotNull($body);
  	
  	return json_decode($body);
  }
  
  private function searchPage($search) {
    $client = new GuzzleHttp\Client();
    $response = $client->get("http://localhost:1234/wp-json/wp/v2/pages?search=$search");
    $this->assertNotNull($response);
    $body = $response->getBody();
    $this->assertNotNull($body);
     
    $pages = json_decode($body);
    $this->assertCount(1, $pages);
    
    return $pages[0];
  }
  
  private function deletePage($page) {
  	$client = new GuzzleHttp\Client();
  	$response = $client->delete("http://localhost:1234/wp-json/wp/v2/pages/$page->id?force=true", [
  	  "auth" => ["admin", "password"]
  	]);
  			
  	$this->assertEquals(200, $response->getStatusCode());
  }

  private function deletePages($pages) {
  	foreach ($pages as $page) {
  		$this->deletePage($page);
  	}
  }
  
  private function createFooGallery($title, $content) {
  	$client = new GuzzleHttp\Client();
  	$response = $client->post("http://localhost:1234/wp-json/wp/v2/foogallery", [
  	  "json" => [
  	    "title" => $title,
  	    "content" => $content,
  	    "status" => "publish"
  	  ],
  	  "auth" => ["admin", "password"]
  	]);
  	 
  	$this->assertNotNull($response);
  	$this->assertGreaterThanOrEqual(200, $response->getStatusCode());
  	$this->assertLessThanOrEqual(299, $response->getStatusCode());
  	 
  	$body = $response->getBody();
  	$this->assertNotNull($body);
  	 
  	return json_decode($body);
  }
  
  private function listFooGalleries() {
  	$client = new GuzzleHttp\Client();
  	$response = $client->get("http://localhost:1234/wp-json/wp/v2/foogallery");
  	$this->assertNotNull($response);
  	$body = $response->getBody();
  	$this->assertNotNull($body);
  
  	return json_decode($body);
  }
  
  private function deleteFooGallery($fooGallery) {
  	$client = new GuzzleHttp\Client();
  	$response = $client->delete("http://localhost:1234/wp-json/wp/v2/foogallery/$fooGallery->id?force=true", [
  	  "auth" => ["admin", "password"]
  	]);
  			
  	$this->assertEquals(200, $response->getStatusCode());
  }
  
  private function deleteFooGalleries($fooGalleries) {
  	if ($fooGalleries) {
      foreach ($fooGalleries as $fooGallery) {
	    $this->deleteFooGallery($fooGallery);
  	  }
  	}
  }
  
  private function listPages() {
  	$client = new GuzzleHttp\Client();
  	$response = $client->get("http://localhost:1234/wp-json/wp/v2/pages");
  	$this->assertNotNull($response);
  	$body = $response->getBody();
  	$this->assertNotNull($body);
  	 
  	return json_decode($body);
  }
  
  private function listMedias() {
    $client = new GuzzleHttp\Client();
    $response = $client->get("http://localhost:1234/wp-json/wp/v2/media");
    $this->assertNotNull($response);
    $body = $response->getBody();
    $this->assertNotNull($body);
     
    return json_decode($body);
  }
  
  private function listMediasByParent($parentId) {
  	$client = new GuzzleHttp\Client();
  	$response = $client->get("http://localhost:1234/wp-json/wp/v2/media?parent=$parentId");
  	$this->assertNotNull($response);
  	$body = $response->getBody();
  	$this->assertNotNull($body);
  	 
  	return json_decode($body);
  }
  
  private function deleteMedias($medias) {
  	$client = new GuzzleHttp\Client();
  	foreach ($medias as $media) {
  	  $response = $client->delete("http://localhost:1234/wp-json/wp/v2/media/$media->id?force=true", [
  	  	"auth" => ["admin", "password"]
  	  ]);
  	  
  	  $this->assertEquals(200, $response->getStatusCode());
  	}
  }
  
  private function assertGalleryImageCount($search, $expected) {
  	$gallery = $this->searchGallery($search);
  	$this->assertCount($expected, $gallery->images);
  }

  private function searchGallery($search) {
  	$client = new GuzzleHttp\Client();
  	$response = $client->get("http://localhost:1234/wp-json/wp/v2/foogallery?search=$search");
  	$this->assertNotNull($response);
  	$body = $response->getBody();
  	$this->assertNotNull($body);
  	 
  	$pages = json_decode($body);
  	$this->assertCount(1, $pages);
  
  	return $pages[0];
  }
  
  private function mockWebHook($body, $imageName, $imageType, $imageUrl, $imageSize) {
  	$importPage = $this->searchPage("import");
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
    $bodyHtml = $this->createBodyHtmlData($bodyPlain);
    
    return [
      'domain' => 'fake.mailgun.example',
      'subject' => 'test',
      'from' => $from,
      'content-id-map' => $this->createContentIdMapData(),
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
      'attachments' => json_encode([
        $this->createAttachmentData($imageName, $imageType, $imageUrl, $imageSize)
      ])
    ];
  }
  
  private function createContentIdMapData() {
  	return array("<ABCDEFG0-1234-5678-9ABC-DEFG01234567>" => "https://so.api.mailgun.net/v3/domains/example.com/messages/1234567890==/attachments/0");
  }
  
  private function createBodyHtmlData($bodyPlain) {
  	return '<html><head><meta http-equiv=\"Content-Type\" content=\"text/html charset=us-ascii\"></head><body' . $bodyPlain . '</body></html>';
  }
  
  private function createAttachmentData($imageName, $imageType, $imageUrl, $imageSize) {
  	return [
      "url" => $imageUrl,
      "content-type" => $imageType,
      "name" => $imageName,
      "size" => $imageSize
    ];
  }
  
}
