<?php
  namespace Metatavu\EmailMediaImport;

  if (!defined('ABSPATH')) {
    exit;
  }
  
  class TextProcessor {
    
    private $titleTag;
    private $descriptionTag;
    private $galleryTag;
    private $text;
    
    public function __construct($text, $titleTag, $descriptionTag, $galleryTag) {
      $this->text = $text;
      $this->titleTag = $titleTag ? $titleTag : 'title';
      $this->descriptionTag = $descriptionTag ? $descriptionTag : 'description';
      $this->galleryTag = $galleryTag ? $galleryTag : 'gallery';
    }
    
    public function getTitle() {
      return $this->extractTag($this->titleTag);
    }
    
    public function getDescription() {
    	return $this->extractTag($this->descriptionTag);
    }
    
    public function getGalleries() {
      return $this->extractTags($this->galleryTag);
    }
    
    private function extractTags($tag) {
      $output;
      preg_match_all("/\[$tag\]([^\[]*)\[\/$tag\]/", $this->text, $output);
      if (!empty($output) && is_array($output) && !empty($output[1]) && is_array($output[1])) {
        return $output[1];
      }
        
      return [];
    }
    
    private function extractTag($tag) {
      $values = $this->extractTags($tag);
      if (!empty($values) && is_array($values)) {
      	return $values[0];
      }
      
      return null;
    }
    
  }

?>