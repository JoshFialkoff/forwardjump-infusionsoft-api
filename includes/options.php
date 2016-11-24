<?php
/**
 * Adds options page
 */

add_action( 'admin_menu', 'forwardjump_infusionsoft_sdk_admin_menu' );
/**
 * Add the settings page to the menu.
 */
function forwardjump_infusionsoft_sdk_admin_menu() {
    add_options_page(
        'ForwardJump Infusionsoft SDK Settings',    // Page title
        'FJ Infusionsoft SDK',             // Menu title
        'install_plugins',              // Available to users with this capability
        'forwardjump-infusionsoft-sdk-admin-menu',                       // Menu slug
        'forwardjump_infusionsoft_sdk_display_admin_page'    // Function to call
    );
}

/**
 * Display the settings form and link back.
 */
function forwardjump_infusionsoft_sdk_display_admin_page(){
    echo '<h2>ForwardJump Infusionsoft SDK Settings</h2>';

    echo '<form method="POST" action="options.php">';
        settings_fields( 'fj_infusionsoft_sdk_settings' );

        do_settings_sections( 'fj_infusionsoft_sdk_settings' );

        submit_button();
    echo '</form>';
}

add_action( 'admin_init', 'forwardjump_infusionsoft_sdk_admin_init' );
/**
 * Define the fields used on the settings page.
 */
function forwardjump_infusionsoft_sdk_admin_init() {
    add_settings_section( 'fj_infusionsoft_sdk_settings',
        'ForwardJump Infusionsoft SDK',
        null,
        'fj_infusionsoft_sdk_settings');

    add_settings_field( 'fj_infusionsoft_sdk_client_id',
        'Infusionsoft&reg; Client ID',
        'fj_infusionsoft_sdk_client_id_field',
        'fj_infusionsoft_sdk_settings',
        'fj_infusionsoft_sdk_settings');

    add_settings_field( 'fj_infusionsoft_sdk_client_secret',
        'Infusionsoft&reg; Client Secret',
        'fj_infusionsoft_sdk_client_secret_field',
        'fj_infusionsoft_sdk_settings',
        'fj_infusionsoft_sdk_settings');
    add_settings_field( 'fj_infusionsoft_request_permission',
        'Infusionsoft&reg; Request Permission',
        'fj_infusionsoft_request_permission_link',
        'fj_infusionsoft_sdk_settings',
        'fj_infusionsoft_sdk_settings');

    register_setting( 'fj_infusionsoft_sdk_settings', 'fj_infusionsoft_sdk_client_id', 'infusionsoft_sdk_sanitize' );
    register_setting( 'fj_infusionsoft_sdk_settings', 'fj_infusionsoft_sdk_client_secret', 'infusionsoft_sdk_sanitize' );
    register_setting( 'fj_infusionsoft_sdk_settings', 'fj_infusionsoft_request_permission' );
}

/**
 * Display the Client ID field.
 */
function fj_infusionsoft_sdk_client_id_field() {
    echo '<input type="text" name="fj_infusionsoft_sdk_client_id" value="' . get_option( 'fj_infusionsoft_sdk_client_id' ) . '" size="30" /><br />';
    echo '<span class="description">Your client ID is avalable at <a href="https://keys.developer.infusionsoft.com/apps/mykeys" target="_blank">https://keys.developer.infusionsoft.com/apps/mykeys</a>.</span>';
}

/**
 * Display the Client Secret field.
 */
function fj_infusionsoft_sdk_client_secret_field() {
    echo '<input type="text" name="fj_infusionsoft_sdk_client_secret" value="' . get_option( 'fj_infusionsoft_sdk_client_secret' ) . '" size="15" /><br />';
    echo '<span class="description">Your client ID is avalable at <a href="https://keys.developer.infusionsoft.com/apps/mykeys" target="_blank">https://keys.developer.infusionsoft.com/apps/mykeys</a>.</span>';
}

/**
 * Request permission by signing in to Infusionsoft
 *
 * @see https://developer.infusionsoft.com/docs/xml-rpc/#authentication-request-permission
 */
function fj_infusionsoft_request_permission_link() {
    if ( ! get_option( 'fj_infusionsoft_sdk_client_id' ) || ! get_option( 'fj_infusionsoft_sdk_client_secret' ) ) {
        return;
    }

    $infusionsoft = new \Infusionsoft\Infusionsoft(array(
        'clientId'     => get_option( 'fj_infusionsoft_sdk_client_id' ),
        'clientSecret' => get_option( 'fj_infusionsoft_sdk_client_secret' ),
        'redirectUri'  => admin_url()
    ));

    echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '" target="_blank">Click here to authorize</a>';
}

/**
 * Remove non-alphanumeric characters.
 *
 * @param string $value
 * @return string
 */
function infusionsoft_sdk_sanitize( $value ) {
    return preg_replace("/[^a-zA-Z0-9]+/", "", $value);
}

function fj_infusionsoft_token_success_admin_notice() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p>Your Infusionsoft Token has been successfully added!</p>
    </div>
    <?php
}
