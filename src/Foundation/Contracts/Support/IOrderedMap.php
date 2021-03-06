<?php

namespace Chaos\Foundation\Contracts\Support;

/**
 * Interface IOrderedMap
 * @author ntd1712
 *
 * @see \Doctrine\Common\Collections\Collection
 */
interface IOrderedMap extends \ArrayAccess, \Countable, \IteratorAggregate, \Serializable
{
    /**
     * Proxies to {@link append()}.
     *
     * @param   mixed $element The element to add.
     * @return  boolean Always TRUE.
     */
    public function add($element);

    /**
     * Clears the collection, removing all elements.
     *
     * @return  void
     */
    public function clear();

    /**
     * Checks whether an element is contained in the collection.
     * This is an O(n) operation, where n is the size of the collection.
     *
     * @param   mixed $element The element to search for.
     * @return  boolean TRUE if the collection contains the element, FALSE otherwise.
     */
    public function contains($element);

    /**
     * Checks whether the collection is empty (contains no elements).
     *
     * @return  boolean TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty();

    /**
     * Removes the element at the specified index from the collection.
     *
     * @param   string|integer $key The kex/index of the element to remove.
     * @return  mixed The removed element or NULL, if the collection did not contain the element.
     */
    public function remove($key);

    /**
     * Removes the specified element from the collection, if it is found.
     *
     * @param   mixed $element The element to remove.
     * @return  boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeElement($element);

    /**
     * Checks whether the collection contains an element with the specified key/index.
     *
     * @param   string|integer $key The key/index to check for.
     * @return  boolean TRUE if the collection contains an element with the specified key/index, FALSE otherwise.
     */
    public function containsKey($key);

    /**
     * Gets the element at the specified key/index.
     *
     * @param   string|integer $key The key/index of the element to retrieve.
     * @return  mixed
     */
    public function get($key);

    /**
     * Gets all keys/indices of the collection.
     *
     * @return  array The keys/indices of the collection, in the order of the corresponding elements in the collection.
     */
    public function getKeys();

    /**
     * Gets all values of the collection.
     *
     * @return  array The values of all elements in the collection, in the order they appear in the collection.
     */
    public function getValues();

    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param   string|integer $key The key/index of the element to set.
     * @param   mixed $value The element to set.
     * @return  void
     */
    public function set($key, $value);

    /**
     * Proxies to {@link getArrayCopy()}.
     *
     * @return  array
     */
    public function toArray();

    /**
     * Sets the internal iterator to the first element in the collection and returns this element.
     *
     * @return  mixed
     */
    public function first();

    /**
     * Sets the internal iterator to the last element in the collection and returns this element.
     *
     * @return  mixed
     */
    public function last();

    /**
     * Gets the key/index of the element at the current iterator position.
     *
     * @return  integer|string
     */
    public function key();

    /**
     * Gets the element of the collection at the current iterator position.
     *
     * @return  mixed
     */
    public function current();

    /**
     * Moves the internal iterator position to the next element and returns this element.
     *
     * @return  mixed
     */
    public function next();

    /**
     * Tests for the existence of an element that satisfies the given predicate.
     *
     * @param   \Closure $p The predicate.
     * @return  boolean TRUE if the predicate is TRUE for at least one element, FALSE otherwise.
     */
    public function exists(\Closure $p);

    /**
     * Returns all the elements of this collection that satisfy the predicate p.
     * The order of the elements is preserved.
     *
     * @param   \Closure $p The predicate used for filtering.
     * @return  self A collection with the results of the filter operation.
     */
    public function filter(\Closure $p);

    /**
     * Tests whether the given predicate p holds for all elements of this collection.
     *
     * @param   \Closure $p The predicate.
     * @return  boolean TRUE, if the predicate yields TRUE for all elements, FALSE otherwise.
     */
    public function forAll(\Closure $p);

    /**
     * Applies the given function to each element in the collection and returns
     * a new collection with the elements returned by the function.
     *
     * @param   \Closure $func
     * @return  self
     */
    public function map(\Closure $func);

    /**
     * Partitions this collection in two collections according to a predicate.
     * Keys are preserved in the resulting collections.
     *
     * @param   \Closure $p The predicate on which to partition.
     * @return  self[] An array with two elements. The first element contains the collection
     *                 of elements where the predicate returned TRUE, the second element
     *                 contains the collection of elements where the predicate returned FALSE.
     */
    public function partition(\Closure $p);

    /**
     * Gets the index/key of a given element. The comparison of two elements is strict,
     * that means not only the value but also the type must match.
     * For objects this means reference equality.
     *
     * @param   mixed $element The element to search for.
     * @return  integer|string|boolean The key/index of the element or FALSE if the element was not found.
     */
    public function indexOf($element);

    /**
     * Extracts a slice of $length elements starting at position $offset from the Collection.
     *
     * If $length is null it returns all elements from $offset to the end of the Collection.
     * Keys have to be preserved by this method. Calling this method will only return the
     * selected slice and NOT change the elements contained in the collection slice is called on.
     *
     * @param   integer $offset The offset to start from.
     * @param   null|integer $length [optional] The maximum number of elements to return, or null for no limit.
     * @return  array
     */
    public function slice($offset, $length = null);

    /**
     * Exchanges the elements of the collection for another one.
     *
     * @param   object|array $elements The new elements to exchange with current elements.
     * @return  array The old elements.
     */
    public function exchangeArray($elements);

    /**
     * Adds an element at the end of the collection.
     *
     * @param   mixed $element The element being appended.
     * @return  boolean Always TRUE.
     */
    public function append($element);

    /**
     * Prepends an element onto the beginning of the collection.
     *
     * @param   mixed $element The element being prepended.
     * @return  boolean Always TRUE.
     */
    public function prepend($element);

    /**
     * Sorts the elements of the collection by value.
     *
     * @param   integer $flags [optional] The sorting behavior, for details see {@link sort()}.
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public function asort($flags = null);

    /**
     * Sorts the elements of the collection by key.
     *
     * @param   integer $flags [optional] The sorting behavior, for details see {@link sort()}.
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public function ksort($flags = null);

    /**
     * Sorts the elements of the collection using a case insensitive `natural order` algorithm.
     *
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public function natcasesort();

    /**
     * Sorts the elements of the collection using a `natural order` algorithm.
     *
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public function natsort();

    /**
     * Sorts the elements of the collection with a user-defined comparison function and maintain index association.
     *
     * @param   callable $valueCompareFunc The comparison function, for details see {@link usort()}.
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public function uasort($valueCompareFunc);

    /**
     * Sorts the elements of the collection by keys using a user-defined comparison function.
     *
     * @param   callable $keyCompareFunc The comparison function, for details see {@link usort()}.
     * @return  boolean Returns TRUE on success or FALSE on failure.
     */
    public function uksort($keyCompareFunc);
}
