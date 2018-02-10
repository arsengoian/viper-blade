<?php
/**
 * Created by PhpStorm.
 * User: Арсен
 * Date: 10.02.2018
 * Time: 23:25
 */

namespace Blade;


use Philo\Blade\Blade;
use Viper\Core\Viewable;
use Viper\Support\Libs\Util;
use WideImage\Exception\Exception;

/**
 * Class View
 * Represents a view using Blade template engine
 * @package Blade
 */
class View implements Viewable
{
    private static $vars;

    private const CACHE_DIR = ROOT.'/storage/cache/blade';
    private const VIEW_DIR = ROOT.'/templates';

    private $parsed;

    /**
     * Use this from filter to propagate variables that are to be visible in all views
     * General scope variables are overwritten by view-specific ones
     * @param array $vars
     */
    public static function propagateVars(array $vars): void {
        static::$vars = array_merge(static::$vars, $vars);
    }

    public function __construct (string $viewname, array $data = [])
    {
        if (!is_dir(static::CACHE_DIR))
            Util::recursiveMkdir(static::CACHE_DIR);
        if (!is_dir(static::VIEW_DIR))
            Util::recursiveMkdir(static::VIEW_DIR);

        try {
            $this -> parse($viewname);
        } catch(Exception $e) {
            var_dump($e); // TODO
        }
    }

    private function parse(string $viewname) {
        $blade = new Blade(self::VIEW_DIR, self::CACHE_DIR);
        $this -> parsed = $blade -> view() -> make($viewname) -> render();
    }

    public function flush (): string
    {
        return $this -> parsed;
    }
}