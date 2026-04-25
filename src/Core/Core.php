<?php

namespace Gobi\Core;

/**
 * Core coordination for GOBi plugin.
 *
 * Orchestrates all modules without containing business logic.
 */
class Core
{
    /**
     * Initialize GOBi Core modules.
     *
     * @return void
     */
    public static function init()
    {
        self::load_modules();
    }

    /**
     * Load all GOBi modules.
     *
     * @return void
     */
    private static function load_modules()
    {
        // CPTs
        require_once GOBI_CORE_PATH . 'src/CPT/Register.php';
        \Gobi\CPT\Register::init();

        // Taxonomies
        require_once GOBI_CORE_PATH . 'src/Taxonomies/Register.php';
        \Gobi\Taxonomies\Register::init();

        // Relations
        require_once GOBI_CORE_PATH . 'src/Relations/Country.php';

        // Capabilities
        require_once GOBI_CORE_PATH . 'src/Capabilities/Register.php';
        \Gobi\Capabilities\Register::init();

        // Workflow
        require_once GOBI_CORE_PATH . 'src/Workflow/Manager.php';
        \Gobi\Workflow\Manager::init();

        // Bitacora
        require_once GOBI_CORE_PATH . 'src/Bitacora/Logger.php';
        \Gobi\Bitacora\Logger::init();
    }

    /**
     * Plugin activation hook.
     *
     * @return void
     */
    public static function activate()
    {
        \Gobi\Bitacora\Logger::create_table();
        
        // Flush rewrite rules for CPTs
        flush_rewrite_rules();
    }
}
