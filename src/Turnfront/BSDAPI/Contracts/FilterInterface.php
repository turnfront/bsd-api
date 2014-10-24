<?php
/**
 * @file 
 */
namespace Turnfront\BSDAPI\Contracts;

interface FilterInterface {

  public function create($options = array());

  public function asString();

} 