<?php

namespace GiveDoubleTheDonation\Addon;

use InvalidArgumentException;

/**
 * Helper class responsible for loading add-on views.
 *
 * @package     GiveDoubleTheDonation\Addon\Helpers
 * @copyright   Copyright (c) 2020, GiveWP
 */
class View
{

    /**
     * @since 1.0.0
     *
     * @param array  $templateParams Arguments for template.
     * @param bool   $echo
     *
     * @param string $view
     *
     * @return string|void
     * @throws InvalidArgumentException if template file not exist
     *
     */
    public static function load($view, $templateParams = [], $echo = false)
    {
        $template = GIVE_DTD_DIR . 'src/DoubleTheDonation/resources/views/' . $view . '.php';

        if ( ! file_exists($template)) {
            throw new InvalidArgumentException("View template file {$template} not exist");
        }

        ob_start();
        extract($templateParams);
        include $template;
        $content = ob_get_clean();

        if ( ! $echo) {
            return $content;
        }

        echo $content;
    }

    /**
     * @since 1.0.0
     *
     * @param array  $vars
     *
     * @param string $view
     */
    public static function render($view, $vars = [])
    {
        static::load($view, $vars, true);
    }
}
