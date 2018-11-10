<?php

namespace Chaos\Foundation;

/**
 * Class AbstractBaseObjectCollection
 * @author ntd1712
 */
abstract class AbstractBaseObjectCollection extends AbstractBaseObject implements Contracts\IBaseObjectCollection
{
    use Traits\OrderedMapAwareTrait;

    /**
     * @JMS\Serializer\Annotation\Exclude()
     */
    private $elements;

    /**
     * Constructor.
     *
     * @param   array $elements An array containing the elements of the collection.
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return  string
     */
    public function __toString()
    {
        return get_called_class();
    }

    /**
     * {@inheritdoc} Required by parent (abstract).
     * Proxies to {@link toArray()}.
     *
     * @return  array
     */
    public function getArrayCopy()
    {
        return $this->elements;
    }

    /**
     * Creates a new object instance from the specified elements.
     *
     * @param   array $elements The elements.
     * @return  static
     */
    public static function createFrom(array $elements)
    {
        return new static($elements);
    }
}
