<?php
/**
 * Infusionsoft init
 *
 * @package     ForwardJump Infusionsoft API
 * @since       1.0.0
 * @author      Tim Jensen
 * @link        https://www.timjensen.us
 * @license     GNU General Public License 2.0+
 */

namespace ForwardJump\Infusionsoft;

/**
 * Class Init
 *
 * @package ForwardJump\Infusionsoft
 */
class Init extends \Infusionsoft\Infusionsoft {

	/**
	 * Init constructor.
	 */
	public function __construct() {

		parent::__construct();

		// Properties of the parent Infusionsoft Class
		$this->clientId     = esc_html( self::get_client_id() );
		$this->clientSecret = esc_html( self::get_client_secret() );
		$this->redirectUri  = esc_url( admin_url() );

		$infusionsoft_token = $this->get_access_token();

		// Return early if token is not available.
		if ( ! $infusionsoft_token || ! unserialize( $infusionsoft_token ) ) {
			new Response_Handler( array( 'valid_access_token' => false ) );

			return;
		}

		$this->setToken( unserialize( $infusionsoft_token ) );

		// Refresh the token if it is set to expire within 3 hours.
		if ( 10800 > unserialize( $infusionsoft_token )->endOfLife - time() ) {

			$this->refresh_access_token();

		}

	}

	/**
	 * @return string  Client ID
	 */
	public static function get_client_id() {
		return get_option( 'fj_infusionsoft_api_client_id' );
	}

	/**
	 * @return string  Client Secret
	 */
	public static function get_client_secret() {
		return get_option( 'fj_infusionsoft_api_client_secret' );
	}

	/**
	 * @return mixed|null
	 */
	protected function get_access_token() {
		return get_option( 'fj_infusionsoft_api_token' );
	}

	/**
	 * Refreshes the Infusionsoft access token
	 */
	protected function refresh_access_token() {
		try {

			$this->refreshAccessToken();

			new Response_Handler( array( 'refresh_access_token' => 'true' ) );

		}
		catch ( \GuzzleHttp\Exception\RequestException $e ) {

			new Response_Handler( array( 'refresh_access_token' => 'false' ) );

		}
	}
}
