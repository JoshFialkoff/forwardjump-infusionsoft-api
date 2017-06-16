<?php
/**
 * Prints Admin notices
 *
 * @package     ForwardJump Infusionsoft API
 * @since       1.0.0
 * @author      Tim Jensen
 * @link        https://www.timjensen.us
 * @license     GNU General Public License 2.0+
 */

namespace ForwardJump\InfusionsoftAPI;

/**
 * Class Print_Notices
 *
 * @package ForwardJump\InfusionsoftAPI
 */
class Print_Notices {


	/**
	 * @var mixed|void
	 */
	protected $admin_notices;

	/**
	 * Print_Notices constructor.
	 */
	public function __construct() {

		$this->admin_notices = get_option( 'fj_infusionsoft_api_errors' );

		$this->print_notices();

	}

	/**
	 * Prints error messages in WP admin notices
	 */
	protected function print_notices() {

		if ( ! defined( 'WP_DEBUG' ) || false == WP_DEBUG ) {
			return;
		}

		if ( ! $this->admin_notices ) {
			return;
		}

		$this->admin_notices = (array) $this->admin_notices;

		ob_start();

		$notice = $this->admin_notices[0]['message'] ?: 'There was an error with communicating with infusionsoft';

		include FJ_INFUSIONSOFT_API_DIR . '/views/admin-notices-error.php';

		$html_string = ob_get_clean();

		add_action( 'admin_notices', function () use ( $html_string ) {
			echo $html_string;
		} );
	}
}
