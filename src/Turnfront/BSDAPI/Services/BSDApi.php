<?php
namespace Turnfront\BSDAPI\Services;

use Turnfront\BSDAPI\Contracts\ApiInterface;
use Turnfront\BSDAPI\Contracts\ConstituentInterface;
use Turnfront\BSDAPI\Contracts\ConstituentFieldsInterface;
use Turnfront\BSDAPI\Helpers\BSDResponse;
use Turnfront\BSDAPI\Helpers\FilterManager;
use Turnfront\BSDAPI\Helpers\Signup;
use Turnfront\CurlRequest\Facades\CurlRequest;

/**
 * @file
 */

class BSDApi implements ApiInterface {

  protected $apiKey = "738f7637f743e65e7a603cf3b8fa269856970bac";
  protected $apiID = "turnfront";
  protected $host = "https://secure.38degrees.org.uk";
  protected $apiPath = 'page/api';
  protected $deferred = array();

  public function __construct() {
    //$this->host   = \Config::get("bsdapi::bsd.host");
    //$this->apiKey = \Config::get("bsdapi::bsd.apiKey");
    //$this->apiID  = \Config::get("bsdapi::bsd.apiID");
  }

  /**
   * Handles preparing a request for sending to the BSD API.
   *
   * @param        $path
   * @param array  $data
   * @param string $method
   *
   * @return BSDResponse
   */
  protected function makeRequest($path, $data = array(), $method = "GET") {
    $data['api_id']  = $this->apiID;
    $now             = time();
    $data['api_ts']  = $now;
    $data['api_ver'] = "1";
    if ($method === "POST") {
      $body = $data['postBody'];
      unset($data['postBody']);
    }
    if (!empty($this->deferredURL)){
      $data['deferred_callback'] = $this->deferredURL;
    }
    ksort($data);
    $queryString     = array_reduce(
      array_map(
        function ($value, $key) {
          return urlencode($key) . "=" . urlencode($value);
        }, $data, array_keys($data)),
      function ($left, $value) {
        if (!empty($left)) {
          $left .= "&";
        }
        return $left .= $value;
      }, "");
    $signingString   = $this->apiID . "\n" . $now . "\n" . "/" . $this->apiPath . "/" . $path . "\n" . urldecode($queryString);
    $data['api_mac'] = hash_hmac("sha1", $signingString, $this->apiKey);
    $url             = $this->host . "/" . $this->apiPath . '/' . $path . '?' . $queryString . "&api_mac=" . $data['api_mac'];
    /** @var \Turnfront\CurlRequest\Contracts\CurlRequestInterface $curlHandler */
    $curlHandler = CurlRequest::setUrl($url);
    if (isset($body)) {
      $curlHandler->makePost();
      $curlHandler->setPostBody($body);
    }
    $curlHandler->setHandler(new BSDResponse());
    $response = $curlHandler->setOpt(CURLOPT_RETURNTRANSFER, TRUE)->send();
    return $response;
  }

  /**
   * Process submission of a specified signup.
   *
   * @param int $signupID
   * @param     $values
   *
   * @return BSDResponse
   */
  public function processSignup($signupID, $values) {
    $signup = new Signup($signupID, $values);
    $xml    = $signup->generateXML();
    $result = $this->makeRequest("signup/process_signup", array("postBody" => $xml), "POST");
    return $result;
  }

  /**
   * Creates a new constituent in the BSD system.
   *
   * @param ConstituentInterface $cons
   *
   * @return ConstituentInterface|bool Either false or the Constituent that was created
   */
  public function createConstituent(ConstituentInterface $cons) {
    $result = $this->makeRequest("cons/set_constituent_data", array("postBody" => $cons->generateXML()), "POST");
    if ($result->http_code === 200) {
      $objects = $result->getObjects();
      if (!empty($objects) && isset($objects['cons'])) {
        $constituent = $objects['cons'][0];
        foreach ($constituent->attributes() as $key => $value) {
          $cons->$key = $value->__toString();
        }
        return $cons;
      }
    }
    return FALSE;
  }

  /**
   * Pass through to createConstituent method.
   *
   * @param ConstituentInterface $cons
   *
   * @return bool|ConstituentInterface
   */
  public function setConstituentData(ConstituentInterface $cons) {
    return $this->createConstituent($cons);
  }

  /**
   * Get a specific constituent group.
   *
   * @param $id
   *
   * @return BSDResponse
   */
  public function getConstituentGroup($id) {
    $result = $this->makeRequest("cons_group/get_constituent_group", array("cons_group_id" => $id));
    return $result;
  }

  /**
   * Adds the provided constituent to the group with the named ID.
   *
   * @param $constituent
   * @param $group_id
   *
   * @return bool
   */
  public function addConstituentToGroup(ConstituentInterface $constituent, $group_id) {
    $data = array("cons_ids" => $constituent->id, "cons_group_id" => $group_id);
    if (!empty($this->deferredURL)) {
      $data['deferred_callback'] = $this->deferredURL;
    }
    $result                     = $this->makeRequest("cons_group/add_cons_ids_to_group", $data);
    $rawResult                  = $result->getResult();
    $this->deferred[$rawResult] = FALSE;
    return $result->http_code == 202 ? $rawResult : FALSE;
  }

  public function setCustomConstituentFields($consID, ConstituentFieldsInterface $fields) {
    $data = array();
    $data['postBody'] = $fields->generateXML();
    $data['delete_missing'] = 0;
    $data['cons_id'] = $consID;
    $result = $this->makeRequest("cons/set_custom_constituent_fields", $data, "POST");
    return $result;
  }

  /**
   * Fetch a deferred result from the API.
   *
   * @param $deferred
   *
   * @return BSDResponse|bool
   */
  public function getDeferredResult($deferred) {
    $result = $this->makeRequest("get_deferred_results", array("deferred_id" => $deferred));
    if ($result->http_code != 503 && $result->http_code !== 410 && $result->http_code !== 204) {
      return $result;
    }
    return FALSE;
  }

  /**
   * Set the URL that the BSD API should call if a deferred result is required.
   *
   * @param $url
   *
   * @return mixed|void
   */
  public function setDeferredURL($url) {
    $this->deferredURL = $url;
  }

  /**
   * Set the api path to be a custom one.
   *
   * @param $path
   *
   * @return mixed|void
   */
  public function setAPIPath($path) {
    $this->apiPath = $path;
  }

  /**
   * Takes one or more GUIDs (non-sequential, random, unique identifiers for constituents) as a parameter and returns the matching constituent records.
   *
   * @param string|array $guid
   *
   * @return mixed
   */
  public function getConstituentsByGuid($guid) {
    if (is_array($guid)) {
      $guid = explode(",", $guid);
    }
    $response = $this->makeRequest("cons/get_constituents_by_guid", array("guids" => $guid, "bundles" => "cons_field,cons_email,cons_addr"));
    return $response;
  }

  public function getConstituents(FilterManager $filters){
    $response = $this->makeRequest("cons/get_constituents", array("filter"=>$filters->asString(), "bundles"=>"cons_field,cons_email,cons_addr"));
    return $response;
  }

}