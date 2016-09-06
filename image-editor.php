<?php
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

  require_once("constants.php");
  
  class ImageEditor {
  	
  	private $image;
  	private $exifData;
  	private $imageName;
  	
  	public function __construct($file) {
      $this->imageName = $file['name'];
  	  $imageSrc = get_temp_dir() . wp_unique_filename(get_temp_dir(), $this->imageName);
  	  
  	  file_put_contents($imageSrc, $file['data']);
  	  try {
        $this->image = wp_get_image_editor($imageSrc);
        if (is_wp_error($this->image) ) {
  	      error_log($this->image->get_error_message());
  	    } else { 
    	  $this->exifData = exif_read_data($imageSrc);
    	  if (!$this->exifData) {
  	    	error_log("Could not read image EXIF data");
  	      }
  	    }
  	  } finally {
  	  	unlink($imageSrc);
  	  }
  	}
  	
  	public function scaleImage($maxWidth, $maxHeight) {
  	  if (!is_wp_error($this->image) ) {
  	    $this->image->resize($maxWidth, $maxHeight);
  	  }
  	}
  	
  	public function fixOrientation() {
  	  if (is_wp_error($this->image) ) {
  	  	return;
  	  }
  	  
  	  if (!$this->exifData) {
  	  	return;
  	  }
  		
  	  $rotate = null;
  	  $flipHorzontal = false;
  	  $flipVertical = false;
  	  
  	  switch ($this->exifData['Orientation']) {
  	  	case 2:
  	  	  $flipHorzontal = true;
  	    break;
  	  	case 3:
  	  	  $rotate = -180;
  	    break;
  	  	case 4:
  	  	  $flipVertical = true;
  	  	break;
  	  	case 5:
  	  	  $rotate = -90;
  	  	  $flipHorzontal = true;
  	  	break;
  	  	case 6:
  	  	  $rotate = -90;
  	    break;
  	  	case 7:
  	  	  $rotate = -270;
  	  	  $flipHorzontal = true;
  	  	break;
  	  	case 8:
  	  	case 9:
  	  	  $rotate = -270;
  	  	break;
  	  	default:
  	    break;
  	  }
  	  
  	  if ($rotate != null) {
  	  	$this->image->rotate($rotate);
  	  }
  	  
  	  if ($flipHorzontal || $flipVertical) {
  	  	$this->image->flip($flipHorzontal, $flipVertical);
  	  }
  	}
  	
  	public function save() {
  	  if (!is_wp_error($this->image)) {
  	    return $this->image->save(wp_upload_dir()['path'] . '/' . $this->imageName);
  	  }
  	}
  	
  }

?>