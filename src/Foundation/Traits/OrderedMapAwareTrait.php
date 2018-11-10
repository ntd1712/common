<?php

namespace Chaos\Foundation\Traits;

/**
 * Trait OrderedMapAwareTrait
 * @author ntd1712
 *
 * @property-read array $elements
 */
trait OrderedMapAwareTrait
{
    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   mixed $element The element to add.
     * @return  boolean Always TRUE.
     */
    public function add($element)
    {
        return $this->append($element);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @return  void
     */
    public function clear()
    {
        $this->elements = [];
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   mixed $element The element to search for.
     * @return  boolean TRUE if the collection contains the element, FALSE otherwise.
     */
    public function contains($element)
    {
        return in_array($element, $this->elements, true);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @return  boolean TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty()
    {
        return empty($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   string|integer $key The kex/index of the element to remove.
     * @return  null|mixed The removed element or NULL, if the collection did not contain the element.
     */
    public function remove($key)
    {
        if ($this->offsetExists($key)) {
            $removed = $this->elements[$key];
            unset($this->elements[$key]);

            return $removed;
        }

        return null;
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   mixed $element The element to remove.
     * @return  boolean TRUE if the collection contained the specified element, FALSE otherwise.
     */
    public function removeElement($element)
    {
        $offset = $this->indexOf($element);

        if (false !== $offset) {
            unset($this->elements[$offset]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   string|integer $key The key/index to check for.
     * @return  boolean TRUE if the collection contains an element with the specified key/index, FALSE otherwise.
     */
    public function containsKey($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   string|integer $key The key/index of the element to retrieve.
     * @return  mixed
     */
    public function get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @return  array The keys/indices of the collection, in the order of the corresponding elements in the collection.
     */
    public function getKeys()
    {
        return array_keys($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @return  array The values of all elements in the collection, in the order they appear in the collection.
     */
    public function getValues()
    {
        return array_values($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   string|integer $key The key/index of the element to set.
     * @param   mixed $value The element to set.
     * @return  void
     */
    public function set($key, $value)
    {
        $this->elements[$key] = $value;
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @return  array
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @return  mixed
     */
    public function first()
    {
        return reset($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @return  mixed
     */
    public function last()
    {
        return end($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @return  integer|string
     */
    public function key()
    {
        return key($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @return  mixed
     */
    public function current()
    {
        return current($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @return  mixed
     */
    public function next()
    {
        return next($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   \Closure $p The predicate.
     * @return  boolean TRUE if the predicate is TRUE for at least one element, FALSE otherwise.
     */
    public function exists(\Closure $p)
    {
        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   \Closure $p The predicate used for filtering.
     * @return  static A collection with the results of the filter operation.
     */
    public function filter(\Closure $p)
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        return new static(array_filter($this->elements, $p));
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   \Closure $p The predicate.
     * @return  boolean TRUE, if the predicate yields TRUE for all elements, FALSE otherwise.
     */
    public function forAll(\Closure $p)
    {
        foreach ($this->elements as $key => $element) {
            if (!$p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   \Closure $func The function.
     * @return  static
     */
    public function map(\Closure $func)
    {
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        return new static(array_map($func, $this->elements));
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   \Closure $p The predicate on which to partition.
     * @return  static[] An array with two elements. The first element contains the collection
     *                   of elements where the predicate returned TRUE, the second element
     *                   contains the collection of elements where the predicate returned FALSE.
     */
    public function partition(\Closure $p)
    {
        $matches = $noMatches = [];

        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        return [new static($matches), new static($noMatches)];
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   mixed $element The element to search for.
     * @return  integer|string|boolean The key/index of the element or FALSE if the element was not found.
     */
    public function indexOf($element)
    {
        return array_search($element, $this->elements, true);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   integer $offset The offset to start from.
     * @param   integer $length [optional] The maximum number of elements to return, or NULL for no limit.
     * @return  array
     */
    public function slice($offset, $length = null)
    {
        return array_slice($this->elements, $offset, $length, true);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   object|array $elements The new elements to exchange with current elements.
     * @return  array The old elements.
     */
    public function exchangeArray($elements)
    {
        $current = $this->elements;

        if (is_array($elements)) {
            $this->elements = $elements;
        } else if ($elements instanceof \Traversable) {
            $this->elements = traversableToArray($elements);
        }

        return $current;
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   mixed $element The element being appended.
     * @return  boolean Always TRUE.
     */
    public function append($element)
    {
        $this->elements[] = $element;

        return true;
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   mixed $element The element being prepended.
     * @return  boolean Always TRUE.
     */
    public function prepend($element)
    {
        array_unshift($this->elements, $element);

        return true;
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   integer $flags [optional] The sorting behavior, for details see {@link sort()}.
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public function asort($flags = null)
    {
        return asort($this->elements, $flags);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   integer $flags [optional] The sorting behavior, for details see {@link sort()}.
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public function ksort($flags = null)
    {
        return ksort($this->elements, $flags);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public function natcasesort()
    {
        return natcasesort($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public function natsort()
    {
        return natsort($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   callable $valueCompareFunc The comparison function, for details see {@link usort()}.
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public function uasort($valueCompareFunc)
    {
        return uasort($this->elements, $valueCompareFunc);
    }

    /**
     * {@inheritdoc} Required by interface IOrderedMap.
     *
     * @param   callable $keyCompareFunc The comparison function, for details see {@link usort()}.
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public function uksort($keyCompareFunc)
    {
        return uksort($this->elements, $keyCompareFunc);
    }

    // <editor-fold desc="INTERFACES IMPLEMENTATIONS" defaultstate="collapsed">

    /**
     * {@inheritdoc} Required by interface ArrayAccess.
     *
     * @param   mixed $offset An offset to check for.
     * @return  boolean TRUE on success or FALSE on failure.
     */
    public function offsetExists($offset)
    {
        return isset($this->elements[$offset]) || array_key_exists($offset, $this->elements);
    }

    /**
     * {@inheritdoc} Required by interface ArrayAccess.
     *
     * @param   mixed $offset The offset to retrieve.
     * @return  mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->elements[$offset] : null;
    }

    /**
     * {@inheritdoc} Required by interface ArrayAccess.
     *
     * @param   mixed $offset The offset to assign the value to.
     * @param   mixed $value The value to set.
     * @return  void
     */
    public function offsetSet($offset, $value)
    {
        if ($this->offsetExists($offset)) {
            $this->elements[$offset] = $value;
        } else {
            $this->elements[] = $value;
        }
    }

    /**
     * {@inheritdoc} Required by interface ArrayAccess.
     *
     * @param   mixed $offset The offset to unset.
     * @return  void
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->elements[$offset]);
        }
    }

    /**
     * {@inheritdoc} Required by interface Countable.
     *
     * @return  integer The custom count as an integer.
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface IteratorAggregate.
     *
     * @return  \ArrayIterator An instance of an object implementing <b>Iterator</b> or <b>Traversable</b>.
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface Serializable.
     *
     * @return  null|string The string representation of the object or NULL.
     */
    public function serialize()
    {
        return serialize($this->elements);
    }

    /**
     * {@inheritdoc} Required by interface Serializable.
     *
     * @param   string $serialized The string representation of the object.
     * @return  void
     */
    public function unserialize($serialized)
    {
        $this->elements = unserialize($serialized);
    }

    // </editor-fold>
}
