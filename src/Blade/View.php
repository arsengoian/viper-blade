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

/**
 * Class View
 * Represents a view using Blade template engine
 * @package Blade
 */
class View implements Viewable
{
    private static $vars = [];
    private static $errView = FALSE;
    private static $errVar = 'e';

    private static function cacheDir(): string {
        return root().'/storage/cache/blade';
    }
    private static function viewDir(): string {
        return root().'/templates';
    }

    private $parsed;

    /**
     * Use this from filter to propagate variables that are to be visible in all views
     * General scope variables are overwritten by view-specific ones
     * @param array $vars
     */
    public static function propagateVars(array $vars): void {
        static::$vars = array_merge(static::$vars, $vars);
    }


    /**
     * Use this from filter to bind an error view name
     * The view will be passed
     * @param string $viewName the name of the view, without "blade.php"
     * @param string $errVar the name of the exception varilable
     */
    public static function bindErrorView(string $viewName = 'error', string $viewVar = 'e'): void {
        self::$errView = $viewName;
        self::$errVar = $viewVar;
    }



    public function __construct (string $viewname, array $data = [])
    {
        if (!is_dir(static::cacheDir()))
            Util::recursiveMkdir(static::cacheDir());
        if (!is_dir(static::viewDir()))
            Util::recursiveMkdir(static::viewDir());

        try {
            $this -> parse($viewname, array_merge(self::$vars, $data));
        } catch(\Exception $e) {
            if (static::$errView && file_exists(static::viewDir().'/'.self::$errView.'.blade.php'))
                return $this -> parse(self::$errView, array_merge(self::$vars, [self::$errVar => $e]));
            else throw $e;
        }
    }

    private function parse(string $viewname, array $data) {
        $blade = new Blade(self::viewDir(), self::cacheDir());
        $this -> parsed = $blade -> view() -> make($viewname, $data) -> render();
    }

    public function flush (): string
    {
        return $this -> parsed;
    }
}