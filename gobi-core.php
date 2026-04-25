<?php
/**
 * Plugin Name: GOBi Core
 * Plugin URI: https://gobi.com
 * Description: Core domain functionality for GOBi platform. Manages CPTs, workflows, capabilities, and audit trail.
 * Version: 1.0.0
 * Author: GOBi Team
 * License: MIT
 * Text Domain: gobi-core
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.3
 *
 * @package GOBi\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

define('GOBI_CORE_VERSION', '1.0.0');
define('GOBI_CORE_FILE', __FILE__);
define('GOBI_CORE_PATH', plugin_dir_path(__FILE__));
define('GOBI_CORE_URL', plugin_dir_url(__FILE__));

/**
 * Autoloader for GOBi Core classes.
 *
 * @param string $class Class name to autoload.
 */
function gobi_core_autoload($class)
{
    $prefix = 'Gobi\\';
    $len = strlen($prefix);
    
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = GOBI_CORE_PATH . 'src/' . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
}

spl_autoload_register('gobi_core_autoload');

/**
 * Initialize GOBi Core.
 */
function gobi_core_init()
{
    \Gobi\Core\Core::init();
}

// Initialize on WordPress plugins_loaded (earlier than init)
add_action('plugins_loaded', 'gobi_core_init');

/**
 * Plugin activation hook.
 */
register_activation_hook(__FILE__, ['Gobi\Core\Core', 'activate']);

/**
 * Plugin deactivation hook.
 */
register_deactivation_hook(__FILE__, function () {
    // Flush rewrite rules
    flush_rewrite_rules();
});
