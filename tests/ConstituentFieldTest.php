<?php

class ConstituentFieldTest extends BSDAPITestCase {

  /**
   * Tests whether we can add custom fields and whether they have the right format.
   */
  public function testAddCustomFields(){
    $constituentFields = new ConstituentFields();
    $constituentFields->addFields(array(1211=>"test"));
    $xml = $constituentFields->generateXML();
    $xmlObject = simplexml_load_string($xml);
    $consField = $xmlObject->children()->cons_field;
    $this->assertNotEquals(empty($consField), true);
    $consFieldXML = $consField->asXML();
    $expectation = '<cons_field id="1211"><value>test</value></cons_field>';
    $this->assertEquals($expectation, $consFieldXML);
  }

}