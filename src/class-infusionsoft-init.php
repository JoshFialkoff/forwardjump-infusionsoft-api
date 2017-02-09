<?php
/**
 * Infusionsoft Init
 *
 * @package     ForwardJump Infusionsoft API
 * @since       1.0.0
 * @author      Tim Jensen
 * @link        https://www.timjensen.us
 * @license     GNU General Public License 2.0+
 */

namespace ForwardJump\InfusionsoftAPI;

/**
 * Infusionsoft Init
 *
 * @package ForwardJump\InfusionsoftAPI
 */
class Infusionsoft_Init extends Infusionsoft {

	/**
	 * Init constructor.
	 */
	public function __construct() {

		parent::__construct();

		$infusionsoft_token = $this->get_access_token();
		$unserialized_token = maybe_unserialize( $infusionsoft_token );

		$this->setToken( $unserialized_token );

		// Refresh the token if it is set to expire within 3 hours.
		$time_to_expire = $unserialized_token->endOfLife - time();
		if ( 10800 > $time_to_expire || ! $this->getToken() ) {

			$this->refresh_access_token();

		}

	}

	/**
	 * Expected to return a serialized object.
	 *
	 * @return mixed|null
	 */
	protected function get_access_token() {
		return get_option( 'fj_infusionsoft_api_token' );
	}

	/**
	 * Updates the access token in the options table
	 * 
	 * @param $token
	 *
	 * @return bool
	 */
	protected function update_access_token( $token ) {
		if ( ! $token ) {
			return false;
		}

		return update_option( 'fj_infusionsoft_api_token', $token );
	}

	/**
	 * Refreshes the Infusionsoft access token
	 */
	protected function refresh_access_token() {
		try {

			$refreshed_token = $this->refreshAccessToken();

			$this->update_access_token( $refreshed_token );

			new Response_Handler( array( 'refresh_access_token' => 'true' ) );

		}
		catch ( \GuzzleHttp\Exception\RequestException $e ) {

			new Response_Handler( array( 'refresh_access_token' => 'false' ) );

		}
	}
}
