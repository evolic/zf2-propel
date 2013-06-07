<?php

namespace PropelORM\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use PropelORM\Model\Friend;
use PropelORM\Model\FriendPeer;
use PropelORM\Model\FriendQuery;
use PropelORM\Model\Person;

/**
 * Base class that represents a query for the 'friend' table.
 *
 *
 *
 * @method FriendQuery orderByPerson1($order = Criteria::ASC) Order by the person_1 column
 * @method FriendQuery orderByPerson2($order = Criteria::ASC) Order by the person_2 column
 *
 * @method FriendQuery groupByPerson1() Group by the person_1 column
 * @method FriendQuery groupByPerson2() Group by the person_2 column
 *
 * @method FriendQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method FriendQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method FriendQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method FriendQuery leftJoinPersonRelatedByPerson1($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonRelatedByPerson1 relation
 * @method FriendQuery rightJoinPersonRelatedByPerson1($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonRelatedByPerson1 relation
 * @method FriendQuery innerJoinPersonRelatedByPerson1($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonRelatedByPerson1 relation
 *
 * @method FriendQuery leftJoinPersonRelatedByPerson2($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonRelatedByPerson2 relation
 * @method FriendQuery rightJoinPersonRelatedByPerson2($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonRelatedByPerson2 relation
 * @method FriendQuery innerJoinPersonRelatedByPerson2($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonRelatedByPerson2 relation
 *
 * @method Friend findOne(PropelPDO $con = null) Return the first Friend matching the query
 * @method Friend findOneOrCreate(PropelPDO $con = null) Return the first Friend matching the query, or a new Friend object populated from the query conditions when no match is found
 *
 * @method Friend findOneByPerson1(int $person_1) Return the first Friend filtered by the person_1 column
 * @method Friend findOneByPerson2(int $person_2) Return the first Friend filtered by the person_2 column
 *
 * @method array findByPerson1(int $person_1) Return Friend objects filtered by the person_1 column
 * @method array findByPerson2(int $person_2) Return Friend objects filtered by the person_2 column
 *
 * @package    propel.generator..om
 */
abstract class BaseFriendQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseFriendQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'zf2tutorial-blog', $modelName = 'PropelORM\\Model\\Friend', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new FriendQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   FriendQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return FriendQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof FriendQuery) {
            return $criteria;
        }
        $query = new FriendQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$person_1, $person_2]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Friend|Friend[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FriendPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(FriendPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Friend A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `person_1`, `person_2` FROM `friend` WHERE `person_1` = :p0 AND `person_2` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Friend();
            $obj->hydrate($row);
            FriendPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Friend|Friend[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Friend[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return FriendQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(FriendPeer::PERSON_1, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(FriendPeer::PERSON_2, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return FriendQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(FriendPeer::PERSON_1, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(FriendPeer::PERSON_2, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the person_1 column
     *
     * Example usage:
     * <code>
     * $query->filterByPerson1(1234); // WHERE person_1 = 1234
     * $query->filterByPerson1(array(12, 34)); // WHERE person_1 IN (12, 34)
     * $query->filterByPerson1(array('min' => 12)); // WHERE person_1 >= 12
     * $query->filterByPerson1(array('max' => 12)); // WHERE person_1 <= 12
     * </code>
     *
     * @see       filterByPersonRelatedByPerson1()
     *
     * @param     mixed $person1 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FriendQuery The current query, for fluid interface
     */
    public function filterByPerson1($person1 = null, $comparison = null)
    {
        if (is_array($person1)) {
            $useMinMax = false;
            if (isset($person1['min'])) {
                $this->addUsingAlias(FriendPeer::PERSON_1, $person1['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($person1['max'])) {
                $this->addUsingAlias(FriendPeer::PERSON_1, $person1['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FriendPeer::PERSON_1, $person1, $comparison);
    }

    /**
     * Filter the query on the person_2 column
     *
     * Example usage:
     * <code>
     * $query->filterByPerson2(1234); // WHERE person_2 = 1234
     * $query->filterByPerson2(array(12, 34)); // WHERE person_2 IN (12, 34)
     * $query->filterByPerson2(array('min' => 12)); // WHERE person_2 >= 12
     * $query->filterByPerson2(array('max' => 12)); // WHERE person_2 <= 12
     * </code>
     *
     * @see       filterByPersonRelatedByPerson2()
     *
     * @param     mixed $person2 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FriendQuery The current query, for fluid interface
     */
    public function filterByPerson2($person2 = null, $comparison = null)
    {
        if (is_array($person2)) {
            $useMinMax = false;
            if (isset($person2['min'])) {
                $this->addUsingAlias(FriendPeer::PERSON_2, $person2['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($person2['max'])) {
                $this->addUsingAlias(FriendPeer::PERSON_2, $person2['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FriendPeer::PERSON_2, $person2, $comparison);
    }

    /**
     * Filter the query by a related Person object
     *
     * @param   Person|PropelObjectCollection $person The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FriendQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPersonRelatedByPerson1($person, $comparison = null)
    {
        if ($person instanceof Person) {
            return $this
                ->addUsingAlias(FriendPeer::PERSON_1, $person->getId(), $comparison);
        } elseif ($person instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FriendPeer::PERSON_1, $person->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPersonRelatedByPerson1() only accepts arguments of type Person or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PersonRelatedByPerson1 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FriendQuery The current query, for fluid interface
     */
    public function joinPersonRelatedByPerson1($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PersonRelatedByPerson1');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PersonRelatedByPerson1');
        }

        return $this;
    }

    /**
     * Use the PersonRelatedByPerson1 relation Person object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PropelORM\Model\PersonQuery A secondary query class using the current class as primary query
     */
    public function usePersonRelatedByPerson1Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPersonRelatedByPerson1($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonRelatedByPerson1', '\PropelORM\Model\PersonQuery');
    }

    /**
     * Filter the query by a related Person object
     *
     * @param   Person|PropelObjectCollection $person The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FriendQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPersonRelatedByPerson2($person, $comparison = null)
    {
        if ($person instanceof Person) {
            return $this
                ->addUsingAlias(FriendPeer::PERSON_2, $person->getId(), $comparison);
        } elseif ($person instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FriendPeer::PERSON_2, $person->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPersonRelatedByPerson2() only accepts arguments of type Person or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PersonRelatedByPerson2 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FriendQuery The current query, for fluid interface
     */
    public function joinPersonRelatedByPerson2($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PersonRelatedByPerson2');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PersonRelatedByPerson2');
        }

        return $this;
    }

    /**
     * Use the PersonRelatedByPerson2 relation Person object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PropelORM\Model\PersonQuery A secondary query class using the current class as primary query
     */
    public function usePersonRelatedByPerson2Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPersonRelatedByPerson2($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonRelatedByPerson2', '\PropelORM\Model\PersonQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Friend $friend Object to remove from the list of results
     *
     * @return FriendQuery The current query, for fluid interface
     */
    public function prune($friend = null)
    {
        if ($friend) {
            $this->addCond('pruneCond0', $this->getAliasedColName(FriendPeer::PERSON_1), $friend->getPerson1(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(FriendPeer::PERSON_2), $friend->getPerson2(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    // equal_nest behavior
    
    /**
     * Filter the query by 2 Person objects for a Equal Nest Friend relation
     *
     * @param      Person|integer $object1
     * @param      Person|integer $object2
     * @return     FriendQuery Fluent API
     */
    public function filterByPersons($object1, $object2)
    {
        return $this
            ->condition('first-one', 'PropelORM\Model\Friend.Person1 = ?', is_object($object1) ? $object1->getPrimaryKey() : $object1)
            ->condition('first-two', 'PropelORM\Model\Friend.Person2 = ?', is_object($object2) ? $object2->getPrimaryKey() : $object2)
            ->condition('second-one', 'PropelORM\Model\Friend.Person2 = ?', is_object($object1) ? $object1->getPrimaryKey() : $object1)
            ->condition('second-two', 'PropelORM\Model\Friend.Person1 = ?', is_object($object2) ? $object2->getPrimaryKey() : $object2)
            ->combine(array('first-one',  'first-two'),  'AND', 'first')
            ->combine(array('second-one', 'second-two'), 'AND', 'second')
            ->where(array('first', 'second'), 'OR');
    }

}
