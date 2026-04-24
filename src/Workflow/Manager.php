<?php

namespace Gobi\Workflow;

use Gobi\Bitacora\Logger;

/**
 * Workflow management system for GOBi.
 *
 * Handles legislative project state transitions,
 * validation, and audit logging.
 */
class Manager
{
    /**
     * Initialize workflow system.
     *
     * @return void
     */
    public static function init()
    {
        add_action('init', [__CLASS__, 'register_workflow_hooks']);
    }

    /**
     * Register workflow hooks.
     *
     * @return void
     */
    public static function register_workflow_hooks()
    {
        // Hook into post saves for state validation
        add_action('save_post', [__CLASS__, 'handle_proyecto_state_save'], 10, 2);
    }

    /**
     * Get valid states for proyectos.
     *
     * @return array Valid states.
     */
    public static function get_states()
    {
        return [
            'presentado',
            'en_comision',
            'en_debate',
            'votado',
            'aprobado',
            'archivado'
        ];
    }

    /**
     * Get valid transitions for proyectos.
     *
     * @return array Valid transitions matrix.
     */
    public static function get_transitions()
    {
        return [
            'presentado' => ['en_comision', 'archivado'],
            'en_comision' => ['en_debate', 'archivado'],
            'en_debate' => ['votado', 'archivado'],
            'votado' => ['aprobado', 'archivado'],
            'aprobado' => [],
            'archivado' => [],
        ];
    }

    /**
     * Check if transition is valid.
     *
     * @param string $from Current state.
     * @param string $to Target state.
     * @return bool Whether transition is allowed.
     */
    public static function can_transition($from, $to)
    {
        $transitions = self::get_transitions();
        return in_array($to, $transitions[$from] ?? []);
    }

    /**
     * Change state of a proyecto.
     *
     * @param int $proyecto_id Project ID.
     * @param string $new_state New state.
     * @param string $reason Reason for change.
     * @return bool|\WP_Error Whether change was successful or WP_Error on failure.
     */
    public static function change_state($proyecto_id, $new_state, $reason)
    {
        // Validate inputs
        if (!get_post($proyecto_id) || get_post_type($proyecto_id) !== 'gobi_proyecto') {
            return new \WP_Error('invalid_project', 'Proyecto inválido o no encontrado');
        }

        $current_state = get_post_meta($proyecto_id, '_gobi_estado', true) ?: 'presentado';

        // Check if transition is valid
        if (!self::can_transition($current_state, $new_state)) {
            return new \WP_Error('invalid_transition', sprintf('Transición no permitida: de %s a %s', $current_state, $new_state));
        }

        // Validate reason
        if (empty($reason)) {
            return new \WP_Error('empty_reason', 'El motivo del cambio es obligatorio');
        }

        // Update state
        update_post_meta($proyecto_id, '_gobi_estado', $new_state);

        // Trigger action for other integrations (Logger will handle this via hook)
        do_action('gobi_estado_cambiado', $proyecto_id, $current_state, $new_state, $reason);

        return true;
    }

    /**
     * Handle proyecto state save from admin.
     *
     * @param int $post_id Post ID.
     * @param WP_Post $post Post object.
     * @return void
     */
    public static function handle_proyecto_state_save($post_id, $post)
    {
        // Skip auto-saves
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Only handle proyectos
        if ($post->post_type !== 'gobi_proyecto') {
            return;
        }

        // Check user permissions
        if (!current_user_can('gobi_change_project_state')) {
            return;
        }

        // Check nonce
        if (!isset($_POST['gobi_proyecto_meta_nonce']) || !wp_verify_nonce($_POST['gobi_proyecto_meta_nonce'], 'gobi_proyecto_meta_save')) {
            return;
        }

        // Get new state and reason
        $new_state = isset($_POST['gobi_estado']) ? sanitize_text_field($_POST['gobi_estado']) : '';
        $reason = isset($_POST['gobi_estado_motivo']) ? sanitize_textarea_field($_POST['gobi_estado_motivo']) : '';

        // Only change if state is different
        $current_state = get_post_meta($post_id, '_gobi_estado', true) ?: 'presentado';
        if ($new_state === $current_state) {
            return;
        }

        // Attempt state change
        $result = self::change_state($post_id, $new_state, $reason);

        // If failed, show admin notice
        if (is_wp_error($result)) {
            add_action('admin_notices', function() use ($result) {
                $class = 'notice notice-error';
                $message = $result->get_error_message();
                printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
            });
        }
    }

    /**
     * Get current state of a proyecto.
     *
     * @param int $proyecto_id Project ID.
     * @return string Current state.
     */
    public static function get_current_state($proyecto_id)
    {
        return get_post_meta($proyecto_id, '_gobi_estado', true) ?: 'presentado';
    }

    /**
     * Get state label for display.
     *
     * @param string $state State key.
     * @return string Human-readable state.
     */
    public static function get_state_label($state)
    {
        $labels = [
            'presentado' => __('Presentado', 'gobi-core'),
            'en_comision' => __('En Comisión', 'gobi-core'),
            'en_debate' => __('En Debate', 'gobi-core'),
            'votado' => __('Votado', 'gobi-core'),
            'aprobado' => __('Aprobado', 'gobi-core'),
            'archivado' => __('Archivado', 'gobi-core'),
        ];

        return $labels[$state] ?? $state;
    }

    /**
     * Get next possible states for current state.
     *
     * @param string $current_state Current state.
     * @return array Next possible states.
     */
    public static function get_next_states($current_state)
    {
        $transitions = self::get_transitions();
        return $transitions[$current_state] ?? [];
    }

    /**
     * Check if user can change state of proyecto.
     *
     * @param int $proyecto_id Project ID.
     * @param int $user_id User ID (optional, defaults to current).
     * @return bool Whether user can change state.
     */
    public static function user_can_change_state($proyecto_id, $user_id = null)
    {
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }

        return user_can($user_id, 'gobi_change_project_state');
    }
}
