<?php

namespace Chaos\Foundation;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class AbstractDoctrineRepository
 * @author ntd1712
 *
 * @method self beginTransaction()
 * @method self commit()
 * @method self rollback()
 * @method self flush()
 * @method self close()
 * @method string getClassName()
 */
abstract class AbstractDoctrineRepository extends EntityRepository implements Contracts\IDoctrineRepository
{
    use Traits\ConfigAwareTrait, Traits\ContainerAwareTrait,
        BaseDoctrineRepositoryTrait;

    /**
     * {@inheritdoc}
     *
     * @param   boolean $fetchJoinCollection Whether the query joins a collection (true by default).
     */
    public function paginate($criteria = [], array $paging = [], $fetchJoinCollection = true)
    {
        $query = $this->getQueryBuilder($criteria);

        if (null === $query->getFirstResult()) {
            $query->setFirstResult(@$paging['CurrentPageStart'] ?: 0);
        }

        if (null === $query->getMaxResults()) {
            $query->setMaxResults(@$paging['ItemCountPerPage'] ?: CHAOS_MAX_QUERY);
        }

        return new Paginator($query, $fetchJoinCollection);
    }

    /**
     * {@inheritdoc}
     *
     * @param   integer $hydrationMode Processing mode to be used during the hydration process.
     */
    public function readAll($criteria = [], $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        $result = $this->getQueryBuilder($criteria)
            ->getQuery()->execute(null, $hydrationMode);

        return new \ArrayIterator($result);
    }

    /**
     * {@inheritdoc}
     *
     * @param   integer $hydrationMode The hydration mode.
     */
    public function read($criteria, $hydrationMode = null)
    {
        $result = $this->getQueryBuilder($criteria)
            ->setMaxResults(1)->getQuery()->getOneOrNullResult($hydrationMode);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function create($entity, $autoFlush = true)
    {
        return $this->update($entity, null, $autoFlush, true);
    }

    /**
     * {@inheritdoc}
     *
     * @param   boolean $isNew A flag indicating we are creating or updating a record.
     */
    public function update($entity, $criteria = null, $autoFlush = true, $isNew = false)
    {
        if (isset($criteria)) {
            $entity = $this->read($criteria);
        }

        if (!is_array($entity)) {
            $entity = [$entity];
        }

        $i = 0;

        foreach ($entity as $v) {
            $isNew ? $this->_em->persist($v) : $v = $this->_em->merge($v);

            if ((0 === ++$i % CHAOS_MAX_QUERY) && $autoFlush) {
                $this->_em->flush();
            }
        }

        if ($autoFlush && 0 !== $i) {
            $this->_em->flush();
        }

        return $i;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($criteria, $autoFlush = true)
    {
        $entity = is_object($criteria) ? [$criteria] : $this->getQueryBuilder($criteria)->getQuery()->getResult();
        $i = 0;

        foreach ($entity as $v) {
            if ($this->_em->contains($v)) {
                $this->_em->remove($v);

                if ((0 === ++$i % CHAOS_MAX_QUERY) && $autoFlush) {
                    $this->_em->flush();
                }
            }
        }

        if ($autoFlush && 0 !== $i) {
            $this->_em->flush();
        }

        return $i;
    }

    /**
     * {@inheritdoc}
     */
    public function exist($criteria, $fieldName = null)
    {
        if (is_scalar($criteria)) {
            if (isset($fieldName)) {
                $criteria = [$fieldName => $criteria];
            } else if (!empty($this->_class->identifier)) {
                $instance = Criteria::create();

                foreach ($this->_class->identifier as $v) {
                    $instance->orWhere($instance->expr()->eq($v, $criteria));
                }

                $criteria = $instance;
            }
        }

        if ($criteria instanceof Criteria) {
            return 0 !== count($this->matching($criteria));
        }

        return null !== $this->findOneBy($criteria);
    }

    /**
     * @param   string $name The name of the property being interacted with.
     * @return  mixed
     */
    public function __get($name)
    {
        switch ($name) {
            case 'className':
                return $this->_class->reflClass->getShortName();
            case 'entityName':
                return $this->_entityName;
            case 'entity':
                return new $this->_entityName;
            case 'fields':
                return $this->_class->fieldMappings;
            case 'pk':
                return $this->_class->identifier;
            case 'criteria':
                return Criteria::create();
            case 'expression':
                return $this->_em->getExpressionBuilder();
            case 'entityManager':
                return $this->_em;
            case 'metadata':
                return $this->_class;
            default:
                throw new \InvalidArgumentException('Invalid magic property on repository');
        }
    }
}
