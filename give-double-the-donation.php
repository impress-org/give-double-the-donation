<?php
/**
 * Plugin Name: Give - Double the Donation
 * Plugin URI:  https://givewp.com/addons/give-double-the-donation/
 * Description: Easily integrate with the Double the Donation employer matching platform.
 * Version:     1.0.1
 * Author:      GiveWP
 * Author URI:  https://givewp.com/
 * Text Domain: give-double-the-donation
 * Domain Path: /languages
 */

namespace GiveDoubleTheDonation;

use GiveDoubleTheDonation\Addon\Activation;
use GiveDoubleTheDonation\Addon\Environment;
use GiveDoubleTheDonation\DoubleTheDonation\AddonServiceProvider;

defined( 'ABSPATH' ) or exit;

// Add-on name
define( 'GIVE_DTD_NAME', 'Give - Double the Donation Integration' );

// Versions
define( 'GIVE_DTD_VERSION', '1.0.1' );
define( 'GIVE_DTD_MIN_GIVE_VERSION', '2.9.0' );

// Add-on paths
define( 'GIVE_DTD_FILE', __FILE__ );
define( 'GIVE_DTD_DIR', plugin_dir_path( GIVE_DTD_FILE ) );
define( 'GIVE_DTD_URL', plugin_dir_url( GIVE_DTD_FILE ) );
define( 'GIVE_DTD_BASENAME', plugin_basename( GIVE_DTD_FILE ) );

require 'vendor/autoload.php';

// Activate add-on hook.
register_activation_hook( GIVE_DTD_FILE, [ Activation::class, 'activateAddon' ] );

// Deactivate add-on hook.
register_deactivation_hook( GIVE_DTD_FILE, [ Activation::class, 'deactivateAddon' ] );

// Uninstall add-on hook.
register_uninstall_hook( GIVE_DTD_FILE, [ Activation::class, 'uninstallAddon' ] );

// Register the add-on service provider with the GiveWP core.
add_action(
	'before_give_init',
	function () {
		// Check Give min required version.
		if ( Environment::giveMinRequiredVersionCheck() ) {
			give()->registerServiceProvider( AddonServiceProvider::class );
		}
	}
);

// Check to make sure GiveWP core is installed and compatible with this add-on.
add_action( 'admin_init', [ Environment::class, 'checkEnvironment' ] );
