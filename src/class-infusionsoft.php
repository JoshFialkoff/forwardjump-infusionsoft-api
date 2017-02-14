<?php
/**
 * Infusionsoft
 *
 * @package     ForwardJump Infusionsoft API
 * @since       1.0.0
 * @author      Tim Jensen
 * @link        https://www.timjensen.us
 * @license     GNU General Public License 2.0+
 */

namespace ForwardJump\InfusionsoftAPI;

/**
 * Class Infusionsoft
 *
 * @package ForwardJump\InfusionsoftAPI
 */
class Infusionsoft extends \Infusionsoft\Infusionsoft {

	/**
	 * Constructor.
	 */
	public function __construct() {

		parent::__construct();

		$fj_infusionsoft_api_settings = get_option( 'fj_infusionsoft_api' );

		$this->clientId = $fj_infusionsoft_api_settings['client_id'];

		$this->clientSecret = $fj_infusionsoft_api_settings['client_secret'];

		$this->redirectUri = admin_url();

	}

	/**
	 * Retrieves the access token object.
	 *
	 * @return mixed|null
	 */
	public function get_option_access_token() {
		return maybe_unserialize( get_option( 'fj_infusionsoft_api_token' ) );
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
	 * Checks if the stored token is expiring in less than 3 hrs.
	 *
	 * @return bool
	 */
	public function is_token_expiring() {
		return ( 10800 > ( $this->get_option_access_token()->endOfLife - time() ) );
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