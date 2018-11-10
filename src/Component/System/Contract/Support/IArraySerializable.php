<?php

namespace Chaos\Component\System\Contract\Support;

/**
 * Interface IArraySerializable
 * @author ntd1712
 *
 * @see \Zend\Stdlib\ArraySerializableInterface
 */
interface IArraySerializable
{
    /**
     * Exchanges internal values from provided array.
     *
     * @param   object|array $data The data.
     * @return  mixed
     */
    public function exchangeArray($data);

    /**
     * Returns an array representation of the object.
     *
     * @return  array
     */
    public function getArrayCopy();
}
