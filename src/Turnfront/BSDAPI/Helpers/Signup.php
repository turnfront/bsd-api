<?php
namespace Turnfront\BSDAPI\Helpers;
use Turnfront\BSDAPI\Contracts\SignupInterface;

/**
 * @file 
 */

class Signup implements SignupInterface {

  protected $fields = array();

  protected $formID = NULL;

  public function __construct($formID, $fields = array()) {
    $this->formID = $formID;
    $this->setFields($fields);
  }

  public function setFields($fields) {
    $this->fields = $fields;
  }

  public function generateXML() {
    $xmlTree    = new \DOMDocument("1.0", "UTF-8");
    $api        = $xmlTree->createElement("api");
    $api        = $xmlTree->appendChild($api);
    $signupForm = $xmlTree->createElement("signup_form");
    $signupForm = $api->appendChild($signupForm);
    $signupForm->setAttribute("id", $this->formID);
    if (!empty($this->fields)) {
      foreach ($this->fields as $id => $fieldValue) {
        if (is_array($fieldValue) && isset($fieldValue['type']) && $fieldValue['type'] === "file"){
          $fieldElement = $xmlTree->createElement("signup_form_field");
          $fileElement = $xmlTree->createElement("file");
          $fileElement = $fieldElement->appendChild($fileElement);
          $filenameElement = $xmlTree->createElement("filename", $fieldValue['filename']);
          $filenameElement = $fileElement->appendChild($filenameElement);
          $dataElement = $xmlTree->createElement("data", $fieldValue['data']);
          $dataElement = $fileElement->appendChild($dataElement);
        }
        else {
          $fieldElement = $xmlTree->createElement("signup_form_field", $fieldValue);
        }
        $fieldElement->setAttribute("id", $id);
        $signupForm->appendChild($fieldElement);
      }
    }
    return $xmlTree->saveXML();
  }

} 