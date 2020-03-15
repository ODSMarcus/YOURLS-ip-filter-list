<?php
/*
Plugin Name: IP Filter List
Plugin URI: https://github.com/ODSMarcus/YOURLS-ip-filter-list
Description: Comma-Separated list of IPs to ignore during the logging process
Version: 1.0
Author: Marcus Allen
*/

// No direct call
if (!defined('YOURLS_ABSPATH')) {
    die();
}

// Plugin settings page etc.
yourls_add_action('plugins_loaded', 'ip_filter_list_add_settings');
function ip_filter_list_add_settings()
{
    yourls_register_plugin_page('ip_filter_list', 'IP Filter List Settings', 'ip_filter_list_settings_page');
}

function ip_filter_list_settings_page()
{
    // Check if form was submitted
    if (isset($_POST['ip_filter_list'])) {
        // If so, verify nonce
        yourls_verify_nonce('ip_filter_list_settings');
        // and process submission if nonce is valid
        ozh_ip_filter_list_settings_update();
    }

    $ip_filter_list = yourls_get_option('ip_filter_list');
    $nonce = yourls_create_nonce('ip_filter_list_settings');

    echo <<<HTML
        <main>
            <h2>IP Filter List Settings</h2>
            <form method="post">
            <input type="hidden" name="nonce" value="$nonce" />
            <p>
                <label>Random Keyword Length</label>
                <input type="number" name="ip_filter_list" min="1" max="128" value="$ip_filter_list" />
            </p>
            <p><input type="submit" value="Save" class="button" /></p>
            </form>
        </main>
HTML;
}

function ozh_ip_filter_list_settings_update()
{
    $ip_filter_list = $_POST['ip_filter_list'];

    if ($ip_filter_list) {
        yourls_update_option('ip_filter_list', trim($ip_filter_list));
    } else {
        echo "Error: No length value given.";
    }
}
