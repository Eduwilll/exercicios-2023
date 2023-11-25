<?php

namespace Chuva\Php\WebScrapping;

/**
 * Runner for the Webscrapping exercice.
 */
class Main {

  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $htmlFilePath = '/../../assets/origin.html';
    $dom->loadHTMLFile(__DIR__ . $htmlFilePath);
    $scrapper = new Scrapper();
    $data = $scrapper->scrap($dom);

    // Print scraped data.
    print_r($data);

    // Save scraped data as JSON, formatting for readability.
    $jsonFilePath = __DIR__ . './scraped_data.json';
    file_put_contents($jsonFilePath, json_encode($data, JSON_PRETTY_PRINT));

    // Set XLSX file path and export data to XLSX.
    $outputFilePath = './assets/output.xlsx';
    $scrapper->exportToXlsx($data, $outputFilePath);
    /* echo 'XLSX file created successfully at ' . $outputFilePath . PHP_EOL; */
  }

}
