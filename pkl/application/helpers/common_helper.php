<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('dd')) {
    /**
     * dd (Dump Die)
     *
     * @param	mixed
     * @return	void
     */
    function dd(...$args): void
    {
        foreach ($args as $x) {
            echo '<pre>';
            var_dump($x);
            echo '</pre>';
        }
        exit(1);
    }
}

if (!function_exists('is_active')) {
    /**
     * Check if the current menu, sub-menu, or header is active based on URL segment
     *
     * @param string $menu The menu to check against the URL segment
     * @param int $segment The URL segment to check (default: 1)
     * @param string $activeClass The class to return if active (default: 'active')
     * @param string $inactiveClass The class to return if inactive (default: '')
     * @return string
     */
    function is_active(string $menu, int $segment = 1, string $activeClass = 'active', string $inactiveClass = ''): string
    {
        $CI = &get_instance();
        $current_url = $CI->uri->segment($segment);
        return ($current_url === $menu) ? $activeClass : $inactiveClass;
    }
}
