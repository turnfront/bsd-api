<?php
/**
 * @file 
 */
namespace Turnfront\BSDAPI\Contracts;

use Turnfront\CurlRequest\Contracts\ResponseInterface;

interface ApiResponseInterface extends ResponseInterface {
  /**
   * Returns an ArrayCollection of all the constituents that were returned.
   *
   * @return \Doctrine\Common\Collections\ArrayCollection
   */
  public function getConstituents();

  /**
   * @return mixed
   */
  public function getErrors();

  /**
   * Returns a keyed array of all of the objects from the API.
   *
   * @return mixed
   */
  public function getObjects();

} 