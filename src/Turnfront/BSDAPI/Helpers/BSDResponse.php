<?php
namespace Turnfront\BSDAPI\Helpers;
use Doctrine\Common\Collections\ArrayCollection;
use Turnfront\BSDAPI\Contracts\ApiResponseInterface;

/**
 * @file
 */

class BSDResponse implements ApiResponseInterface {

  protected $responseInformation;
  protected $result;
  protected $objects;

  /**
   * The objects array can contain objects that have been returned from the BSD system (e.g. a Constituent or Constituent Group)
   *
   * @return mixed
   */
  public function getObjects() {
    return $this->objects;
  }

  /**
   * Reads a SimpleXML object and extracts the information required to create a constituent object.
   *
   * @param $xmlObject
   */
  protected function addConstituent($xmlObject){
    $params = array();
    $params["guid"] = (string) $xmlObject->guid;
    $params["firstname"] = (string) $xmlObject->firstname;
    $params["middlename"] = (string) $xmlObject->middlename;
    $params["lastname"] = (string) $xmlObject->lastname;
    foreach ($xmlObject->attributes() as $key => $attribute){
      if ($key === "id"){
        $params['id'] = (string) $attribute;
        break;
      }
    }
    $cons = new Constituent($params);
    if (!empty($xmlObject->cons_field)){
      /** @var SimpleXMLElement $cons_field */
      foreach ($xmlObject->cons_field as $cons_field){
        foreach ($cons_field->attributes() as $key=>$attribute){
          if ($key === "id"){
            $cons->setCustomField((string) $attribute, (string) $cons_field->value);
            break;
          }
        }
      }
    }
    if (!empty($xmlObject->cons_email)){
      foreach ($xmlObject->cons_email as $email){
        $emailArray = array();
        if (!empty($email->email)){
          $emailArray['address'] = (string) $email->email;
        }
        if (!empty($email->email_type)){
          $emailArray['type'] = (string) $email->email_type;
        }
        if (!empty($email->is_subscribed)){
          $emailArray['is_subscribed'] = (string) $email->is_subscribed;
        }
        if (!empty($email->is_primary)){
          $emailArray['is_primary'] = (string) $email->is_primary;
        }
        $id = null;
        foreach ($email->attributes() as $key=>$attribute){
          if ($key === "id"){
            $id = (string) $attribute;
          }
        }
        $cons->setEmail($emailArray, $id);
      }
    }
    if (!isset($this->objects['cons']) || !is_array($this->objects['cons'])){
      $this->objects['cons'] = new ArrayCollection();
    }
    $this->objects['cons']->add($cons);
  }

  /**
   * Returns the raw string of the result.
   *
   * @return mixed
   */
  public function getResult() {
    return $this->result;
  }

  public function __get($name) {
    if (isset($this->responseInformation[$name])) {
      return $this->responseInformation[$name];
    }
    return null;
  }

  public function setResponseInfo($responseInfo) {
    $this->responseInformation = $responseInfo;
    return $this;
  }

  public function setResult($result) {
    libxml_use_internal_errors(TRUE);
    $resultXML = simplexml_load_string($result);
    if ($resultXML) {
      // If we have XML then we need to parse it and extract any data we can.
      if (!empty($resultXML->cons)) {
        $this->addConstituent($resultXML->cons);
      }
      if (!empty($resultXML->error)) {
        $this->objects['error'] = $resultXML->error;
      }
    }
    $this->result = $result;
    return $this;
  }

  public function getConstituents() {
   return isset($this->objects['cons']) ? $this->objects['cons'] : new ArrayCollection();
  }

  /**
   * Return the status code of the request.
   *
   * @return int
   */
  public function getStatusCode() {
    return $this->responseInformation["http_code"];
  }

  /**
   * @return mixed
   */
  public function getErrors() {
    if ($this->responseInformation['http_code'] === 403){
      $this->objects['error'] = $this->result;
    }
    return $this->objects['error'];
  }
}