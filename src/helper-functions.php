<?php
/**
 * Helper Functions
 *
 * @package     ForwardJump Infusionsoft API
 * @since       1.0.0
 * @author      Tim Jensen
 * @link        https://www.timjensen.us
 * @license     GNU General Public License 2.0+
 */

/**
 * Wrapper function for getting the Infusionsoft class and valid token
 *
 * @return \ForwardJump\Infusionsoft\obj
 */
function fj_infusionsoft_init() {
	return \ForwardJump\Infusionsoft\infusionsoft_init();
}

/**
 * Wrapper function for adding error messages to Admin Notices
 *
 * @param array $error_message
 */
function fj_update_error_messages( $error_message = null ) {
	\ForwardJump\Infusionsoft\update_error_messages( $error_message );
}

