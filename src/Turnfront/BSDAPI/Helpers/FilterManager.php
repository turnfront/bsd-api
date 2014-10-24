<?php
namespace Turnfront\BSDAPI\Helpers;
use Doctrine\Common\Collections\ArrayCollection;
use Turnfront\BSDAPI\Contracts\FilterInterface;

/**
 * @file 
 */

class FilterManager {

  public function __construct(){
    $this->filters = new ArrayCollection();
  }

  public function add(FilterInterface $filter){
    $this->filters->add($filter);
  }

  public function asString(){
    $output = implode(",", $this->filters->map(function ($filter){
      return $filter->asString();
    })->toArray());
    return $output;
  }

} 