<?php

namespace Gobi\Capabilities;

/**
 * User capabilities and roles registration for GOBi.
 *
 * Handles registration of GOBi-specific user roles
 * and capabilities for legislative domain management.
 */
class Register
{
    /**
     * Initialize capabilities registration.
     *
     * @return void
     */
    public static function init()
    {
        add_action('init', [__CLASS__, 'register_gobi_capabilities']);
    }

    /**
     * Register GOBi-specific capabilities.
     *
     * @return void
     */
    public static function register_gobi_capabilities()
    {
        // Get administrator role
        $admin_role = get_role('administrator');
        
        if (!$admin_role) {
            return;
        }

        // Define GOBi-specific capabilities
        $gobi_capabilities = [
            'gobi_change_project_state',
            'gobi_view_bitacora',
            'gobi_manage_home_curation',
            'gobi_edit_project_notes',
            'gobi_view_private_explainers',
            'gobi_view_internal_metrics',
        ];

        // Add capabilities to administrator role
        foreach ($gobi_capabilities as $cap) {
            $admin_role->add_cap($cap);
        }

        // Store capabilities mapping for reference
        update_option('gobi_capabilities', $gobi_capabilities);
    }

    /**
     * Check if current user has specific GOBi capability.
     *
     * @param string $capability Capability to check.
     * @param int|null $user_id User ID (optional, defaults to current user).
     * @return bool Whether user has the capability.
     */
    public static function current_user_can($capability, $user_id = null)
    {
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }

        return user_can($user_id, $capability);
    }

    /**
     * Get all GOBi capabilities.
     *
     * @return array All registered GOBi capabilities.
     */
    public static function get_all_capabilities()
    {
        return get_option('gobi_capabilities', []);
    }

    /**
     * Check if user can change project state.
     *
     * @param int|null $user_id User ID (optional).
     * @return bool Whether user can change project state.
     */
    public static function user_can_change_state($user_id = null)
    {
        return self::current_user_can('gobi_change_project_state', $user_id);
    }

    /**
     * Check if user can view bitacora.
     *
     * @param int|null $user_id User ID (optional).
     * @return bool Whether user can view bitacora.
     */
    public static function user_can_view_bitacora($user_id = null)
    {
        return self::current_user_can('gobi_view_bitacora', $user_id);
    }

    /**
     * Check if user can manage home curation.
     *
     * @param int|null $user_id User ID (optional).
     * @return bool Whether user can manage home curation.
     */
    public static function user_can_manage_home($user_id = null)
    {
        return self::current_user_can('gobi_manage_home_curation', $user_id);
    }

    /**
     * Check if user can edit project notes.
     *
     * @param int|null $user_id User ID (optional).
     * @return bool Whether user can edit project notes.
     */
    public static function user_can_edit_notes($user_id = null)
    {
        return self::current_user_can('gobi_edit_project_notes', $user_id);
    }

    /**
     * Check if user can view private explainers.
     *
     * @param int|null $user_id User ID (optional).
     * @return bool Whether user can view private explainers.
     */
    public static function user_can_view_explainers($user_id = null)
    {
        return self::current_user_can('gobi_view_private_explainers', $user_id);
    }

    /**
     * Check if user can view internal metrics.
     *
     * @param int|null $user_id User ID (optional).
     * @return bool Whether user can view internal metrics.
     */
    public static function user_can_view_metrics($user_id = null)
    {
        return self::current_user_can('gobi_view_internal_metrics', $user_id);
    }

    /**
     * Get capability description for display.
     *
     * @param string $capability Capability key.
     * @return string Human-readable capability description.
     */
    public static function get_capability_description($capability)
    {
        $descriptions = [
            'gobi_change_project_state' => __('Cambiar estado de proyectos legislativos', 'gobi-core'),
            'gobi_view_bitacora' => __('Ver bitácora de auditoría', 'gobi-core'),
            'gobi_manage_home_curation' => __('Gestionar curaduría de portada', 'gobi-core'),
            'gobi_edit_project_notes' => __('Editar notas internas de proyectos', 'gobi-core'),
            'gobi_view_private_explainers' => __('Ver explicadores privados', 'gobi-core'),
            'gobi_view_internal_metrics' => __('Ver métricas internas', 'gobi-core'),
        ];

        return $descriptions[$capability] ?? $capability;
    }
}
