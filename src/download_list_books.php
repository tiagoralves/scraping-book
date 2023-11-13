<?php

require 'vendor/autoload.php';

use Goutte\Client;

define('URL', "http://books.toscrape.com/");
define('CSV_FILE', 'files_download/books.csv');

function scrapBooks($client)
{
  return $client->request('GET', URL)->filter('.product_pod')->each(function ($node) {
    $title = $node->filter('h3 > a')->attr('title');
    $price = $node->filter('.price_color')->text();
    return [$title, $price];
  });
}

function writeCsv($data)
{
  $file = fopen(CSV_FILE, 'w');
  fputcsv($file, ['Title', 'Price']);
  foreach ($data as $row) {
    fputcsv($file, $row);
  }
  fclose($file);
}

try {
  $client = new Client();
  $bookData = scrapBooks($client);
  writeCsv($bookData);
  echo 'Records written to CSV file successfully!!';
} catch (Exception $e) {
  echo 'Error when trying to write records to the file CSV.' . $e->getMessage();
}
