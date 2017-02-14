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

		if ( ! $this->getToken() || $this->is_token_expiring() ) {

			$this->try_refresh_access_token();

		}
	}

}
