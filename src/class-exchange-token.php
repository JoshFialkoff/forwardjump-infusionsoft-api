<?php
/**
 * Created by PhpStorm.
 * User: timothyjensen
 * Date: 2/2/17
 * Time: 9:38 PM
 */

namespace ForwardJump\Infusionsoft;

/**
 * Class Exchange_Token
 *
 * @package ForwardJump\Infusionsoft
 */
class Exchange_Token extends \Infusionsoft\Infusionsoft {

	/**
	 * Init constructor.
	 */
	public function __construct() {

		if ( ! isset( $_GET['code'] ) || ! preg_match( '/infusionsoft/i', $_GET['scope'] ) ) {
			return;
		}

		parent::__construct();

		$this->clientId     = esc_html( Init::get_client_id() );
		$this->clientSecret = esc_html( Init::get_client_secret() );
		$this->redirectUri  = esc_url( admin_url() );

		$this->request_access_token( $_GET['code'] );

	}

	/**
	 * If we are returning from Infusionsoft we need to exchange the code for an access token.
	 *
	 * @see https://developer.infusionsoft.com/docs/xml-rpc/#authentication-request-an-access-token
	 */
	protected function request_access_token( $code ) {
		try {
			$this->requestAccessToken( $code );

			// Save the serialized token to the current session for subsequent requests
			$infusionsoft_token = serialize( $this->getToken() );

			// Store serialized token in the WP options table
			update_option( 'fj_infusionsoft_api_token', $infusionsoft_token );

			new Response_Handler( array( 'refresh_access_token' => 'true' ) );

			if ( $infusionsoft_token ) {
				add_action( 'admin_notices', array( $this, 'print_success_notice' ) );
			}
		}
		catch ( \GuzzleHttp\Exception\RequestException $e ) {

			new Response_Handler( array( 'request_access_token' => 'false' ) );

		}
	}

	/**
	 * Prints success message when OAuth is successful
	 */
	public function print_success_notice() {
		$notice = 'Your Infusionsoft Token has been successfully added!';
		include FJ_INFUSIONSOFT_API_DIR . '/views/admin-notices-success.php';
	}
}

add_action( 'admin_init', function () {
	new Exchange_Token();
} );
