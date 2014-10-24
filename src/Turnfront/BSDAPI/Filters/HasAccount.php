<?php
/**
 * @file 
 */
namespace Turnfront\BSDAPI\Filters;
use Turnfront\BSDAPI\Contracts\FilterInterface;

class HasAccount extends AbstractBSDFilter implements FilterInterface {

  public function asString(){
    return "has_account";
  }

} 