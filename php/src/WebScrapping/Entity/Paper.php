<?php

namespace Chuva\Php\WebScrapping\Entity;

/**
 * The Paper class represents the row of the parsed data.
 */
class Paper {

  /**
   * Paper Id.
   *
   * @var int
   */
  public $id;
  /**
   * Paper Title.
   *
   * @var string
   */
  public $title;
  /**
   * The paper type (e.g. Poster, Nobel Prize, etc).
   *
   * @var string
   */
  public $type;
  /**
   * Paper authors.
   *
   * @var \Chuva\Php\WebScrapping\Entity\Person[]
   */
  public $authors;

  /**
   * Constructs a new MyCustomClass object.
   *
   * @param mixed $id
   *   The unique identifier for the object.
   * @param mixed $title
   *   The title of the object.
   * @param mixed $type
   *   The type of the object.
   * @param array $authors
   *   An array of authors associated with the object (optional).
   */
  public function __construct($id, $title, $type, array $authors = []) {
    $this->id = $id;
    $this->title = $title;
    $this->type = $type;
    $this->authors = $authors;
  }

  /**
   * Get paper Id.
   *
   * @return int
   *   The unique identifier of the paper.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Get paper Title.
   *
   * @return string
   *   The type of the paper.
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * Get the paper type (e.g. Poster, Nobel Prize, etc).
   *
   * @return string
   *   The type of the paper.
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Get paper authors.
   *
   * @return \Chuva\Php\WebScrapping\Entity\Person[]
   *   An array of Person objects representing the authors of the paper.
   */
  public function getAuthors() {
    return $this->authors;
  }

}
