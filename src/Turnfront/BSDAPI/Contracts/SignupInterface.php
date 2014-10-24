<?php
/**
 * @file 
 */
namespace Turnfront\BSDAPI\Contracts;

/**
 * Class Signup
 *
 * The Signup class contains a signup response
 *
 * @package BSD
 *
 */
interface SignupInterface {

  public function __construct($formID);

  public function setFields($fields);

  public function generateXML();

} 