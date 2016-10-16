<?php
  namespace Metatavu\EmailMediaImport;

  if (!defined('ABSPATH')) {
  	exit;
  }
  
  require_once("constants.php");
  
  class TextProcessor {
  	
  	private $titleTag;
  	private $descriptionTag;
  	private $text;
  	
    public function __construct($text, $titleTag, $descriptionTag) {
      $this->text = $text;
  	  $this->titleTag = $titleTag ? $titleTag : 'title';
  	  $this->descriptionTag = $descriptionTag ? $descriptionTag : 'description';
    }
  	
  	public function getTitle() {
  	  return $this->extractTag($this->titleTag);
  	}
  	
  	public function getDescription() {
  	  return $this->extractTag($this->descriptionTag);
  	}
  	
  	private function extractTag($tag) {
      $output;
      preg_match_all("/\[$tag\]([^\[]*)\[\/$tag\]/", $this->text, $output);
      if (!empty($output) && is_array($output)) {
      	if (!empty($output[1]) && is_array($output[1])) {
      	  return $output[1][0];
      	}
  	  }
  		 
  	  return null;
  	}
  	
  }

?>