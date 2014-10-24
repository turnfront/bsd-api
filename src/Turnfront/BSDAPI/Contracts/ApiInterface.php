<?php

namespace Turnfront\BSDAPI\Contracts;
use Turnfront\BSDAPI\Helpers\FilterManager;

/**
 * @file 
 */

interface ApiInterface {
  /**
   * Process submission of a particular signup.
   *
   * @param int $signupID
   * @param $values
   *
   * @return ApiResponseInterface
   */
  public function processSignup($signupID, $values);

  /**
   * Create a constituent (user) in the remote system.
   *
   * @param ConstituentInterface $cons
   *
   * @return mixed
   */
  public function createConstituent(ConstituentInterface $cons);

  /**
   * Get a particular constituent group from the API.
   *
   * @param $id
   *
   * @return mixed
   */
  public function getConstituentGroup($id);

  /**
   * Add a given constituent to a given group.
   *
   * @param ConstituentInterface $constituent
   * @param                      $groupID
   *
   * @return mixed
   */
  public function addConstituentToGroup(ConstituentInterface $constituent, $groupID);

  /**
   * Takes one or more GUIDs (non-sequential, random, unique identifiers for constituents) as a parameter and returns the matching constituent records.
   *
   * @param string $guid
   *
   * @return mixed
   */
  public function getConstituentsByGuid($guid);

  /**
   * Test that a given deferred result is ready for collection.
   *
   * @param $deferred
   *
   * @return mixed
   */
  public function getDeferredResult($deferred);

  /**
   * Set the url to poll for deferred results.
   *
   * @param $url
   *
   * @return mixed
   */
  public function setDeferredURL($url);

  /**
   *
   *
   * @param $path
   *
   * @return mixed
   */
  public function setAPIPath($path);

  /**
   * @param FilterManager $filter
   *
   * @return mixed
   */
  public function getConstituents(FilterManager $filter);

} 