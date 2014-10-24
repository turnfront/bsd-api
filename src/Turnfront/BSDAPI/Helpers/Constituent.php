<?php
namespace Turnfront\BSDAPI\Helpers;
use Turnfront\BSDAPI\Contracts\ConstituentInterface;

/**
 * @file 
 */

class Constituent implements ConstituentInterface {

  protected $banned = FALSE;
  protected $firstname;
  protected $lastname;
  protected $email = array();
  protected $id;
  protected $group;
  protected $guid;
  protected $customFields;
  protected $allowedParams = array("firstname", "middlename", "lastname", "id", "email", "guid");

  public function __construct($consParams) {
    $this->customFields = new ConstituentFields();
    if (!empty($consParams)) {
      foreach ($consParams as $key => $param) {
        if (in_array($key, $this->allowedParams)) {
          $method = "set" . ucfirst($key);
          if (is_callable(array($this, $method))) {
            $this->$method($param);
          } else {
            $this->$key = $param;
          }
        }
      }
    }
  }

  public function setBanned($status){
    $this->banned = (bool) $status;
    return $this;
  }

  /**
   * @param $id
   *
   * @return $this
   */
  public function setGroup($id) {
    $this->group = $id;
    return $this;
  }

  /**
   * @param $key
   * @param $value
   *
   * @return $this
   */
  public function setCustomField($key, $value) {
    $this->customFields->addFields(array((int) $key=>$value));
    return $this;
  }

  public function getCustomField($key){
    $value = $this->customFields->$key;
    return $value;
  }

  public function setEmail($email, $id = null) {
    if (!is_array($email)) {
      if (!is_string($email)) {
        return;
      }
      $email = array(
        "address"       => $email,
        "type"          => "home",
        "is_subscribed" => 1,
        "is_primary"    => 1,
      );
    }
    $email['id'] = $id;
    if (!is_array($this->email)){
      $this->email = array();
    }
    $this->email[] = $email;
    return $this;
  }

  public function generateXML() {
    $xmlTree = new \DOMDocument("1.0", "UTF-8");
    $api     = $xmlTree->createElement("api");
    $api     = $xmlTree->appendChild($api);
    $cons    = $xmlTree->createElement("cons");
    $cons    = $api->appendChild($cons);
    if (!empty($this->id)){
      $cons->setAttribute("id", $this->id);
    }
    $firstname = $xmlTree->createElement("firstname", $this->firstname);
    $cons->appendChild($firstname);
    $lastname = $xmlTree->createElement("lastname", $this->lastname);
    $cons->appendChild($lastname);
    $is_banned = $xmlTree->createElement("is_banned", $this->banned);
    $cons->appendChild($is_banned);
    if (isset($this->guid)){
      $guid = $xmlTree->createElement("guid", $this->guid);
      $cons->appendChild($guid);
    }
    if (!empty($this->email)) {
      foreach ($this->email as $emailArray){
        $email        = $xmlTree->createElement("cons_email");
        if (!empty($emailArray['id'])){
          $email->setAttribute("id", $emailArray['id']);
        }
        $e_address    = $xmlTree->createElement("email", $emailArray['address']);
        $e_type       = $xmlTree->createElement("email_type", $emailArray['type']);
        $e_subscribed = $xmlTree->createElement("is_subscribed", $emailArray['is_subscribed']);
        $e_primary    = $xmlTree->createElement("is_primary", $emailArray['is_primary']);
        $email->appendChild($e_address);
        $email->appendChild($e_type);
        $email->appendChild($e_subscribed);
        $email->appendChild($e_primary);
        $cons->appendChild($email);
      }

    }
    if (!empty($this->group)) {
      $cons_group = $xmlTree->createElement("cons_group");
      $cons_group = $cons->appendChild($cons_group);
      $cons_group->setAttribute("id", $this->group);
    }
    $xmlTree = $this->customFields->fillXMLTree($xmlTree, $cons);
    return $xmlTree->saveXML();
  }

  public function __set($key, $value) {
    if (in_array($key, $this->allowedParams)) {
      $this->$key = $value;
    }
    $this->customFields->$key = $value;
    return $this;
  }

  public function __get($field) {
    if (in_array($field, $this->allowedParams)) {
      return $this->$field;
    }
    $customField = $this->customFields->$field;
    if (!empty($customField)){
      return $customField;
    }
    return FALSE;
  }

} 