<?php
/*
 * require setasign\Fpdi\Fpdi;
*/

require_once('../../inc/php/backend/libraries/vendor/autoload.php');
use setasign\Fpdi\Fpdi;

class FileCompressor {
  private $quality;

  public function __construct($quality = 80) {
    $this->quality = $quality;
  }

  public function compressFilesInFolder($folder_path) {
    $items = scandir($folder_path);

    foreach ($items as $item) {
      if ($item == '.' || $item == '..') {
        continue;
      }

      $item_path = $folder_path . '/' . $item;

      if (is_dir($item_path)) {
        $this->compressFilesInFolder($item_path);
      } else {
        if ($this->isImageFile($item)) {
          echo $item_path." | ";
          $image = $this->createImageFromFile($item_path);
          $this->compressImage($image, $item_path);
        } /*elseif ($this->isPdfFile($item)) {
                    $this->compressPdf($item_path);
                }*/
      }
    }
  }

  private function isImageFile($file) {
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($file_extension, $allowed_extensions);
  }

  private function isPdfFile($file) {
    $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return $file_extension === 'pdf';
  }

  private function createImageFromFile($file_path) {
    $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    switch ($file_extension) {
      case 'jpg':
      case 'jpeg':
        return imagecreatefromjpeg($file_path);
      case 'png':
        return imagecreatefrompng($file_path);
      case 'gif':
        return imagecreatefromgif($file_path);
      default:
        return false;
    }
  }

  private function compressImage($image, $file_path) {
    if ($image) {
      echo $file_path."\n";
      imagejpeg($image, $file_path, $this->quality);
      imagedestroy($image);
    }
  }

  private function compressPdf($file_path) {
    $pdf = new Fpdi();
    $page_count = $pdf->setSourceFile($file_path);
    $pdf->SetCompression(true, 9);

    for ($page = 1; $page <= $page_count; $page++) {
      $pdf->AddPage();
      $pdf->useTemplate($pdf->importPage($page));
    }

    $pdf->Output($file_path, 'F');
  }
}

$fileCompressor = new FileCompressor(85);
$fileCompressor->compressFilesInFolder('saved');
