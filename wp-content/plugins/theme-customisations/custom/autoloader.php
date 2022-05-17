<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ONG Autoloader.
 *
 * @class          ONG_Autoloader
 * @version        2.3.0
 * @package        ONG/Classes
 * @category       Class
 *
 */
class ONG_Autoloader
{

    /**
     * Path to the includes directory.
     *
     * @var string
     */
    private $include_path = '';

    /**
     * The Constructor.
     */
    public function __construct()
    {
        if (function_exists("__autoload")) {
            spl_autoload_register("__autoload");
        }

        spl_autoload_register([$this, 'autoload']);

        $this->include_path = untrailingslashit(plugin_dir_path(__FILE__));
    }

    /**
     * Auto-load WC classes on demand to reduce memory consumption.
     *
     * @param string $class
     */
    public function autoload($class)
    {
        $path = '';
        if (strpos(strtolower($class), 'ong_addon_') === 0 && preg_match('~^ong_addon_([^_]+)(_.+)?$~is', $class, $matches)) {
            $path = $this->include_path . '/addons/'.self::camelToSnake($matches[1]).'/';
        }
        //$class = strtolower($class);
        //$path = strtolower($path);
        if (empty($path) || (!$this->loadFile($path, $class) && stripos($class, 'ong_') === 0)) {
            $path = $this->include_path . '/classes/';
            $this->loadFile($path, $class);
        }
    }

    /**
     * Include a class file.
     *
     * @param  string $path
     *
     * @return bool successful or not
     */
    private function loadFile($path, $class)
    {
        $class_file = $path . $this->getClassFileNameFromClass($class);
        if ($class_file && is_readable($class_file)) {
            include_once($class_file);
            return true;
        }
        $interface_file = $path . $this->getInterfaceFileNameFromClass($class);
        if ($interface_file && is_readable($interface_file)) {
            include_once($interface_file);
            return true;
        }
        $trait_file = $path . $this->getTraitFileNameFromClass($class);
        if ($trait_file && is_readable($trait_file)) {
            include_once($trait_file);
            return true;
        }
        return false;
    }

    /**
     * Take a class name and turn it into a file name.
     *
     * @param  string $class
     *
     * @return string
     */
    private function getClassFileNameFromClass($class)
    {
        $class = self::camelToSnake($class);
        return 'class-' . str_replace('_', '-', $class) . '.php';
    }

    /**
     * Take a class name and turn it into a file name.
     *
     * @param  string $class
     *
     * @return string
     */
    private function getInterfaceFileNameFromClass($class)
    {
        $class = self::camelToSnake($class);
        return str_replace('_', '-', $class) . '.php';
    }

    private function getTraitFileNameFromClass( $class ) {
        $class = self::camelToSnake($class);
        return str_replace('_', '-', $class) . '-trait.php';
    }

    public static function camelToSnake($input) {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $input));
    }
}

new ONG_Autoloader();
