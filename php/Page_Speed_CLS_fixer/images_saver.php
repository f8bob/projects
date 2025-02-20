<?php

$url = "http://example.com/";
$local_dir = "saved/";
$search_for = "public/1.0/uploads/source"; //example of path for the filtering the images from the website

//https://pinetools.com/html-beautifier

try {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'authority: example.com',
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'accept-language: ru,en;q=0.9,en-GB;q=0.8,en-US;q=0.7',
    'cache-control: max-age=0',
    'sec-ch-ua: ^\^"Chromium^\^";v=^\^"116^\^", ^\^"Not)A;Brand^\^";v=^\^"24^\^", ^\^"Microsoft Edge^\^";v=^\^"116^\^"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: ^\^"Windows^\^"',
    'sec-fetch-dest: document',
    'sec-fetch-mode: navigate',
    'sec-fetch-site: none',
    'sec-fetch-user: ?1',
    'upgrade-insecure-requests: 1',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36 Edg/116.0.1938.62',
    'compressed'
  ));
  $html = curl_exec($ch);
  curl_close($ch);
} catch (Exception $e) {
  die('Error with getting HTML: ' . $e->getMessage());
}

$regex = '/src="([^"]*)"/';
preg_match_all($regex, $html, $matches);
$src_values = $matches[1];

foreach ($src_values as $key => $src_value) {
  if (strpos($src_value, $search_for) === false) {
    unset($src_values[$key]);
  }
}
$src_values = array_unique($src_values);

function createdir($local_dir) {
  $local_dirs = explode("/", $local_dir); $folder = __DIR__; foreach ($local_dirs as $local_dir) { $folder = $folder."/".$local_dir; if (!file_exists($folder)) mkdir($folder); }
}

$fp = fopen('images_size.csv', 'w');
fputcsv($fp, array('Image URL', 'Width', 'Height'));

$total = count($src_values);
foreach ($src_values as $src_value) {
  $src_value = str_replace($url, "", $src_value);

  if (stripos($src_value, "skip_folder") !== false) continue;

  $source = $url.$src_value;
  $path = $local_dir.str_replace($search_for, "", $source);
  $path = str_replace($url, "", $path);
  $filename = basename($path);
  $path = str_replace($filename, "", $path);

  if (!file_exists($path.$filename)) {
    try {
      $file = file_get_contents($source);
      createdir($path);
      file_put_contents($path.$filename, $file);
    } catch (Exception $e) {
      echo 'Error with file saving: ' . $e->getMessage();
      continue;
    }
  }

  try {
    $size = getimagesize($source);
    $width = $size[0];
    $height = $size[1];
    fputcsv($fp, array($src_value, $width, $height));
  } catch (Exception $e) {
    echo 'Error with images sizes calculating: ' . $e->getMessage();
  }
}
