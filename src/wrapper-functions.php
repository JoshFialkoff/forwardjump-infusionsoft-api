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
 * @return obj $infusionsoft    Required for each API call
 */
function fj_infusionsoft_init() {

	return new \ForwardJump\InfusionsoftAPI\Infusionsoft_Init();

}




