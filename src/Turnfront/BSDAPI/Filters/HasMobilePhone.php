<?php
/**
 * @file 
 */
namespace Turnfront\BSDAPI\Filters;
use Turnfront\BSDAPI\Contracts\FilterInterface;

class HasMobilePhone extends AbstractBSDFilter implements FilterInterface {

  public function asString(){
    return "has_mobile_phone";
  }

} 