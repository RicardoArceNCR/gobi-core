<?php

namespace Gobi\Relations;

class Country
{
    public static function get_country_id($post_id)
    {
        return (int) get_post_meta($post_id, '_gobi_pais_id', true);
    }

    public static function set_country_id($post_id, $country_id)
    {
        return update_post_meta($post_id, '_gobi_pais_id', absint($country_id));
    }

    public static function belongs_to_country($post_id, $country_id)
    {
        return self::get_country_id($post_id) === absint($country_id);
    }
}
