<?php
/**
 * @file
 */

/**
 * Class BSDApiTest
 *
 * The class that we are testing doesn't actually produce much itself so the unit tests of this are not particularly rigorous.
 * We also are only testing the functionality that will be used in this project so there will be large bits of the code uncovered.
 */
class BSDApiTest extends BSDAPITestCase {

  public function testCanGetConstituentByGuid() {
    $api  = $this->makeApi();
    $curl = $this->prepareForRequest();
    $curl->shouldReceive("makePost")->between(0, 1)->andReturn($curl);
    $curl->shouldReceive("send")->once()->andReturn(new BSDResponse());
    // This isn't a valid GUID but we don't really require that of the API class, the actual API will handle validation
    $api->getConstituentsByGuid(1012);
  }

  public function testCanSetCustomConstituentFields(){
    $curl = $this->prepareForRequest();
    $curl->shouldReceive("makePost")->once()->andReturn($curl);
    $curl->shouldReceive("setPostBody")->once()->andReturn($curl);
    $response = Mockery::mock("BSDResponse");
    $response->http_code = 200;
    $curl->shouldReceive("send")->once()->andReturn($response);
    $api = $this->makeApi();
    $result = $api->setCustomConstituentFields(1012, new ConstituentFields());
    $this->assertEquals(true, $result);
  }

  protected function makeApi() {
    return new BSDApi("http://example.com/api", "", "");
  }

  protected function prepareForRequest(){
    $curl = Mockery::mock('\Turnfront\Curlrequest\Engine\CurlRequest');
    // We can't really test the making of the key at present as we don't have access to the timestamp that will be used
    \Turnfront\Curlrequest\Facades\CurlRequest::shouldReceive("setUrl")->once()->andReturn($curl);
    $curl->shouldReceive("setHandler")->zeroOrMoreTimes()->andReturn($curl);
    $curl->shouldReceive("setOpt")->zeroOrMoreTimes()->andReturn($curl);
    return $curl;
  }

  protected function getConstituent(){
    $constituent = new Constituent(array(
                                        "firstname"=>"John",
                                        "lastname" => "Test",
                                        "guid" => "6q5zL92ZzHdSDL-C8aFUQiA",
                                        "email" => "jtest@example.com",
                                        "id" => 2000
                                   ));
    return $constituent;
  }

} 