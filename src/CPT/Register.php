<?php

namespace Gobi\CPT;

/**
 * Custom Post Type registration for GOBi.
 *
 * Handles registration of all GOBi-specific post types
 * for the legislative domain: proyectos, diputados, comisiones
 */
class Register
{
    /**
     * Initialize CPT registration.
     *
     * @return void
     */
    public static function init()
    {
        add_action('init', [__CLASS__, 'register_proyecto_cpt']);
        add_action('init', [__CLASS__, 'register_diputado_cpt']);
        add_action('init', [__CLASS__, 'register_comision_cpt']);
        add_action('init', [__CLASS__, 'register_pais_cpt']);
        
        // Add meta boxes for CPTs
        add_action('add_meta_boxes', [__CLASS__, 'add_meta_boxes']);
        
        // Save post meta
        add_action('save_post', [__CLASS__, 'save_post_meta']);
    }

    /**
     * Register Proyecto post type.
     *
     * @return void
     */
    public static function register_proyecto_cpt()
    {
        $labels = [
            'name' => __('Proyectos', 'gobi-core'),
            'singular_name' => __('Proyecto', 'gobi-core'),
            'menu_name' => __('Proyectos Legislativos', 'gobi-core'),
            'name_admin_bar' => __('Proyecto', 'gobi-core'),
            'add_new' => __('Agregar Nuevo', 'gobi-core'),
            'add_new_item' => __('Agregar Nuevo Proyecto', 'gobi-core'),
            'new_item' => __('Nuevo Proyecto', 'gobi-core'),
            'edit_item' => __('Editar Proyecto', 'gobi-core'),
            'view_item' => __('Ver Proyecto', 'gobi-core'),
            'all_items' => __('Todos los Proyectos', 'gobi-core'),
            'search_items' => __('Buscar Proyectos', 'gobi-core'),
            'parent_item_colon' => __('Proyectos Padre:', 'gobi-core'),
            'not_found' => __('No se encontraron proyectos.', 'gobi-core'),
            'not_found_in_trash' => __('No se encontraron proyectos en la papelera.', 'gobi-core'),
            'featured_image' => __('Imagen del Proyecto', 'gobi-core'),
            'set_featured_image' => __('Establecer imagen del proyecto', 'gobi-core'),
            'remove_featured_image' => __('Quitar imagen del proyecto', 'gobi-core'),
            'use_featured_image' => __('Usar como imagen del proyecto', 'gobi-core'),
            'archives' => __('Archivo de proyectos', 'gobi-core'),
            'insert_into_item' => __('Insertar en proyecto', 'gobi-core'),
            'uploaded_to_this_item' => __('Subido a este proyecto', 'gobi-core'),
            'filter_items_list' => __('Filtrar lista de proyectos', 'gobi-core'),
            'items_list_navigation' => __('Navegación de lista de proyectos', 'gobi-core'),
            'items_list' => __('Lista de proyectos', 'gobi-core'),
        ];

        $args = [
            'label' => __('Proyecto', 'gobi-core'),
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'proyecto'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 25,
            'menu_icon' => 'dashicons-portfolio',
            'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments', 'revisions'],
            'show_in_rest' => true,
            'rest_base' => 'proyectos',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ];

        register_post_type('gobi_proyecto', $args);
    }

    /**
     * Register Diputado post type.
     *
     * @return void
     */
    public static function register_diputado_cpt()
    {
        $labels = [
            'name' => __('Diputados', 'gobi-core'),
            'singular_name' => __('Diputado', 'gobi-core'),
            'menu_name' => __('Diputados', 'gobi-core'),
            'name_admin_bar' => __('Diputado', 'gobi-core'),
            'add_new' => __('Agregar Nuevo', 'gobi-core'),
            'add_new_item' => __('Agregar Nuevo Diputado', 'gobi-core'),
            'new_item' => __('Nuevo Diputado', 'gobi-core'),
            'edit_item' => __('Editar Diputado', 'gobi-core'),
            'view_item' => __('Ver Diputado', 'gobi-core'),
            'all_items' => __('Todos los Diputados', 'gobi-core'),
            'search_items' => __('Buscar Diputados', 'gobi-core'),
            'parent_item_colon' => __('Diputados Padre:', 'gobi-core'),
            'not_found' => __('No se encontraron diputados.', 'gobi-core'),
            'not_found_in_trash' => __('No se encontraron diputados en la papelera.', 'gobi-core'),
            'featured_image' => __('Foto del Diputado', 'gobi-core'),
            'set_featured_image' => __('Establecer foto del diputado', 'gobi-core'),
            'remove_featured_image' => __('Quitar foto del diputado', 'gobi-core'),
            'use_featured_image' => __('Usar como foto del diputado', 'gobi-core'),
            'archives' => __('Archivo de diputados', 'gobi-core'),
            'insert_into_item' => __('Insertar en diputado', 'gobi-core'),
            'uploaded_to_this_item' => __('Subido a este diputado', 'gobi-core'),
            'filter_items_list' => __('Filtrar lista de diputados', 'gobi-core'),
            'items_list_navigation' => __('Navegación de lista de diputados', 'gobi-core'),
            'items_list' => __('Lista de diputados', 'gobi-core'),
        ];

        $args = [
            'label' => __('Diputado', 'gobi-core'),
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'diputado'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 26,
            'menu_icon' => 'dashicons-groups',
            'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'],
            'show_in_rest' => true,
            'rest_base' => 'diputados',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ];

        register_post_type('gobi_diputado', $args);
    }

    /**
     * Register Comision post type.
     *
     * @return void
     */
    public static function register_comision_cpt()
    {
        $labels = [
            'name' => __('Comisiones', 'gobi-core'),
            'singular_name' => __('Comisión', 'gobi-core'),
            'menu_name' => __('Comisiones', 'gobi-core'),
            'name_admin_bar' => __('Comisión', 'gobi-core'),
            'add_new' => __('Agregar Nueva', 'gobi-core'),
            'add_new_item' => __('Agregar Nueva Comisión', 'gobi-core'),
            'new_item' => __('Nueva Comisión', 'gobi-core'),
            'edit_item' => __('Editar Comisión', 'gobi-core'),
            'view_item' => __('Ver Comisión', 'gobi-core'),
            'all_items' => __('Todas las Comisiones', 'gobi-core'),
            'search_items' => __('Buscar Comisiones', 'gobi-core'),
            'parent_item_colon' => __('Comisiones Padre:', 'gobi-core'),
            'not_found' => __('No se encontraron comisiones.', 'gobi-core'),
            'not_found_in_trash' => __('No se encontraron comisiones en la papelera.', 'gobi-core'),
            'archives' => __('Archivo de comisiones', 'gobi-core'),
            'insert_into_item' => __('Insertar en comisión', 'gobi-core'),
            'uploaded_to_this_item' => __('Subido a esta comisión', 'gobi-core'),
            'filter_items_list' => __('Filtrar lista de comisiones', 'gobi-core'),
            'items_list_navigation' => __('Navegación de lista de comisiones', 'gobi-core'),
            'items_list' => __('Lista de comisiones', 'gobi-core'),
        ];

        $args = [
            'label' => __('Comisión', 'gobi-core'),
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'comision'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 27,
            'menu_icon' => 'dashicons-building',
            'supports' => ['title', 'editor', 'author', 'excerpt', 'custom-fields', 'revisions'],
            'show_in_rest' => true,
            'rest_base' => 'comisiones',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ];

        register_post_type('gobi_comision', $args);
    }

    /**
     * Register Pais post type.
     *
     * @return void
     */
    public static function register_pais_cpt()
    {
        register_post_type('gobi_pais', [
            'labels' => [
                'name' => 'Países',
                'singular_name' => 'País',
                'add_new_item' => 'Agregar Nuevo País',
                'edit_item' => 'Editar País',
            ],
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-admin-site-alt3',
            'supports' => ['title', 'editor', 'thumbnail', 'revisions'],
            'has_archive' => true,
            'rewrite' => ['slug' => 'paises'],
        ]);
    }

    /**
     * Add meta boxes for CPTs.
     *
     * @return void
     */
    public static function add_meta_boxes()
    {
        add_meta_box(
            'gobi_proyecto_meta',
            __('Detalles del Proyecto', 'gobi-core'),
            [__CLASS__, 'proyecto_meta_box'],
            'gobi_proyecto',
            'normal',
            'high'
        );

        add_meta_box(
            'gobi_diputado_meta',
            __('Detalles del Diputado', 'gobi-core'),
            [__CLASS__, 'diputado_meta_box'],
            'gobi_diputado',
            'normal',
            'high'
        );

        add_meta_box(
            'gobi_comision_meta',
            __('Detalles de la Comisión', 'gobi-core'),
            [__CLASS__, 'comision_meta_box'],
            'gobi_comision',
            'normal',
            'high'
        );
    }

    /**
     * Get countries for dropdown.
     *
     * @return array List of countries.
     */
    private static function get_countries()
    {
        $countries = get_posts([
            'post_type' => 'gobi_pais',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ]);
        
        $options = ['' => __('Seleccionar país...', 'gobi-core')];
        foreach ($countries as $country) {
            $options[$country->ID] = $country->post_title;
        }
        
        return $options;
    }

    /**
     * Render country selector field.
     *
     * @param WP_Post $post Current post object.
     * @param string $field_name Field name.
     * @param string $label Field label.
     * @return void
     */
    private static function render_country_selector($post, $field_name, $label)
    {
        $countries = self::get_countries();
        $selected_country = get_post_meta($post->ID, '_gobi_pais_id', true);
        
        echo '<tr>';
        echo '<th><label for="' . esc_attr($field_name) . '">' . esc_html($label) . '</label></th>';
        echo '<td><select id="' . esc_attr($field_name) . '" name="' . esc_attr($field_name) . '">';
        
        foreach ($countries as $country_id => $country_name) {
            $selected = selected($selected_country, $country_id, false);
            echo '<option value="' . esc_attr($country_id) . '"' . $selected . '>' . esc_html($country_name) . '</option>';
        }
        
        echo '</select></td>';
        echo '</tr>';
    }

    /**
     * Render comision selector field.
     *
     * @param WP_Post $post Current post object.
     * @return void
     */
    private static function render_comision_selector($post)
    {
        $selected_country = absint(get_post_meta($post->ID, '_gobi_pais_id', true));
        $selected_comision = absint(get_post_meta($post->ID, '_gobi_comision_id', true));

        echo '<tr>';
        echo '<th><label for="gobi_comision_id">' . esc_html__('Comisión Asignada', 'gobi-core') . '</label></th>';
        echo '<td><select id="gobi_comision_id" name="gobi_comision_id">';
        echo '<option value="">' . esc_html__('Seleccionar comisión...', 'gobi-core') . '</option>';

        if (!$selected_country) {
            echo '<option value="" disabled>' . esc_html__('Primero seleccione y guarde un país', 'gobi-core') . '</option>';
            echo '</select></td>';
            echo '</tr>';
            return;
        }

        $comisiones = get_posts([
            'post_type' => 'gobi_comision',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => '_gobi_pais_id',
                    'value' => $selected_country,
                    'compare' => '=',
                    'type' => 'NUMERIC',
                ],
            ],
        ]);

        foreach ($comisiones as $comision) {
            $pais = get_the_title($selected_country);
            $label = sprintf('%s — %s', $comision->post_title, $pais);
            $selected = selected($selected_comision, $comision->ID, false);

            echo '<option value="' . esc_attr($comision->ID) . '"' . $selected . '>' . esc_html($label) . '</option>';
        }

        echo '</select>';
        echo '<p class="description">' . esc_html__('Solo se muestran comisiones del país seleccionado. Si cambia el país, guarde y vuelva a elegir comisión.', 'gobi-core') . '</p>';
        echo '</td>';
        echo '</tr>';
    }

    /**
     * Render proyecto meta box.
     *
     * @param WP_Post $post Current post object.
     * @return void
     */
    public static function proyecto_meta_box($post)
    {
        wp_nonce_field('gobi_proyecto_meta_save', 'gobi_proyecto_meta_nonce');
        
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="gobi_estado">' . __('Estado del Proyecto', 'gobi-core') . '</label></th>';
        echo '<td><select id="gobi_estado" name="gobi_estado">';
        
        $estados = ['presentado', 'en_comision', 'en_debate', 'votado', 'aprobado', 'archivado'];
        $estado_actual = get_post_meta($post->ID, '_gobi_estado', true);
        
        foreach ($estados as $estado) {
            $selected = selected($estado_actual, $estado, false);
            echo '<option value="' . esc_attr($estado) . '"' . $selected . '>' . esc_html(ucfirst($estado)) . '</option>';
        }
        
        echo '</select></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="gobi_estado_motivo">' . __('Motivo del Cambio de Estado', 'gobi-core') . '</label></th>';
        echo '<td><textarea id="gobi_estado_motivo" name="gobi_estado_motivo" rows="3" cols="50" placeholder="' . esc_attr__('Describa el motivo del cambio de estado...', 'gobi-core') . '">' . esc_textarea(get_post_meta($post->ID, '_gobi_estado_motivo', true)) . '</textarea></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="gobi_expediente">' . __('Número de Expediente', 'gobi-core') . '</label></th>';
        echo '<td><input type="text" id="gobi_expediente" name="gobi_expediente" value="' . esc_attr(get_post_meta($post->ID, '_gobi_expediente', true)) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="gobi_fecha_inicio">' . __('Fecha de Inicio', 'gobi-core') . '</label></th>';
        echo '<td><input type="date" id="gobi_fecha_inicio" name="gobi_fecha_inicio" value="' . esc_attr(get_post_meta($post->ID, '_gobi_fecha_inicio', true)) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="gobi_resumen_ciudadano">' . __('Resumen Ciudadano', 'gobi-core') . '</label></th>';
        echo '<td><textarea id="gobi_resumen_ciudadano" name="gobi_resumen_ciudadano" rows="4" cols="50" placeholder="' . esc_attr__('Escriba un resumen sencillo para ciudadanos...', 'gobi-core') . '">' . esc_textarea(get_post_meta($post->ID, '_gobi_resumen_ciudadano', true)) . '</textarea></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="gobi_texto_base_url">' . __('URL del Texto Base', 'gobi-core') . '</label></th>';
        echo '<td><input type="url" id="gobi_texto_base_url" name="gobi_texto_base_url" value="' . esc_attr(get_post_meta($post->ID, '_gobi_texto_base_url', true)) . '" placeholder="' . esc_attr__('https://...', 'gobi-core') . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="gobi_organo_presentador">' . __('Órgano Presentador', 'gobi-core') . '</label></th>';
        echo '<td><input type="text" id="gobi_organo_presentador" name="gobi_organo_presentador" value="' . esc_attr(get_post_meta($post->ID, '_gobi_organo_presentador', true)) . '" placeholder="' . esc_attr__('Ej: Cámara de Diputados', 'gobi-core') . '" /></td>';
        echo '</tr>';
        
        // Add country selector
        self::render_country_selector($post, 'gobi_pais_id', __('País', 'gobi-core'));
        self::render_comision_selector($post);
        
        echo '</table>';
    }

    /**
     * Render diputado meta box.
     *
     * @param WP_Post $post Current post object.
     * @return void
     */
    public static function diputado_meta_box($post)
    {
        wp_nonce_field('gobi_diputado_meta_save', 'gobi_diputado_meta_nonce');
        
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th><label for="gobi_partido_actual">' . __('Partido Actual', 'gobi-core') . '</label></th>';
        echo '<td><input type="text" id="gobi_partido_actual" name="gobi_partido_actual" value="' . esc_attr(get_post_meta($post->ID, '_gobi_partido_actual', true)) . '" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th><label for="gobi_provincia">' . __('Provincia', 'gobi-core') . '</label></th>';
        echo '<td><input type="text" id="gobi_provincia" name="gobi_provincia" value="' . esc_attr(get_post_meta($post->ID, '_gobi_provincia', true)) . '" /></td>';
        echo '</tr>';
        
        // Add country selector
        self::render_country_selector($post, 'gobi_pais_id', __('País', 'gobi-core'));
        
        echo '</table>';
    }

    /**
     * Render comision meta box.
     *
     * @param WP_Post $post Current post object.
     * @return void
     */
    public static function comision_meta_box($post)
    {
        wp_nonce_field('gobi_comision_meta_save', 'gobi_comision_meta_nonce');
        
        echo '<table class="form-table">';
        
        // Add country selector
        self::render_country_selector($post, 'gobi_pais_id', __('País', 'gobi-core'));
        
        echo '</table>';
    }

    /**
     * Save post meta for CPTs.
     *
     * @param int $post_id Post ID.
     * @return void
     */
    public static function save_post_meta($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save proyecto meta
        if (get_post_type($post_id) === 'gobi_proyecto') {
            if (isset($_POST['gobi_proyecto_meta_nonce']) && wp_verify_nonce($_POST['gobi_proyecto_meta_nonce'], 'gobi_proyecto_meta_save')) {
                // Note: _gobi_estado is handled by Workflow\Manager::change_state() only
                if (isset($_POST['gobi_estado_motivo'])) {
                    update_post_meta($post_id, '_gobi_estado_motivo', sanitize_textarea_field($_POST['gobi_estado_motivo']));
                }
                if (isset($_POST['gobi_expediente'])) {
                    update_post_meta($post_id, '_gobi_expediente', sanitize_text_field($_POST['gobi_expediente']));
                }
                if (isset($_POST['gobi_pais_id'])) {
                    update_post_meta($post_id, '_gobi_pais_id', absint($_POST['gobi_pais_id']));
                }
                if (isset($_POST['gobi_fecha_inicio'])) {
                    update_post_meta($post_id, '_gobi_fecha_inicio', sanitize_text_field($_POST['gobi_fecha_inicio']));
                }
                if (isset($_POST['gobi_resumen_ciudadano'])) {
                    update_post_meta($post_id, '_gobi_resumen_ciudadano', sanitize_textarea_field($_POST['gobi_resumen_ciudadano']));
                }
                if (isset($_POST['gobi_texto_base_url'])) {
                    update_post_meta($post_id, '_gobi_texto_base_url', esc_url_raw($_POST['gobi_texto_base_url']));
                }
                if (isset($_POST['gobi_organo_presentador'])) {
                    update_post_meta($post_id, '_gobi_organo_presentador', sanitize_text_field($_POST['gobi_organo_presentador']));
                }
                if (isset($_POST['gobi_comision_id'])) {
                    $proyecto_pais_id = isset($_POST['gobi_pais_id']) ? absint($_POST['gobi_pais_id']) : 0;
                    $comision_id = absint($_POST['gobi_comision_id']);

                    if (!$comision_id) {
                        delete_post_meta($post_id, '_gobi_comision_id');
                    } else {
                        $comision_pais_id = absint(get_post_meta($comision_id, '_gobi_pais_id', true));

                        if ($proyecto_pais_id && $comision_pais_id === $proyecto_pais_id) {
                            update_post_meta($post_id, '_gobi_comision_id', $comision_id);
                        } else {
                            delete_post_meta($post_id, '_gobi_comision_id');
                        }
                    }
                }
            }
        }

        // Save diputado meta
        if (get_post_type($post_id) === 'gobi_diputado') {
            if (isset($_POST['gobi_diputado_meta_nonce']) && wp_verify_nonce($_POST['gobi_diputado_meta_nonce'], 'gobi_diputado_meta_save')) {
                if (isset($_POST['gobi_partido_actual'])) {
                    update_post_meta($post_id, '_gobi_partido_actual', sanitize_text_field($_POST['gobi_partido_actual']));
                }
                if (isset($_POST['gobi_provincia'])) {
                    update_post_meta($post_id, '_gobi_provincia', sanitize_text_field($_POST['gobi_provincia']));
                }
                if (isset($_POST['gobi_pais_id'])) {
                    update_post_meta($post_id, '_gobi_pais_id', absint($_POST['gobi_pais_id']));
                }
            }
        }

        // Save comision meta
        if (get_post_type($post_id) === 'gobi_comision') {
            if (isset($_POST['gobi_comision_meta_nonce']) && wp_verify_nonce($_POST['gobi_comision_meta_nonce'], 'gobi_comision_meta_save')) {
                if (isset($_POST['gobi_pais_id'])) {
                    update_post_meta($post_id, '_gobi_pais_id', absint($_POST['gobi_pais_id']));
                }
            }
        }
    }
}
