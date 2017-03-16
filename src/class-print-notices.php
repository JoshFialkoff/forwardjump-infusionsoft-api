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

		if ( ! $this->admin_notices ) {
			return;
		}

		$notices = array();

		if ( isset( $this->admin_notices['request_access_token'] ) && 'false' === $this->admin_notices['request_access_token'] ) {
			$notices[] = 'Infusionsoft Error: </b>There was a problem <b>requesting</b> the Infusionsoft Access Token.';
		}

		if ( isset( $this->admin_notices['refresh_access_token'] ) && 'false' === $this->admin_notices['refresh_access_token'] ) {
			$notices[] = '<b>Infusionsoft Error: </b>There was a problem <b>refreshing</b> the Infusionsoft Access Token.';
		}

		if ( isset( $this->admin_notices['valid_access_token'] ) && 'false' === $this->admin_notices['valid_access_token'] ) {
			$notices[] = '<b>Infusionsoft Error: </b>It looks like you have an <b>invalid</b> Infusionsoft Access Token.';
		}

		if ( ! empty( $this->admin_notices['error_message'] ) ) {
			$notices[] = '<b>Infusionsoft Error: </b>' . $this->admin_notices['error_message'];
		}

		ob_start();
		foreach ( $notices as $notice ) {
			include FJ_INFUSIONSOFT_API_DIR . '/views/admin-notices-error.php';
		}
		$html_string = ob_get_clean();

		add_action( 'admin_notices', function () use ( $html_string ) {
			echo $html_string;
		} );
	}
}