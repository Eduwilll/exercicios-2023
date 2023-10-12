<?php

namespace Chuva\Php\WebScrapping\Entity;

/**
 * Paper Author personal information.
 */
class Person{
    /**
     * Person name.
     */
    public string $name;
    /**
     * Person institution.
     */
    public string $institution;
    /**
     * Builder.
     */
    public function __construct($name, $institution){
        $this->name = $name;
        $this->institution = $institution;
    }
    /**
     * Get person name.
     */ 
    public function getName(){
        return $this->name;
    }
    /**
     * Get person institution.
     */ 
    public function getInstitution(){
        return $this->institution;
    }
}
