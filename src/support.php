<?php
/**
 * Created by PhpStorm.
 * User: Арсен
 * Date: 11.02.2018
 * Time: 16:49
 */

use Blade\View;

if (!function_exists('blade')) {
    function blade(string $viewname, array $data) {
        return new View($viewname, $data);
    }
}