<?php

namespace Chaos\Component\System;

/**
 * Class Collection
 * @author ntd1712
 */
class Collection extends \ArrayObject implements Contract\ICollection
{
    use CollectionAware, ObjectAware;
}
