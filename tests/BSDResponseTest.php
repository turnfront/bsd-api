<?php
/**
 * @file 
 */

class BSDResponseTest extends BSDAPITestCase {

  public function testCanSetResult(){
    $response = new BSDResponse();
    // This XML is from the BSD API documentation
    $xml = $this->getResponseXML();
    $response->setResult($xml);
    $this->assertEquals($xml, $response->getResult());
  }

} 