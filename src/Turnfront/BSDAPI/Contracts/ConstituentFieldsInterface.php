<?php
/**
 * @file 
 */
namespace Turnfront\BSDAPI\Contracts;

interface ConstituentFieldsInterface {
  /**
   * Generate a DOM Tree for the XML, pass this a tree and parent node and new elements will be added to the parent node.
   *
   * @param $document
   * @param $parent
   *
   * @return mixed
   */
  public function fillXMLTree($document, $parent);

  public function generateXML();

  /**
   * Takes a hash of fields and adds them to the fields representation, should be in the form field_id=>value
   *
   * @param $fields
   *
   * @return $this
   */
  public function addFields($fields);

} 