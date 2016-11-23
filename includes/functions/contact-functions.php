<?php
/**
 * Add Infusionsoft functions
 */

/**
 * Add contact to Infusionsoft if not already in the CRM
 *
 * @param array $contact
 */
function fj_infusionsoft_add_with_dup_check( $contact ) {
    if ( ! $contact ) {
        return;
    }

    $infusionsoft = new \Infusionsoft\Infusionsoft(array(
        'clientId' => get_option('fj_infusionsoft_sdk_client_id'),
        'clientSecret' => get_option('fj_infusionsoft_sdk_client_secret'),
        'redirectUri' => admin_url(),
    ));

    // Get the serialized token from the options table
    $_SESSION['token'] = get_option('fj_infusionsoft_sdk_token');

    /**
     * If the serialized token is available in the session storage, we tell the SDK
     * to use that token for subsequent requests.
     *
     * @see https://developer.infusionsoft.com/docs/xml-rpc/#authentication-refresh-an-access-token
     */
    if (isset($_SESSION['token'])) {
        $infusionsoft->setToken(unserialize($_SESSION['token']));
    }

    /**
     * Make sure to also store the new refresh token every time you request and store a new access token.
     */
    if ($infusionsoft->getToken()) {
        try {
            $cid = $infusionsoft->contacts->addWithDupCheck($contact, 'Email');
        } catch (\Infusionsoft\TokenExpiredException $e) {
            // If the request fails due to an expired access token, we can refresh
            // the token and then do the request again.
            $infusionsoft->refreshAccessToken();

            $cid = addWithDupCheck($infusionsoft);
        }

        $contact = $infusionsoft->contacts->load($cid, array('Id', 'FirstName', 'LastName', 'Email'));

        var_dump($contact);

        // Save the serialized token to the current session for subsequent requests
        $_SESSION['token'] = serialize($infusionsoft->getToken());

        // Store serialized token in the WP options table
        update_option('fj_infusionsoft_sdk_token', $_SESSION['token']);
    }
}