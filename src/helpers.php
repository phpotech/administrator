<?php

use Keyhunter\Administrator\Exception;

if ( ! function_exists('keyhunter_administrator_version')) {
    
    /** get administrator version */
    function keyhunter_administrator_version () {
        return '1.0.6';
    }
}

if (! function_exists('output_boolean'))
{
    function output_boolean($row, $field = 'active')
    {
        if (! isset($row->{$field}))
        {
            throw new Exception(sprintf('Unknown property %s in class %s', $field, get_class($row)));
        }

        return ($row->{$field} ? '<i class="fa fa-fw fa-check" style="color: #605ca8;"></i>' : '');
    }

    /**
     * @param mixed Eloquent|string $row - Eloquent model or path to image
     * @param null $field = model column, contains path to image
     * @param array $attributes
     * @return string
     * @throws Exception
     */
    function output_image($row, $field = null, array $attributes = [])
    {
        if (is_object($row) && is_string($field))
        {
            if (! isset($row->{$field}))
            {
                throw new Exception(sprintf('Unknown property %s in class %s', $field, get_class($row)));
            }

            $path = $row->{$field};
        }
        else if (is_string($row))
        {
            $path = $row;
        }

        $attributes = html_attributes($attributes);
        return ($path ? '<img src="'.$path.'" '. $attributes .' />' : '');
    }

    function html_attributes(array $attributes = [])
    {
        $out = [];
        foreach ($attributes as $key => $value)
        {
            // transform
            if (is_bool($value))
            {
                $out[] = "{$key}=\"{$key}\"";
            }
            else if (is_numeric($key))
            {
                $out[] = "{$value}=\"{$value}\"";
            }
            else
            {
                $value = htmlspecialchars($value);
                $out[] = "{$key}=\"{$value}\"";
            }
        }
        return join(" ", $out);
    }
};

if (! function_exists('column_element'))
{
    function column_element($title = '', $standalone = false, $output = null)
    {
        return compact('title', 'standalone', 'output');
    }

    function column_group($label = '', array $elements = [])
    {
        $group = [];
        foreach ($elements as $key => $value)
        {
            if (is_numeric($key) && is_string($value))
            {
                $group[$value] = column_element($value);
            }
            else
            {
                $group[$key] = call_user_func_array('column_element', $value);
            }
        }

        return [
            'label'    => $label,
            'elements' => $group
        ];
    }

    /**
     * Add permission checker to view column
     *
     * @param $permission
     * @return array
     */
    function column_restricted($permission)
    {
        return [
            'permission' => $permission
        ];
    }
}

if (! function_exists('filter_text'))
{
    function filter_text($label = '', Closure $query = null)
    {
        return [
            'type' => 'text',
            'label' => $label,
            'query' => $query
        ];
    }

    function filter_hidden(Closure $query = null)
    {
        return [
            'type' => 'hidden',
            'query' => $query
        ];
    }
	
	/**
     * Generate NumberRange filter config
     *
     * @param string $label
     * @param array $params
     * @param Closure $query
     * @return array
     */
    function filter_number_range($label = '', array $params, Closure $query = null)
    {
        return [
            'type' => 'number_range',
            'label' => $label,
            'params' => $params,
            'query' => $query
        ];
    }

    /**
     * Generate DateRange filter config
     *
     * @param string $label
     * @param mixed array|callable $options
     * @param callable $query
     * @return array
     * @throws Exception
     */
    function filter_select($label = '', $options, Closure $query = null)
    {
        if (! (is_array($options) || is_callable($options)))
        {
            trigger_error('Currently only Array or Closure can be provided as $options list', E_USER_ERROR);
        }

        return [
            'type' => 'select',
            'label' => $label,
            'options' => $options,
            'query' => $query
        ];
    }

    /**
     * Generate DateRange filter config
     *
     * @param string $label
     * @param callable $query
     * @return array
     */
    function filter_daterange($label = '', Closure $query = null)
    {
        return [
            'label'=> $label,
            'type' => 'daterange',
            'query' => $query
        ];
    }

    /**
     * Generate Date filter config
     *
     * @param string $label
     * @param callable $query
     * @return array
     */
    function filter_date($label = '', Closure $query = null)
    {
        return [
            'label'=> $label,
            'type' => 'date',
            'query' => $query
        ];
    }
}

if (! function_exists('form_key'))
{
    function input($type = 'text', $label = '', array $attributes = [])
    {
        $attributes = [
                'type'      => $type,
                'label'     => $label
            ] + $attributes;

        return $attributes;
    }

    function form_key($label = '')
    {
        return input('key', $label, []);
    }

    function form_hidden($attributes)
    {
        return input('hidden', $attributes);
    }

    function form_text($label = '', array $attributes = [])
    {
        return input('text', $label, $attributes);
    }

    function form_textarea($label = '', array $attributes = [])
    {
        return input('textarea', $label, $attributes);
    }

    function form_tinymce($label = '', array $attributes = [])
    {
        return input('tinymce', $label, $attributes);
    }

    function form_ckeditor($label = '', array $attributes = [])
    {
        return input('ckeditor', $label, $attributes);
    }

    function form_wysi_html5($label = '', array $attributes = [])
    {
        return input('wysihtml5', $label, $attributes);
    }

    function form_tel($label = '', array $attributes = [])
    {
        return input('tel', $label, $attributes);
    }

    function form_email($label = '', array $attributes = [])
    {
        return input('email', $label, $attributes);
    }

    function form_number($label = '', array $attributes = [])
    {
        return input('number', $label, $attributes);
    }

    /**
     * Generate Select input
     *
     * @param string $label
     * @param array|callable|string $options - string options should have relation format: table.field
     * @param bool $multiple
     * @param array $attributes
     * @return array
     */
    function form_select($label = '', $options = [], $multiple = false, array $attributes = [])
    {
        $default = [
            'type'      => 'select',
            'label'     => $label,
            'options'   => $options
        ];

        if ($multiple)
        {
            $default['multiple'] = "multiple";
        }

        return $default + $attributes;
    }

    /**
     * Build boolean [checkbox] input
     *
     * @param string $label
     * @param array $attributes
     * @return array
     */
    function form_boolean($label = '', array $attributes = [])
    {
        return input('bool', $label, $attributes);
    }

    /**
     * Build date field
     *
     * @param string $label
     * @param array $attributes
     * @return array
     */
    function form_date($label = '', array $attributes = [])
    {
        return input('date', $label, $attributes);
    }

    /**
     * Build file input
     *
     * @param string $label
     * @param $location
     * @param string $naming
     * @return array
     */
    function form_file($label = '', $location, $naming = 'original')
    {
        return [
            'type' => 'file',
            'location' => $location,
            'naming' => $naming
        ];
    }

    /**
     * Build image input
     *
     * @param string $label
     * @param string $location
     * @param array $sizes
     * @return array
     */
    function form_image($label = '', $location = '', $sizes = [])
    {
        return [
            'type'      => 'image',
            'location'  => $location,
            'sizes'     => $sizes
        ];
    }

    /**
     * Sometimes we need to save specific size to specific table field
     * this option allows you to do that
     *
     * @example: imagine that we have $sizes = ['original' => '800x800', 'image' => '400x400', 'side' => '200x200', 'small' => '100x100']
     *  with $aliases = ['image' => 'small', 'side' => 'image', 'small' => 'side']
     *  the @small sized image will be saved in `image` field,
     *      @image sized image => `side` field,
     *      @side sized image => `small` field,
     *  etc...
     *
     * @param array $aliases
     * @return array
     */
    function image_aliases(array $aliases = [])
    {
        return [
            'alias' => $aliases
        ];
    }

    /**
     * Set field description [optional]
     *
     * @param string $description
     * @return array
     */
    function has_description($description = '')
    {
        return ['description' => $description];
    }

    /**
     * Make field translatable
     *
     * @uses keyhunter/translatable package
     * @uses keyhunter/multilingual package
     *
     * @return array
     */
    function translatable()
    {
        return ['translatable' => true];
    }

    /**
     * Add description for field
     *
     * @uses keyhunter/administrator package
     *
     * @return array
     */
    function description($description)
    {
        return ['description' => $description];
    }

    /**
     * Set relation to field.
     * @Select uses relation to fetch options list from table fields, like enum
     *  @example: from users.role of type enum('member', 'admin', 'manager') => will fetch [member, admin, manager]
     * @Image uses relation to save items in related table,
     *  @example: users && images.image [images.user_id => user.id]
     * @Text uses relation to fetch value from related table,
     *  @example: users && user_detail.phone [user_detail.user_id => users.id]
     *
     * @param $relation
     * @return array
     */
    function has_relation($relation)
    {
        if (! is_string($relation))
            trigger_error('Relation should be string of format table.field');

        return ["relation" => $relation];
    }

    /**
     * has_relation alias
     *
     * @param $relation
     * @return array
     */
    function file_save_to($relation)
    {
        return has_relation($relation);
    }
}

/**
 * Fetch a flattened array of a nested array element.
 * 
 * @since laravel 5.2 was removed from \Illuminate\Database\Eloquent\Collection::class
 * @param  array   $array
 * @param  string  $key
 * @return array
 */
if ( ! function_exists('array_fetch')) {
    
    function array_fetch($array, $key) {
        foreach (explode('.', $key) as $segment) {
            $results = array();
            
            foreach ($array as $value) {
                $value = (array) $value;
                
                $results[] = $value[$segment];
            }
            
            $array = array_values($results);
        }
        
        return array_values($results);
    }
}

if (!function_exists('settings')) {
    function settings(){
        return (new \Keyhunter\Administrator\Model\Settings);
    }
}

if ( ! function_exists('keyhunter_administrator_copyright')) {
    
    /** render copyright. */
    function keyhunter_administrator_copyright() {
        return sprintf(
            '<strong>Copyright © %s <a href="%s">Powered by %s</a>.</strong> All rights reserved.',
                config('administrator.copyright.years', '2015-2016'),
                config('administrator.copyright.poweredby', 'https://github.com/keyhunter'),
                config('administrator.copyright.author', 'Keyhunter')
            );
    }
}