<?php
namespace Turnfront\BSDAPI\Helpers;
use Doctrine\Common\Collections\ArrayCollection;
use Turnfront\BSDAPI\Contracts\ConstituentFieldsInterface;

/**
 * @file
 */

class ConstituentFields implements ConstituentFieldsInterface {

  protected $fields;

  public function __construct(){
    $this->fields = new ArrayCollection();
  }

  public function fillXMLTree($xmlTree, $parent){
    if (!empty($this->fields)) {
      foreach ($this->fields->getIterator() as $field_id => $value) {
        $cons_field = $xmlTree->createElement("cons_field");
        $cons_field = $parent->appendChild($cons_field);
        $cons_field->setAttribute("id", $field_id);
        $cons_field_value = $xmlTree->createElement("value", $value);
        $cons_field->appendChild($cons_field_value);
      }
    }
    return $xmlTree;
  }

  /**
   * Output an XML string.
   *
   * @return string
   */
  public function generateXML() {
    $xmlTree = new \DOMDocument("1.0", "UTF-8");
    $api     = $xmlTree->createElement("api");
    $api     = $xmlTree->appendChild($api);
    $xmlTree = $this->fillXMLTree($xmlTree, $api);
    return $xmlTree->saveXML();
  }

  /**
   * Takes a hash of fields and adds them to the fields representation, should be in the form field_id=>value
   *
   * @param $fields
   *
   * @return $this
   */
  public function addFields($fields) {
    if (!$this->fields->isEmpty()) {
      // We can't use array_merge as we'll probably have numeric keys for the IDs.
      foreach ($fields  as $key => $value){
        $this->fields->set($key, $value);
      }
    } else {
      $this->fields = new ArrayCollection($fields);

    }
  }

  public function __set($key, $value){
    // We can't determine that there already is a value for this custom field. Essentially we need to trust the user here.
    $this->fields->set($key, $value);
  }

  public function __get($key){
    if ($this->fields->containsKey($key)){
      return $this->fields->get($key);
    }
    return null;
  }

}