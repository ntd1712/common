<?php

namespace Chaos\Foundation\Contracts;

/**
 * Interface IBaseService
 * @author ntd1712
 *
 * @property-read string $className The short class name of the entity.
 * @property-read string $entityName The qualified class name of the entity.
 * @property-read \Chaos\Foundation\Contracts\IBaseEntity $entity The entity instance.
 * @property-read array $fields The field mappings of the entity.
 * @property-read array $pk The field names that are part of the identifier/primary key of the entity.
 *
 * @property-read \Doctrine\Common\Collections\Criteria $criteria The <tt>Criteria</tt> instance.
 * @property-read \Doctrine\ORM\Query\Expr $expression The <tt>Expr</tt> instance.
 * @property-read \Doctrine\ORM\EntityManager $entityManager The <tt>EntityManager</tt> instance.
 * @property-read \Doctrine\ORM\Mapping\ClassMetadata $metadata The <tt>ClassMetadata</tt> instance.
 */
interface IBaseService
{
    /**
     * The default `readAll` method, you can override this in the derived class.
     *
     * @param   \Doctrine\ORM\QueryBuilder|\Doctrine\Common\Collections\Criteria|array $criteria The criteria.
     * @param   boolean|array $paging The paging criteria; defaults to FALSE.
     * @return  array
     */
    public function readAll($criteria = [], $paging = false);

    /**
     * The default `read` method, you can override this in the derived class.
     *
     * @param   mixed|\Doctrine\ORM\QueryBuilder|\Doctrine\Common\Collections\Criteria|array $criteria The criteria.
     * @return  array
     * @throws  \Chaos\Foundation\Exceptions\ServiceException
     */
    public function read($criteria);

    /**
     * The default `create` method, you can override this in the derived class.
     *
     * @param   array $post The _POST variable.
     * @return  array
     * @throws  \Chaos\Foundation\Exceptions\ServiceException
     * @throws  \Chaos\Foundation\Exceptions\ValidateException
     */
    public function create(array $post = []);

    /**
     * The default `update` method, you can override this in the derived class.
     *
     * @param   array $post The _PUT variable.
     * @param   mixed|\Doctrine\ORM\QueryBuilder|\Doctrine\Common\Collections\Criteria|array $criteria The criteria
     * @return  array
     * @throws  \Chaos\Foundation\Exceptions\ServiceException
     * @throws  \Chaos\Foundation\Exceptions\ValidateException
     */
    public function update(array $post = [], $criteria = null);

    /**
     * The default `delete` method, you can override this in the derived class.
     *
     * @param   mixed|\Doctrine\ORM\QueryBuilder|\Doctrine\Common\Collections\Criteria|array $criteria The criteria.
     * @return  array
     * @throws  \Chaos\Foundation\Exceptions\ServiceException
     */
    public function delete($criteria);
}
