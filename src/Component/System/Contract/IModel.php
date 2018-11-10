<?php

namespace Chaos\Component\System\Contract;

/**
 * Interface IModel
 * @author ntd1712
 */
interface IModel extends IObject
{
    /**
     * Gets the properties of the model object.
     *
     * @return  array
     * @see     get_object_vars()
     */
    public function toArray();
}
