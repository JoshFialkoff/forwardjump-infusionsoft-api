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

		parent::__construct( array(
			'clientId'     => $this->get_client_id(),
			'clientSecret' => $this->get_client_secret(),
			'redirectUri'  => admin_url()
		) );

	}

	/**
	 * @return string  Client ID
	 */
	protected function get_client_id() {
		return get_option( 'fj_infusionsoft_api_client_id' );
	}

	/**
	 * @return string  Client Secret
	 */
	protected function get_client_secret() {
		return get_option( 'fj_infusionsoft_api_client_secret' );
	}
}