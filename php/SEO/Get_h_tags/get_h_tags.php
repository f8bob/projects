<?php

/**
 * Function to check if a target world page exists on the given website.
 * It verifies if the specified page contains the expected content.
 *
 * @param string $baseUrl The base URL of the website.
 * @return bool Returns true if the page contains the expected content, false otherwise.
 */
function checkTargetWorld($baseUrl) {
  $url = rtrim($baseUrl, '/') . '/target/world';
  $content = fetchContent($url);
  return strpos($content, 'The World of Target') !== false;
}

/**
 * Fetches the content of a given URL using cURL.
 *
 * @param string $url The URL to fetch.
 * @return string The HTML content of the URL.
 */
function fetchContent($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  $response = curl_exec($ch);
  curl_close($ch);
  return $response;
}

/**
 * Extracts H1-H6 tags from the provided HTML content.
 *
 * @param string $html The HTML content to parse.
 * @return string JSON encoded list of heading tags with their text.
 */
function getHTags($html) {
  $dom = new DOMDocument();
  libxml_use_internal_errors(true);
  $dom->loadHTML($html);
  libxml_clear_errors();

  $xpath = new DOMXPath($dom);
  $headings = [];

  for ($i = 1; $i <= 6; $i++) {
    foreach ($xpath->query("//h$i") as $node) {
      $headings[] = [
        'tag' => $node->nodeName,
        'text' => trim($node->textContent)
      ];
    }
  }

  return json_encode($headings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// List of example websites to check
$sites = [
  "https://example1.com/",
  "https://example2.com/",
  "https://example3.com/"
];

// List of pages to check on each site
$pages = [
  "/blogs/sample-page-1",
  "/blogs/sample-page-2",
  "/blogs/sample-page-3"
];

// Open CSV file for writing extracted data
$file = fopen("get_h_tags.csv", "w");
fputcsv($file, ["Website URL", "Page URL", "H Tags JSON"]);

// Iterate through each site and check target pages
foreach ($sites as $site) {
  if (checkTargetWorld($site)) {
    foreach ($pages as $page) {
      $pageUrl = rtrim($site, '/') . $page;
      echo "checking {$pageUrl}\n";
      $html = fetchContent($pageUrl);
      $hTagsJson = getHTags($html);
      fputcsv($file, [$site, $pageUrl, $hTagsJson]);
    }
  }
}

// Close the CSV file
fclose($file);

echo "Data has been saved to get_h_tags.csv";
