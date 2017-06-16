<?php
/**
 * Infusionsoft API functions
 *
 * @package     ForwardJump Infusionsoft API
 * @since       1.0.0
 * @author      Tim Jensen
 * @link        https://www.timjensen.us
 * @license     GNU General Public License 2.0+
 */

/**
 * Instantiates the Infusionsoft class and checks for a valid token
 *
 * @return object|bool $infusionsoft Required for each API call
 */
function fj_infusionsoft_init() {

	try {
		$infusionsoft = new \ForwardJump\InfusionsoftAPI\Infusionsoft_Init();

		return $infusionsoft;

	} catch ( Infusionsoft\Http\HttpException $e ) {

		new \ForwardJump\InfusionsoftAPI\Response_Handler( [
				'message' => $e->getMessage(),
				'code'    => $e->getCode(),
				'file'    => $e->getFile(),
				'line'    => $e->getLine(),
			]
		);

		return false;
	}
}

/**
 * @param mixed $message
 */
function fj_update_error_messages( $message ) {

	new \ForwardJump\InfusionsoftAPI\Response_Handler( $message );

}




