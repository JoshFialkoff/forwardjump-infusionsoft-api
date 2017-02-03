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

class Init extends \Infusionsoft\Infusionsoft {

	/**
	 * @return mixed|void
	 */
	public static function get_access_token() {
		return get_option( 'fj_infusionsoft_api_token' );
	}

	/**
	 *
	 */
	public static function get_client_id() {
		return get_option( 'fj_infusionsoft_api_client_id' );
	}

	/**
	 *
	 */
	public static function get_client_secret() {
		return get_option( 'fj_infusionsoft_api_client_secret' );
	}

	/**
	 * @param $infusionsoft
	 * @param $infusionsoft_token
	 */
	public function refresh_token( $infusionsoft, $infusionsoft_token ) {

		// Refresh the token if it is set to expire in less than 3 hrs
		if ( 10800 < unserialize( $infusionsoft_token )->endOfLife - time() ) {
			return;
		}

		try {
			$infusionsoft->refreshAccessToken();

			update_option( 'fj_infusionsoft_api_errors', array( 'refresh_access_token' => true ) );
		}
		catch ( \GuzzleHttp\Exception\RequestException $e ) {
			update_option( 'fj_infusionsoft_api_errors', array( 'refresh_access_token' => false ) );
		}

	}

	/**
	 * Init constructor.
	 */
	public function __construct() {
		$infusionsoft_token = $this->get_access_token();

		if ( ! $infusionsoft_token ) {
			return false;
		}

		$infusionsoft = new \Infusionsoft\Infusionsoft( array(
			'clientId'     => $this->get_client_id(),
			'clientSecret' => $this->get_client_secret(),
			'redirectUri'  => admin_url(),
		) );

		$infusionsoft->setToken( unserialize( $infusionsoft_token ) );

		$this->refresh_token( $infusionsoft, $infusionsoft_token );

		// Save the token for future requests
		$infusionsoft_token = serialize( $infusionsoft->getToken() );

		// Store the serialized token in the WP options table
		update_option( 'fj_infusionsoft_api_token', $infusionsoft_token );

		return $infusionsoft;
	}

}