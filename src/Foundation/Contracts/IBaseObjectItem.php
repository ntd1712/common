<?php

namespace Chaos\Foundation\Contracts;

/**
 * Interface IBaseObjectItem
 * @author ntd1712
 */
interface IBaseObjectItem
{
    /**
     * Gets the object instance as an array.
     *
     * @return  array
     */
    public function toArray();

    /**
     * Copies values from a passed in array to the object instance properties.
     *
     * @param   array $array An array of key/value pairs to copy.
     * @return  static
     * @throws  \ReflectionException
     * @throws  \Chaos\Foundation\Exceptions\ConversionException
     */
    public function exchangeArray(array $array);

    /**
     * Copies values from a passed in object to the object instance properties.
     *
     * @param   object $object The passed in object.
     * @return  static
     * @throws  \ReflectionException
     */
    public function exchangeObject($object);
}
