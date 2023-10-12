<?php

namespace Chuva\Php\WebScrapping;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

    /**
     * @param \DOMDocument The DOMDocument object.
     * 
     * @return array The result as an array.
     */
    public function scrap(\DOMDocument $dom): array {
        
        $data = [];
        $anchors = $dom->getElementsByTagName('a'); // Select all <a> elements with class 'paper-card'. 

        foreach ($anchors as $anchor) {
            // Extract ID from href.
            $href = $anchor->getAttribute('href');
            preg_match('/\/(\d+)$/', $href, $matches);
            $id = isset($matches[1]) ? $matches[1] : NULL;

            // Extract title.
            $titleElement = $anchor->getElementsByTagName('h4')->item(0);
            $title = $titleElement ? $titleElement->textContent : NULL;;

            // Extract Type.
            // Get the first <div> element with class 'tags' and 'mr-sm'.
            $typeElement = $anchor->getElementsByTagName('div');
            $type = NULL;

            foreach ($typeElement as $div) {
                $classAttribute = $div->getAttribute('class');

                if (strpos($classAttribute, 'tags') !== FALSE && strpos($classAttribute, 'mr-sm') !== FALSE) {
                    // This is the <div> with class 'tags' and 'mr-sm'.
                    $type = $div->nodeValue;
                    // "Value of <div class=\"tags mr-sm\">: $type";.
                    break;
                }
            }

            // Extract authors and author institutions.
            $authors = $anchor->getElementsByTagName('span');
            $authorNames = [];
            $authorInstitutions = [];

            foreach ($authors as $author) {
                $authorNames[] = $author->textContent;
                $authorInstitutions[] = $author->getAttribute('title');
            }

            // Check if essential properties are valid.
            if ($id !== NULL && $title !== NULL && !empty($authorNames)) {

                // Create Paper instance and add to data array.
                $paperData = new Paper(
                    $id,
                    $title,
                    $type,
                    array_map(
                        function ($author, $institution) {
                            return new Person($author, $institution);
                        }, $authorNames, $authorInstitutions
                    )
                );

                $data[] = $paperData;
            }
        }

        return $data;

    }

    /**
     * @param array  $data
     * @param string $filename
     * 
     * @return [type]
     */
    public function exportToXlsx(array $data, string $filename) {
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filename);

        // Create header row
        $header = ['ID', 'Title', 'Type'];

        // Get the maximum number of authors.
        $maxAuthors = max(
            array_map(
                function ($paper) {
                    return count($paper->getAuthors());
                }, $data
            )
        );

        for ($i = 1; $i <= $maxAuthors; $i++) {
            $header[] = 'Author ' . $i;
            $header[] = 'Author ' . $i . ' Institution';
        }

        $headerRow = WriterEntityFactory::createRowFromArray($header);
        $writer->addRow($headerRow);

        // Write data to the XLSX file.
        foreach ($data as $paper) {
            $authors = $paper->getAuthors();

            $values = [
                $paper->getId(),
                $paper->getTitle(),
                $paper->getType()
            ];

            foreach ($authors as $author) {
                $values[] = $author->getName();
                $values[] = $author->getInstitution();
            }

            // Fill any remaining empty columns for authors and institutions.
            for ($i = count($authors) * 2 + 1; $i <= $maxAuthors * 2; $i++) {
                $values[] = '';
            }

            $rowFromValues = WriterEntityFactory::createRowFromArray($values);
            $writer->addRow($rowFromValues);

        }

        $writer->close();
    }
}