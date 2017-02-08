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

		// Return early if token is not available.
		if ( ! is_object( $infusionsoft_token ) ) {
			new Response_Handler( array( 'valid_access_token' => false ) );

			return;
		}

		$this->setToken( $infusionsoft_token );

		// Refresh the token if it is set to expire within 3 hours.
		if ( 10800 > ( $infusionsoft_token->endOfLife - time() ) ) {

			$this->refresh_access_token();

		}

	}

	/**
	 * Expected to return an unserialized object.
	 *
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
