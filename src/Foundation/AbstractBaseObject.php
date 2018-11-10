<?php

namespace Chaos\Foundation;

/**
 * Class AbstractBaseObject
 * @author ntd1712
 */
abstract class AbstractBaseObject implements Contracts\IBaseObject
{
    use Traits\ConfigAwareTrait, Traits\ContainerAwareTrait;

    /**
     * Gets a copy of an array.
     *
     * @return  array
     */
    abstract public function getArrayCopy();

    /**
     * {@inheritdoc} Required by interface JsonSerializable.
     *
     * @return  mixed
     */
    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }
}
