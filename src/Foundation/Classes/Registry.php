<?php

namespace Chaos\Foundation\Classes;

/**
 * Class Registry (currently not in use)
 * @author ntd1712
 */
class Registry
{
    /**
     * @internal
     */
    private static $items = [];

    /**
     * Private constructor.
     */
    final private function __construct()
    {
        //
    }

    /**
     * Clears all items in Registry.
     */
    public static function clear()
    {
        self::$items = [];
    }

    /**
     * Retrieves an item from Registry.
     *
     * @param   string $name The item name.
     * @return  mixed
     */
    public static function get($name)
    {
        return isset(self::$items[$name]) ? self::$items[$name] : null;
    }

    /**
     * Checks whether an item exists.
     *
     * @param   string $name The item name.
     * @return  boolean
     */
    public static function has($name)
    {
        return isset(self::$items[$name]) || in_array($name, self::$items, true);
    }

    /**
     * Removes an item from Registry.
     *
     * @param   string $name The item name.
     */
    public static function remove($name)
    {
        if (isset(self::$items[$name]) && is_object(self::$items[$name])
            && method_exists(self::$items[$name], '__destruct')) {
            self::$items[$name]->__destruct();
        }

        unset(self::$items[$name]);
    }

    /**
     * Adds an item into Registry.
     *
     * @param   string $name The name of the requested item.
     * @param   mixed $value The value of the requested item.
     * @param   boolean $overwrite Overwrite item if the given name already exists.
     */
    public static function set($name, $value, $overwrite = false)
    {
        if (!isset(self::$items[$name]) || $overwrite) {
            self::$items[$name] = $value;
        }
    }
}
