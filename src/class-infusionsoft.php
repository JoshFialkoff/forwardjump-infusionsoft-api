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

		$fj_infusionsoft_api_settings = get_option( 'fj_infusionsoft_api_client_id' );

		$this->clientId = $fj_infusionsoft_api_settings['client_id'];

		$this->clientSecret = $fj_infusionsoft_api_settings['client_secret'];

		$this->redirectUri = admin_url();

	}
}