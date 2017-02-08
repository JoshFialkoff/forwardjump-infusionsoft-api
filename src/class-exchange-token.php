<?php
/**
 * Exchange code for access token
 *
 * @package     ForwardJump Infusionsoft API
 * @since       1.0.0
 * @author      Tim Jensen
 * @link        https://www.timjensen.us
 * @license     GNU General Public License 2.0+
 */

namespace ForwardJump\InfusionsoftAPI;

/**
 * Class Exchange_Token
 *
 * @package ForwardJump\InfusionsoftAPI
 */
class Exchange_Token extends Infusionsoft {

	/**
	 * Init constructor.
	 */
	public function __construct() {

		if ( ! isset( $_GET['code'] ) || ! preg_match( '/infusionsoft/i', $_GET['scope'] ) ) {
			return;
		}

		parent::__construct();

		$this->request_access_token( $_GET['code'] );

	}

	/**
	 * If we are returning from Infusionsoft we need to exchange the code for an access token.
	 *
	 * @see https://developer.infusionsoft.com/docs/xml-rpc/#authentication-request-an-access-token
	 */
	protected function request_access_token( $code ) {
		try {
			$token = $this->requestAccessToken( $code );

			// Store serialized token in the WP options table
			update_option( 'fj_infusionsoft_api_token', $token );

			new Response_Handler( array( 'request_access_token' => 'true' ) );

			add_action( 'admin_notices', array( $this, 'print_success_notice' ) );
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
