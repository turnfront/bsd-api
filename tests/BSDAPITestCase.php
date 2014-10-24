<?php

class BSDAPITestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__ . '/../../bootstrap/start.php';
	}

  public function tearDown(){
    \Mockery::close();
  }

  protected function getResponseXML(){
    $xml = <<<EOF
<api>
  <cons id="4382" modified_dt="1171861200">
    <guid>ygdFPkyEdomzBhWEFZGREys</guid>
    <firstname>Bob</firstname>
    <lastname>Smith</lastname>
    <has_account>1</has_account>
    <is_banned>0</is_banned>
    <create_dt>1168146000</create_dt>

    <cons_email id="8991" modified_dt="1168146011">
        <email>bsmith@somecompany.com</email>
        <email_type>work</email_type>
        <is_subscribed>0</is_subscribed>
        <is_primary>0</is_primary>
    </cons_email>

    <cons_email id="12702" modified_dt="1178510447">
        <email>bob_smith@someisp.com</email>
        <email_type>personal</email_type>
        <is_subscribed>1</is_subscribed>
        <is_primary>1</is_primary>
    </cons_email>

    <cons_field id="176">
        <value>custom value 0</value>
    </cons_field>

    <cons_field id="1211">
        <value>12</value>
    </cons_field>

    <cons_addr>
        <addr1>123 Fake St.</addr1>
        <addr2></addr2>
        <city>Anytown</city>
        <state_cd>CA</state_cd>
        <zip>92345</zip>
        <zip_4>8311</zip_4>
        <country>US</country>
        <is_primary>1</is_primary>
        <latitude>42.000</latitude>
        <longitude>71.000</longitude>
    </cons_addr>

  </cons>
</api>
EOF;
    return $xml;
  }

  protected function getConstituent(){
    $constituent = new Constituent(array(
                                        "firstname"=>"John",
                                        "lastname" => "Test",
                                        "guid" => "6q5zL92ZzHdSDL-C8aFUQiA",
                                        "email" => "jtest@example.com"
                                   ));
    return $constituent;
  }

}
