<?php

/**
 * Created by PhpStorm.
 * User: timothyjensen
 * Date: 2/2/17
 * Time: 3:57 PM
 */

namespace ForwardJump\Infusionsoft;

/**
 * Class ExchangeToken
 *
 * @package ForwardJump\Infusionsoft
 */
class ExchangeToken extends Init {

	private function request_access_token( $infusionsoft, $code ) {

		try {
			$infusionsoft->requestAccessToken( $code );

			// Save the serialized token to the current session for subsequent requests
			$infusionsoft_token = serialize( $infusionsoft->getToken() );

			// Store serialized token in the WP options table
			update_option( 'fj_infusionsoft_api_token', $infusionsoft_token );

			update_option( 'fj_infusionsoft_api_errors', array( 'refresh_access_token' => true ) );

			if ( $infusionsoft_token ) {
				add_action( 'admin_notices', 'fj_infusionsoft_token_success_admin_notice' );
			}
		}
		catch ( \GuzzleHttp\Exception\RequestException $e ) {
			update_option( 'fj_infusionsoft_api_errors', array( 'refresh_access_token' => false ) );
		}

	}

	public function __construct( $code ) {
		$infusionsoft = $this->instantiate_infusionsoft_class();

		$this->request_access_token( $infusionsoft, $code );
	}

}