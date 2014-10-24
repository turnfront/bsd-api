<?php

class ConstituentTest extends BSDAPITestCase {
  /**
   * Test that we can create a constituent at all.
   */
  public function testCanCreateConstituent(){
    $constituent = $this->getConstituent();
    $this->assertInstanceOf("ConstituentInterface", $constituent);
    $xml = $constituent->generateXML();
    $xmlObject = simplexml_load_string($xml);
    $cons = $xmlObject->children()->children();
    $firstnameXML = $cons->firstname->asXML();
    $this->assertEquals('<firstname>John</firstname>', $firstnameXML);
    $lastnameXML = $cons->lastname->asXML();
    $this->assertEquals("<lastname>Test</lastname>", $lastnameXML);
    $guidXML = $cons->guid->asXML();
    $this->assertEquals('<guid>6q5zL92ZzHdSDL-C8aFUQiA</guid>', $guidXML);
  }

  /**
   * Tests whether we can add custom fields and whether they have the right format.
   */
  public function testCanAddCustomFieldsToConstituent(){
    $constituent = $this->getConstituent();
    $constituent->setCustomField(1211, "test");
    $xml = $constituent->generateXML();
    $xmlObject = simplexml_load_string($xml);
    $consField = $xmlObject->children()->children()->cons_field;
    $this->assertNotEquals(empty($consField), true);
    $consFieldXML = $consField->asXML();
    $expectation = '<cons_field id="1211"><value>test</value></cons_field>';
    $this->assertEquals($expectation, $consFieldXML);
  }

  public function testCanGetCustomField(){
    $constituent = $this->getConstituent();
    $constituent->setCustomField(1211, "test");
    $this->assertEquals("test", $constituent->getCustomField(1211));
  }

  /**
   * Tests whether we can add a group to a constituent.
   */
  public function testAddGroupToConstituent(){
    $constituent = $this->getConstituent();
    $constituent->setGroup(1212);
    $xml = $constituent->generateXML();
    $xmlObject = simplexml_load_string($xml);
    $group = $xmlObject->children()->children()->cons_group;
    $this->assertNotEquals(empty($group), true);
    $groupXML = $group->asXML();
    $this->assertEquals('<cons_group id="1212"/>', $groupXML);
  }

}