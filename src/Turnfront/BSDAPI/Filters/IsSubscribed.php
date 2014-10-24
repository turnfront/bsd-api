<?php
/**
 * @file 
 */
namespace Turnfront\BSDAPI\Filters;
use Turnfront\BSDAPI\Contracts\FilterInterface;

class IsSubscribed extends AbstractBSDFilter implements FilterInterface {

  public function asString(){
    return "is_subscribed";
  }

} 