<?php
/**
 * Class SampleTest
 *
 * @package Forwardjump_Infusionsoft_Api
 */

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase {

	protected $infusionsoft;

	function test_is_object() {
		$this->infusionsoft = new \ForwardJump\InfusionsoftAPI\Infusionsoft();

		$this->assertTrue( is_object( $this->infusionsoft ) );
	}

	function test_client_id() {
		$client_id = get_option( 'fj_infusionsoft_api_client_id' );
		$client_secret = get_option( 'fj_infusionsoft_api_client_secret' );

		$this->assertTrue( is_string( $client_id ) );
	}


}
