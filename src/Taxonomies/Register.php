<?php

namespace Gobi\Taxonomies;

/**
 * Taxonomy registration for GOBi.
 *
 * Handles registration of all GOBi-specific taxonomies
 * for the legislative domain: temas, partidos
 */
class Register
{
    /**
     * Initialize taxonomy registration.
     *
     * @return void
     */
    public static function init()
    {
        add_action('init', [__CLASS__, 'register_tema_taxonomy']);
        add_action('init', [__CLASS__, 'register_partido_taxonomy']);
    }

    /**
     * Register Tema taxonomy.
     *
     * @return void
     */
    public static function register_tema_taxonomy()
    {
        $labels = [
            'name' => __('Temas', 'gobi-core'),
            'singular_name' => __('Tema', 'gobi-core'),
            'menu_name' => __('Temas Políticos', 'gobi-core'),
            'all_items' => __('Todos los Temas', 'gobi-core'),
            'edit_item' => __('Editar Tema', 'gobi-core'),
            'view_item' => __('Ver Tema', 'gobi-core'),
            'update_item' => __('Actualizar Tema', 'gobi-core'),
            'add_new_item' => __('Agregar Nuevo Tema', 'gobi-core'),
            'new_item_name' => __('Nombre del Nuevo Tema', 'gobi-core'),
            'parent_item' => __('Tema Padre', 'gobi-core'),
            'parent_item_colon' => __('Tema Padre:', 'gobi-core'),
            'search_items' => __('Buscar Temas', 'gobi-core'),
            'popular_items' => __('Temas Populares', 'gobi-core'),
            'separate_items_with_commas' => __('Separar temas con comas', 'gobi-core'),
            'add_or_remove_items' => __('Agregar o quitar temas', 'gobi-core'),
            'choose_from_most_used' => __('Elegir de los más usados', 'gobi-core'),
            'not_found' => __('No se encontraron temas.', 'gobi-core'),
            'no_terms' => __('Sin temas', 'gobi-core'),
            'items_list_navigation' => __('Navegación de lista de temas', 'gobi-core'),
            'items_list' => __('Lista de temas', 'gobi-core'),
        ];

        $args = [
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => false,
            'show_in_rest' => true,
            'rest_base' => 'temas',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        ];

        register_taxonomy('gobi_tema', ['gobi_proyecto'], $args);
    }

    /**
     * Register Partido taxonomy.
     *
     * @return void
     */
    public static function register_partido_taxonomy()
    {
        $labels = [
            'name' => __('Partidos', 'gobi-core'),
            'singular_name' => __('Partido', 'gobi-core'),
            'menu_name' => __('Partidos Políticos', 'gobi-core'),
            'all_items' => __('Todos los Partidos', 'gobi-core'),
            'edit_item' => __('Editar Partido', 'gobi-core'),
            'view_item' => __('Ver Partido', 'gobi-core'),
            'update_item' => __('Actualizar Partido', 'gobi-core'),
            'add_new_item' => __('Agregar Nuevo Partido', 'gobi-core'),
            'new_item_name' => __('Nombre del Nuevo Partido', 'gobi-core'),
            'parent_item' => __('Partido Padre', 'gobi-core'),
            'parent_item_colon' => __('Partido Padre:', 'gobi-core'),
            'search_items' => __('Buscar Partidos', 'gobi-core'),
            'popular_items' => __('Partidos Populares', 'gobi-core'),
            'separate_items_with_commas' => __('Separar partidos con comas', 'gobi-core'),
            'add_or_remove_items' => __('Agregar o quitar partidos', 'gobi-core'),
            'choose_from_most_used' => __('Elegir de los más usados', 'gobi-core'),
            'not_found' => __('No se encontraron partidos.', 'gobi-core'),
            'no_terms' => __('Sin partidos', 'gobi-core'),
            'items_list_navigation' => __('Navegación de lista de partidos', 'gobi-core'),
            'items_list' => __('Lista de partidos', 'gobi-core'),
        ];

        $args = [
            'labels' => $labels,
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => false,
            'show_in_rest' => true,
            'rest_base' => 'partidos',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        ];

        register_taxonomy('gobi_partido', ['gobi_diputado'], $args);
    }
}
