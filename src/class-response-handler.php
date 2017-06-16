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
		$this->admin_notices = (array) get_option( 'fj_infusionsoft_api_errors' );

		$new_admin_notice              = (array) $new_admin_notice;
		$new_admin_notice['timestamp'] = time();

		array_unshift( $this->admin_notices, $new_admin_notice );

		// We want to save a maximum of 20 errors.
		$this->admin_notices = array_slice( $this->admin_notices, 0, 20 );

		update_option( 'fj_infusionsoft_api_errors', $this->admin_notices );
	}
}
