<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

// Read the CSV file
$csvFile = fopen("images_size.csv", "r");

// Create an array to store the image data
$imageData = [];

// Loop through each line in the CSV file
while (($data = fgetcsv($csvFile)) !== false) {
  // Extract the image URL, width, and height from the CSV data
  $imageUrl = $data[0];
  $width = $data[1];
  $height = $data[2];

  // Add the image data to the array
  $imageData[$imageUrl] = [
    'width' => $width,
    'height' => $height
  ];
}


// Close the CSV file
fclose($csvFile);

// fix html5/svg errors
libxml_use_internal_errors(true);

// Read the HTML file
$htmlFile = file_get_contents("index.html");

// Create a DOMDocument object to parse the HTML
$dom = new DOMDocument();
$dom->loadHTML($htmlFile);

// Get all the <img> tags in the HTML
$images = $dom->getElementsByTagName('img');

// Loop through each <img> tag
foreach ($images as $image) {
  // Get the src attribute of the <img> tag
  $src = $image->getAttribute('src');

  // Check if the src attribute exists in the image data array
  if (isset($imageData[$src])) {
    print_r($imageData[$src]);
    // Get the width and height from the image data array
    $width = $imageData[$src]['width'];
    $height = $imageData[$src]['height'];

    // Set the width and height attributes of the <img> tag
    $image->setAttribute('width', $width);
    $image->setAttribute('height', $height);
  }
}

// Save the modified HTML back to the file
$modifiedHtml = $dom->saveHTML();
file_put_contents("index.html", $modifiedHtml);

?>
