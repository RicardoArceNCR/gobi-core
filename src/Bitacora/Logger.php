<?php

namespace Gobi\Bitacora;

/**
 * Audit trail and logging system for GOBi.
 *
 * Tracks all important actions, changes, and events
 * for compliance and debugging purposes using custom table.
 */
class Logger
{
    /**
     * Initialize audit logging system.
     *
     * @return void
     */
    public static function init()
    {
        add_action('init', [__CLASS__, 'register_audit_hooks']);
    }

    /**
     * Register audit logging hooks.
     *
     * @return void
     */
    public static function register_audit_hooks()
    {
        // Log GOBi-specific actions
        add_action('gobi_estado_cambiado', [__CLASS__, 'log_estado_cambio'], 10, 4);
    }

    /**
     * Log general action to audit trail.
     *
     * @param array $data Log data.
     * @return void
     */
    public static function log($data)
    {
        global $wpdb;

        $table = $wpdb->prefix . 'gobi_bitacora';

        // Validate required fields
        $required_fields = ['entidad', 'entidad_id', 'accion', 'valor_anterior', 'valor_nuevo', 'motivo', 'usuario'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field])) {
                $data[$field] = '';
            }
        }

        // Add timestamp if not provided
        if (!isset($data['fecha'])) {
            $data['fecha'] = current_time('mysql');
        }

        $wpdb->insert($table, [
            'entidad' => sanitize_text_field($data['entidad']),
            'entidad_id' => intval($data['entidad_id']),
            'accion' => sanitize_text_field($data['accion']),
            'valor_anterior' => sanitize_textarea_field($data['valor_anterior']),
            'valor_nuevo' => sanitize_textarea_field($data['valor_nuevo']),
            'motivo' => sanitize_textarea_field($data['motivo']),
            'usuario' => intval($data['usuario']),
            'fecha' => $data['fecha']
        ]);
    }

    /**
     * Log estado cambio action.
     *
     * @param int $proyecto_id Project ID.
     * @param string $estado_anterior Previous state.
     * @param string $estado_nuevo New state.
     * @param string $motivo Reason for change.
     * @return void
     */
    public static function log_estado_cambio($proyecto_id, $estado_anterior, $estado_nuevo, $motivo)
    {
        self::log([
            'entidad' => 'proyecto',
            'entidad_id' => $proyecto_id,
            'accion' => 'estado_cambio',
            'valor_anterior' => $estado_anterior,
            'valor_nuevo' => $estado_nuevo,
            'motivo' => $motivo,
            'usuario' => get_current_user_id()
        ]);
    }

    /**
     * Get audit log entries.
     *
     * @param array $args Query arguments.
     * @return array Log entries.
     */
    public static function get_by_entity($entidad, $entidad_id, $limit = 50)
    {
        global $wpdb;

        $table = $wpdb->prefix . 'gobi_bitacora';

        $sql = $wpdb->prepare(
            "SELECT * FROM $table 
             WHERE entidad = %s AND entidad_id = %d 
             ORDER BY fecha DESC 
             LIMIT %d",
            $entidad,
            $entidad_id,
            $limit
        );

        return $wpdb->get_results($sql, ARRAY_A);
    }

    /**
     * Get audit log entries with filters.
     *
     * @param array $args Query arguments.
     * @return array Log entries.
     */
    public static function get_log($args = [])
    {
        global $wpdb;

        $table = $wpdb->prefix . 'gobi_bitacora';
        
        $where = ['1=1'];
        $params = [];

        // Filter by entidad
        if (!empty($args['entidad'])) {
            $where[] = 'entidad = %s';
            $params[] = $args['entidad'];
        }

        // Filter by entidad_id
        if (!empty($args['entidad_id'])) {
            $where[] = 'entidad_id = %d';
            $params[] = $args['entidad_id'];
        }

        // Filter by usuario
        if (!empty($args['usuario'])) {
            $where[] = 'usuario = %d';
            $params[] = $args['usuario'];
        }

        // Filter by accion
        if (!empty($args['accion'])) {
            $where[] = 'accion = %s';
            $params[] = $args['accion'];
        }

        // Filter by date range
        if (!empty($args['fecha_desde'])) {
            $where[] = 'fecha >= %s';
            $params[] = $args['fecha_desde'];
        }

        if (!empty($args['fecha_hasta'])) {
            $where[] = 'fecha <= %s';
            $params[] = $args['fecha_hasta'];
        }

        $where_clause = implode(' AND ', $where);
        
        $sql = "SELECT * FROM $table WHERE $where_clause ORDER BY fecha DESC";
        
        if (!empty($args['limit'])) {
            $sql .= ' LIMIT %d';
            $params[] = $args['limit'];
        }

        if (!empty($params)) {
            $sql = $wpdb->prepare($sql, $params);
        }

        return $wpdb->get_results($sql, ARRAY_A);
    }

    /**
     * Get audit log summary.
     *
     * @param array $args Query arguments.
     * @return array Summary statistics.
     */
    public static function get_summary($args = [])
    {
        global $wpdb;

        $table = $wpdb->prefix . 'gobi_bitacora';
        
        $where = ['1=1'];
        $params = [];

        // Apply same filters as get_log
        if (!empty($args['entidad'])) {
            $where[] = 'entidad = %s';
            $params[] = $args['entidad'];
        }

        if (!empty($args['fecha_desde'])) {
            $where[] = 'fecha >= %s';
            $params[] = $args['fecha_desde'];
        }

        $where_clause = implode(' AND ', $where);
        
        $sql = $wpdb->prepare(
            "SELECT 
                COUNT(*) as total_entries,
                COUNT(DISTINCT entidad) as entidades_unicas,
                COUNT(DISTINCT usuario) as usuarios_unicos,
                MAX(fecha) as ultima_entrada
             FROM $table 
             WHERE $where_clause",
            $params
        );

        $summary = $wpdb->get_row($sql, ARRAY_A);

        // Get recent activity
        $recent_sql = $wpdb->prepare(
            "SELECT * FROM $table WHERE $where_clause ORDER BY fecha DESC LIMIT 10",
            $params
        );
        $summary->actividad_reciente = $wpdb->get_results($recent_sql, ARRAY_A);

        return $summary;
    }

    /**
     * Get user IP address.
     *
     * @return string User IP address.
     */
    private static function get_user_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '';
        }
    }

    /**
     * Clear old audit log entries.
     *
     * @param int $days_old Number of days to keep.
     * @return int Number of entries deleted.
     */
    public static function clear_old_logs($days_old = 90)
    {
        global $wpdb;

        $table = $wpdb->prefix . 'gobi_bitacora';
        $cutoff_date = date('Y-m-d H:i:s', strtotime("-{$days_old} days"));
        
        $sql = $wpdb->prepare(
            "DELETE FROM $table WHERE fecha < %s",
            $cutoff_date
        );

        return $wpdb->query($sql);
    }
}
