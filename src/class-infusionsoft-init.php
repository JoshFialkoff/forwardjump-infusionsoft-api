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

		$this->setToken( $this->get_option_access_token() );

		if ( ! $this->getToken() || $this->is_token_expiring_soon( $this->getToken() ) ) {

			$this->try_refresh_access_token();

		}

	}

	/**
	 * Expected to return a serialized object.
	 *
	 * @return mixed|null
	 */
	public function get_option_access_token() {
		return maybe_unserialize( get_option( 'fj_infusionsoft_api_token' ) );
	}

	/**
	 *
	 *
	 * @param $unserialized_token
	 *
	 * @return bool
	 */
	public function is_token_expiring_soon( $unserialized_token ) {
		return ( 10800 > ( $unserialized_token->endOfLife - time() ) );
	}

	/**
	 * Updates the access token in the options table
	 * 
	 * @param $refreshed_token
	 *
	 * @return bool
	 */
	public function update_option_access_token( $refreshed_token ) {
		if ( ! $refreshed_token ) {
			return false;
		}

		return update_option( 'fj_infusionsoft_api_token', $refreshed_token );
	}

	/**
	 * Refreshes the Infusionsoft access token
	 */
	protected function try_refresh_access_token() {
		try {

			$refreshed_token = $this->refreshAccessToken();

			$this->update_option_access_token( $refreshed_token );

			new Response_Handler( array( 'refresh_access_token' => 'true' ) );

		}
		catch ( \GuzzleHttp\Exception\RequestException $e ) {

			new Response_Handler( array( 'refresh_access_token' => 'false' ) );

		}
	}
}
