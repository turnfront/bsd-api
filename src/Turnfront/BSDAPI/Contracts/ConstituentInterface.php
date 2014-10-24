<?php
/**
 * @file 
 */
namespace Turnfront\BSDAPI\Contracts;

interface ConstituentInterface {

  public function setGroup($id);

  public function setCustomField($key, $value);

  public function setEmail($email);

  public function generateXML();

  public function getCustomField($key);

} 