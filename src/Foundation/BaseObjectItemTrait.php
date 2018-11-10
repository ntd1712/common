<?php

namespace Chaos\Foundation;

/**
 * Class BaseObjectItemTrait
 * @author ntd1712
 */
trait BaseObjectItemTrait
{
    /**
     * Parses the property type.
     *
     * @param   \ReflectionProperty $property The property.
     * @return  array
     * @throws  \ReflectionException
     */
    private function parsePropertyDataType(\ReflectionProperty $property)
    {
        $getter = 'get' . $property->name . 'DataType'; // check if getXyzDataType() exists

        if (method_exists($this, $getter)) {
            $types = @call_user_func([$this, $getter]);

            if (!is_array($types)) {
                $types = [$types];
            }
        } else {
            $docComment = $property->getDocComment();

            if (false !== strpos($docComment, '@var')) {
                // e.g. @var \T        -> ['T']
                //      @var \T[]      -> ['T', '']
                //      @var array<\T> -> ['array', 'T']
                //      @var \Array\Collection<\T> -> ['Array\Collection', 'T']
                preg_match(CHAOS_MATCH_VAR, $docComment, $types);
            } else if (false !== strpos($docComment, 'targetEntity')) {
                // e.g. @OneToMany(targetEntity="\T") -> [OneToMany, 'T'] -> [DOCTRINE_ARRAY_COLLECTION, 'T']
                preg_match(CHAOS_MATCH_ONE_MANY, $docComment, $types);

                if (isset($types[2]) && isset($types[1])) {
                    if ('OneToMany' === $types[1] || 'ManyToMany' === $types[1]) {
                        $types[1] = DOCTRINE_ARRAY_COLLECTION;
                    }
                }
            } else if (false !== strpos($docComment, 'Column')
                || false !== stripos($docComment, 'column') // just a trick
            ) {
                // e.g. @Column(columnDefinition="CHAR(2) NOT NULL", type="string") -> ['string']
                //      @Column(type="string", columnDefinition="ENUM('visible', 'invisible')") -> ['ENUM']
                preg_match(CHAOS_MATCH_COLUMN, $docComment, $types);
            } else if (false !== strpos($docComment, '@Type')
                || false !== strpos($docComment, 'JMS\Serializer\Annotation\Type')
            ) {
                // e.g. @Type("\T")                       : return ['T']
                //      @Type("array<\T>")                : return ['array', 'T']
                //      @Type("array<\K, \V>")            : return ['array', 'K', 'V']
                //      @Type("\Array\Collection")        : return ['Array\Collection']
                //      @Type("\Array\Collection<\T>")    : return ['Array\Collection', 'T']
                //      @Type("\Array\Collection<\K, \V>"): return ['Array\Collection', 'K', 'V']
                //      @Type("DateTime<'Y-m-d'>")        : return ['DateTime', 'Y-m-d']
                //      @Type("DateTime<'Y-m-d', 'UTC'>") : return ['DateTime', 'Y-m-d H:i:sP (e)', 'America/New_York']
                //      @Type("DateTime<'Y-m-d H:i:sP (e)', 'America/New_York', 'Y/m.d\TH:i:s.u'>")
                //          : return ['DateTime', 'Y-m-d H:i:sP (e)', 'America/New_York', 'Y/m.d\TH:i:s.u']
                preg_match(CHAOS_MATCH_TYPE, $docComment, $types);
            }

            if (!empty($types)) {
                array_shift($types); // run faster than without if, so weird
            }
        }

        if (empty($types)) {
            return [gettype($property->getValue($this)), 'is_scalar' => true, 'is_collection' => false];
        }

        // parse the found `types[1]` if any
        $scalars = Types\Type::getTypesMap();
        $types['is_collection'] = isset($types[1]);

        if ($types['is_collection']) {
            $value = $property->getValue($this);
            $isEmpty = empty($types[1]); // e.g. ['T', '']

            if (!$isEmpty) {
                $types = array_reverse($types); // e.g. ['Array\Collection', 'T'] to ['T', 'Array\Collection']
            }

            if (isset($value)) { // if instanced, then override it
                $types[1] = $value;
            } else {
                if ($isEmpty) {
                    $types[1] = Types\Type::ARRAY_TYPE; // e.g. ['T', ''] to ['T', 'array']
                } else if (!isset($scalars[strtolower($types[1])])) {
                    if (false === strpos($types[1], '\\')) {
                        $types[1] = guessNamespace($types[1], $property->getDeclaringClass()->getNamespaceName());
                    }

                    if (is_subclass_of($types[1], DOCTRINE_ARRAY_COLLECTION)
                        || is_subclass_of($types[1], CHAOS_BASE_OBJECT_COLLECTION_INTERFACE)
                    ) {
                        $types[1] = new $types[1];
                    } else if (class_exists($types[1], false)) { // unknown class, use default object instance
                        $types[1] = reflect($types[1])->newInstanceWithoutConstructor();
                    }
                }
            }

            // only array and array-type object are allowed
            if (!($types[1] instanceof \Traversable || Types\Type::ARRAY_TYPE === $types[1])) {
                unset($types[1]);
                $types['is_collection'] = false;
            }
        }

        // parse the found `types[0]` if any
        $types['is_scalar'] = isset($scalars[strtolower($types[0])]);

        if (!$types['is_scalar']) {
            if (false === strpos($types[0], '\\')) {
                $types[0] = guessNamespace($types[0], $property->getDeclaringClass()->getNamespaceName());
            }

            if ($types['is_collection'] && Types\Type::ARRAY_TYPE === $types[1]) {
                $types[1] = new Collections\GenericCollection;
            }
        }

        return $types;
    }

    /**
     * Accepts `visitor`.
     *
     * @param   mixed $var The `visitor`.
     * @param   \Traversable $collection The collection.
     * @param   null|string $method [optional] The known method name.
     * @return  \Traversable
     */
    private function acceptVisitor($var, \Traversable $collection, $method = null)
    {
        if (null === $method) {
            $method = method_exists($collection, 'add') ? 'add' : 'append';
        }

        call_user_func([$collection, $method], $var);

        return $collection;
    }

    /**
     * Converts object to array.
     * @tutorial Breadth-first search
     *
     * @param   mixed $var The value to convert.
     * @param   integer $depth The depth that we go into; defaults to 0.
     * @param   array $visited An array of visited objects; used to prevent cycling.
     * @return  mixed
     */
    private function objectToArray($var, $depth = 0, &$visited = [])
    {
        if (empty($var) || is_scalar($var)) {
            return $var;
        }

        if (is_object($var)) {
            if ($var instanceof \DateTime) {
                return $var->format(DATE_ISO8601);
            }

            $hash = spl_object_hash($var);

            if (isset($visited[$hash])) {
                return '*RECURSION(' . str_replace('\\', '\\\\', get_class($var)) . '#' . $depth . ')*';
            }

            $visited[$hash] = $visited[get_class($var)] = true;

            if ($var instanceof \Traversable) {
                if (is_a($var, DOCTRINE_PERSISTENT_COLLECTION) && null !== ($mapping = $var->getMapping())
                    && isset($visited[$mapping['targetEntity']]) && 4 === $mapping['type'] // one-to-many relation
                ) {
                    return '*COLLECTION*';
                }

                $vars = traversableToArray($var);

                if (empty($vars)) {
                    return $vars;
                }
            } else {
                if ($var instanceof Contracts\IBaseObjectItem) {
                    $vars = $var->toArray();
                } else {
                    $vars = get_object_vars($var);
                }

                if (is_subclass_of($var, DOCTRINE_PROXY)) {
                    unset($vars['__initializer__'], $vars['__cloner__'], $vars['__isInitialized__']);
                }
            }

            $var = $vars;
        }

        if (is_array($var)) {
            if (CHAOS_MAX_RECURSION_DEPTH < ++$depth) {
                return '*MAX_RECURSION_DEPTH_EXCEEDED*';
            }

            return array_map(
                function ($value) use ($depth, $visited) {
                    return $this->objectToArray($value, $depth, $visited);
                },
                $var
            );
        }

        // e.g. resource, closure
        return $var;
    }
}
