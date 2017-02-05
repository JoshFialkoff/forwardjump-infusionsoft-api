<?php
/**
 * Response Handler
 *
 * @package     ForwardJump Infusionsoft API
 * @since       1.0.0
 * @author      Tim Jensen
 * @link        https://www.timjensen.us
 * @license     GNU General Public License 2.0+
 */

namespace ForwardJump\InfusionsoftAPI;

/**
 * Class Response_Handler
 *
 * @package ForwardJump\InfusionsoftAPI
 */
class Response_Handler {

	/**
	 * @var array|null
	 */
	protected $admin_notices;

	/**
	 * Response_Handler constructor.
	 *
	 * @param array $new_admin_notice
	 */
	public function __construct( $new_admin_notice = array() ) {

	    $this->update_admin_notices( $new_admin_notice );

	}

	/**
	 * Updates admin notices
	 *
	 * @param $new_admin_notice
	 */
	protected function update_admin_notices( $new_admin_notice ) {
		$this->admin_notices = get_option( 'fj_infusionsoft_api_errors' );

		if ( ! is_array( $this->admin_notices ) ) {
			$this->admin_notices = array();
		}

		if ( is_array( $new_admin_notice ) ) {
			$this->admin_notices = array_merge( $this->admin_notices, $new_admin_notice );

			update_option( 'fj_infusionsoft_api_errors', $this->admin_notices );
		}
    }
}