<?php
/**
 * @file 
 */

namespace Turnfront\BSDAPI\Filters;
use Turnfront\BSDAPI\Contracts\FilterInterface;

abstract class AbstractBSDFilter implements FilterInterface {

  protected $fieldName;

  protected $options = array();

  public function create($options = array()) {
    if (!is_array($options)){
      $options = array($options);
    }
    $this->options = $options;
  }

  public function asString(){
    if (count($this->options) > 1){
      $states = "(" . explode(",", $this->options) . ")";
    }
    else {
      $states = $this->options[0];
    }
    return $this->fieldName . "=" . $states;
  }

} 