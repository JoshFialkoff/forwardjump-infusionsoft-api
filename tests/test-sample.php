<?php
/**
 * Class SampleTest
 *
 * @package Forwardjump_Infusionsoft_Api
 */

namespace ForwardJump\InfusionsoftAPI;

use Mockery as m;

/**
 * Sample test case.
 */
class InfusionsoftTest extends \WP_UnitTestCase {

	function test_is_object() {
		$infusionsoft = new \ForwardJump\InfusionsoftAPI\Infusionsoft();

		$token = new \Infusionsoft\Token(array( 'access_token' => '', 'refresh_token' => '', 'expires_in' => 5));

		$infusionsoft->setToken( $token );

		$this->assertInstanceOf( 'ForwardJump\InfusionsoftAPI\Infusionsoft', $infusionsoft );
		$this->assertInstanceOf( 'Infusionsoft\Token', $token );
		$this->assertEquals( $infusionsoft->getToken(), $token );
	}

	function test_set_token() {
		$infusionsoft = new \ForwardJump\InfusionsoftAPI\Infusionsoft();

		$client = m::mock('Infusionsoft\Http\GuzzleHttpClient');
		$client->shouldReceive('request')->once()
		       ->with('POST', 'https://api.infusionsoft.com/token', ['body' => array(
				       'client_id' => 'foo',
				       'client_secret' => 'bar',
				       'code' => 'code',
				       'grant_type' => 'authorization_code',
				       'redirect_uri' => 'baz')]
		       )->andReturn(array('access_token' => 'access_token'));

		$infusionsoft->setClientId('foo');
		$infusionsoft->setClientSecret('bar');
		$infusionsoft->setRedirectUri('baz');
		$infusionsoft->setHttpClient($client);
		$infusionsoft->requestAccessToken('code');
		$this->assertEquals('access_token', $infusionsoft->getToken()->getAccessToken());

	}

//	function test_update_token() {
//		$this->ifs->update_option_access_token( $this->token );
//	}


}
