<?php

namespace Chaos\Foundation;

/**
 * Class AbstractBaseObjectItem
 * @author ntd1712
 */
abstract class AbstractBaseObjectItem extends AbstractBaseObject implements Contracts\IBaseObjectItem
{
    use BaseObjectItemTrait, Traits\EntityIdentifierAwareTrait, Traits\EventAwareTrait;

    const ON_AFTER_EXCHANGE_DATA = 'onAfterExchangeData';

    /**
     * {@inheritdoc} Required by parent (abstract).
     * Gets the object instance as an array of arrays.
     *
     * @return  array
     */
    public function getArrayCopy()
    {
        $visited[spl_object_hash($this)] = $visited[get_called_class()] = true;
        $vars = get_object_vars($this);

        foreach ($vars as &$value) {
            $value = $this->objectToArray($value, 0, $visited); // e.g. User: depth 0 > UserRole: 1 > User: 2
        }
        unset($value);

        return $vars;
    }

    /**
     * {@inheritdoc} Required by interface IBaseObjectItem.
     *
     * @return  array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * {@inheritdoc} Required by interface IBaseObjectItem.
     *
     * @param   array $array An array of key/value pairs to copy.
     * @param   integer $depth [optional] The depth that we go into; defaults to -1.
     * @param   array $visited [optional] An array of visited objects; used to prevent cycling.
     * @return  static
     */
    public function exchangeArray(array $array, $depth = -1, &$visited = [])
    {
        if (empty($array) || CHAOS_MAX_RECURSION_DEPTH < ++$depth) {
            return $this;
        }

        // $visited[get_called_class()] = ['object' => &$this, 'depth' => $depth];
        $properties = reflect($this)->getProperties();

        foreach ($properties as $property) {
            if ($property->isStatic()
                || false !== strpos($docComment = $property->getDocComment(), CHAOS_ANNOTATION_IGNORE)
            ) {
                unset($array[$property->name]);
                continue;
            }

            // check if given name not exist in $array; to parse such values like 'now', etc. (for `0 === $depth` only)
            if (!array_key_exists($property->name, $array)) {
                if (empty($depth)) {
                    $property->setAccessible(true);
                    $array[$property->name] = $property->getValue($this);
                } else {
                    continue;
                }
            } else {
                $property->setAccessible(true);
            }

            // switch...
            $types = $this->parsePropertyDataType($property);
            $value = $array[$property->name];

            if ($types['is_scalar']) {
                if ($types['is_collection']) {
                    $value = Types\Type::getType(Types\Type::SIMPLE_ARRAY_TYPE)
                        ->__setConfig($this->__getConfig())
                        ->convertToPHPValue($value);
                    $type0 = Types\Type::getType(strtolower($types[0]))
                        ->__setConfig($this->__getConfig());

                    foreach ($value as &$v) {
                        $v = $type0->convertToPHPValue($v);
                    }
                    unset($v);
                } else {
                    $value = Types\Type::getType(strtolower($types[0]))
                        ->__setConfig($this->__getConfig())
                        ->convertToPHPValue($value);
                }
            /* TODO
                // do we have any defined filters & validators?
                // if (false === strpos($docComment, CHAOS_ANNOTATION_IGNORE_RULES)) {
                //     $this->addRules($property);
                // }
            } else if (isset($visited[$types[0]]) && $visited[$types[0]]['depth'] !== $depth) { // check cyclic refs
                if ($types['is_collection']) {
                    $value = $this->acceptVisitor($visited[$types[0]]['object'], $types[1]);
                } else {
                    $value = $visited[$types[0]]['object'];
                }
            */
            } else if (is_array($value) && class_exists($types[0])) {
                /** @var self|self[]|\Traversable $obj
                  * @var self $item */
                $obj = $types['is_collection'] ? $types[1] : null;

                if (!empty($value) && false === strpos($docComment, CHAOS_ANNOTATION_IGNORE_DATA)) {
                    if (is_subclass_of($types[0], CHAOS_BASE_OBJECT_ITEM_INTERFACE)) {
                        if ($types['is_collection']) {
                            $method = method_exists($obj, 'add') ? 'add' : 'append';
                            $isMulti = is_array($value[$firstKey = key($value)]) || is_object($value[$firstKey]);

                            if (!$isMulti) {
                                $value = [$value];
                            }

                            if (0 === iterator_count($obj)) {
                                foreach ($value as $v) {
                                    $item = new $types[0];
                                    is_object($v)
                                        ? $item->exchangeObject($v)
                                        : $item->exchangeArray($v, $depth, $visited);
                                    $this->acceptVisitor($item, $obj, $method);
                                }
                            } else {
                                // TODO: check 'm again
                                $identifier = array_flip($this->getEntityIdentifier());
                                $tmp = [];

                                foreach ($obj as $k => $v) {
                                    if (is_object($v)) {
                                        if ($v instanceof Contracts\IBaseObjectItem) {
                                            $v = $v->toArray();
                                        } else {
                                            $v = get_object_vars($v);
                                        }
                                    }

                                    if ($v = array_intersect_key($v, $identifier)) {
                                        $tmp[$k] = $v;
                                    }
                                }

                                foreach ($value as $v) {
                                    if (is_object($v)) {
                                        $v = get_object_vars($v);
                                    }

                                    if (($v = array_intersect_key($v, $identifier))
                                        && false !== ($k = array_search($v, $tmp))
                                    ) {
                                        if ($obj[$k] instanceof Contracts\IBaseObjectItem) {
                                            $obj[$k]->exchangeArray($v, $depth, $visited);
                                        } else {
                                            foreach ($obj[$k] as $key => $val) {
                                                if (array_key_exists($key, $v)) {
                                                    $obj[$k]->$key = $v[$key];
                                                }
                                            }
                                        }
                                    } else {
                                        $item = new $types[0];
                                        $item->exchangeArray($v, $depth, $visited);
                                        $this->acceptVisitor($item, $obj, $method);
                                    }
                                }
                            }
                        } else {
                            $obj = new $types[0];
                            $obj->exchangeArray($value, $depth, $visited);
                        }
                    } else { // unknown class, use a kind of default object instance
                        try {
                            if ($types['is_collection']) {
                                $item = new $types[0]($value);
                                $this->acceptVisitor($item, $obj);
                            } else {
                                $obj = new $types[0]($value);
                            }
                        } catch (\Exception $ex) {
                            $obj = null;
                        }
                    }
                }

                $value = $obj;
            }

            $property->setValue($this, $value); // set our new value (if any)
            unset($array[$property->name]);     // for next loop
        }

        return $this->trigger(self::ON_AFTER_EXCHANGE_DATA);
    }

    /**
     * {@inheritdoc} Required by interface IBaseObjectItem.
     *
     * @param   object $object The passed in object.
     * @return  static
     */
    public function exchangeObject($object)
    {
        foreach ($this as $property => $value) {
            if (property_exists($object, $property)) {
                $this->$property = $object->$property;
            }
        }

        return $this->trigger(self::ON_AFTER_EXCHANGE_DATA);
    }

    // <editor-fold desc="MAGIC METHODS" defaultstate="collapsed">

    /**
     * @param   string $name The name of the property being interacted with.
     * @return  mixed
     * @throws  \BadMethodCallException
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        $getter = 'get' . str_replace('_', '', $name);

        if (method_exists($this, $getter)) {
            return $this->$getter();
        }

        throw new \BadMethodCallException(
            sprintf(
                '"%s" does not have a callable "%s" getter method which must be defined',
                $name,
                'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)))
            )
        );
    }

    /**
     * @param   string $name The name of the property being interacted with.
     * @param   mixed $value The value the $name'ed property should be set to.
     * @return  void
     * @throws  \BadMethodCallException
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;

            return;
        }

        $setter = 'set' . str_replace('_', '', $name);

        if (method_exists($this, $setter)) {
            $this->$setter($value);

            return;
        }

        throw new \BadMethodCallException(
            sprintf(
                '"%s" does not have a callable "%s" ("%s") setter method which must be defined',
                $name,
                'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name))),
                $setter
            )
        );
    }

    /**
     * @param   string $name The name of the property being interacted with.
     * @return  boolean
     */
    public function __isset($name)
    {
        try {
            return null !== $this->__get($name);
        } catch (\BadMethodCallException $ex) {
            return false;
        }
    }

    /**
     * @param   string $name The name of the property being interacted with.
     * @return  void
     * @throws  \InvalidArgumentException
     */
    public function __unset($name)
    {
        try {
            $this->__set($name, null);
        } catch (\BadMethodCallException $ex) {
            throw new \InvalidArgumentException(
                'The class property $' . $name . ' cannot be unset as NULL is an invalid value for it',
                0,
                $ex
            );
        }
    }

    /**
     * Returns a string representation of this object.
     *
     * @return  string
     */
    public function __toString()
    {
        return get_called_class();
    }

    // </editor-fold>

    // <editor-fold desc="STATIC METHODS" defaultstate="collapsed">

    /**
     * Creates a new object instance from the specified array.
     *
     * @param   array $array The array.
     * @param   array|\ArrayAccess|\Symfony\Component\DependencyInjection\ContainerInterface $container [optional]
     *          Should be a <tt>ContainerBuilder</tt> instance.
     * @param   array|\ArrayAccess|\M1\Vars\Vars $config [optional] Should be a <tt>Vars</tt> instance.
     * @return  static
     */
    public static function createFrom(array $array, $container = [], $config = null)
    {
        $instance = new static;
        $instance->__setContainer($container)->__setConfig($config);
        $instance->exchangeArray($array);

        return $instance;
    }

    // </editor-fold>
}
