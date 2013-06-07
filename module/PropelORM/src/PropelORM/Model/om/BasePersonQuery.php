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
use PropelORM\Model\Person;
use PropelORM\Model\PersonPeer;
use PropelORM\Model\PersonQuery;

/**
 * Base class that represents a query for the 'person' table.
 *
 *
 *
 * @method PersonQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PersonQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method PersonQuery groupById() Group by the id column
 * @method PersonQuery groupByName() Group by the name column
 *
 * @method PersonQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PersonQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PersonQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PersonQuery leftJoinFriendRelatedByPerson1($relationAlias = null) Adds a LEFT JOIN clause to the query using the FriendRelatedByPerson1 relation
 * @method PersonQuery rightJoinFriendRelatedByPerson1($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FriendRelatedByPerson1 relation
 * @method PersonQuery innerJoinFriendRelatedByPerson1($relationAlias = null) Adds a INNER JOIN clause to the query using the FriendRelatedByPerson1 relation
 *
 * @method PersonQuery leftJoinFriendRelatedByPerson2($relationAlias = null) Adds a LEFT JOIN clause to the query using the FriendRelatedByPerson2 relation
 * @method PersonQuery rightJoinFriendRelatedByPerson2($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FriendRelatedByPerson2 relation
 * @method PersonQuery innerJoinFriendRelatedByPerson2($relationAlias = null) Adds a INNER JOIN clause to the query using the FriendRelatedByPerson2 relation
 *
 * @method Person findOne(PropelPDO $con = null) Return the first Person matching the query
 * @method Person findOneOrCreate(PropelPDO $con = null) Return the first Person matching the query, or a new Person object populated from the query conditions when no match is found
 *
 * @method Person findOneByName(string $name) Return the first Person filtered by the name column
 *
 * @method array findById(int $id) Return Person objects filtered by the id column
 * @method array findByName(string $name) Return Person objects filtered by the name column
 *
 * @package    propel.generator..om
 */
abstract class BasePersonQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePersonQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'zf2tutorial-blog', $modelName = 'PropelORM\\Model\\Person', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PersonQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PersonQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PersonQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PersonQuery) {
            return $criteria;
        }
        $query = new PersonQuery();
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
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Person|Person[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PersonPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Person A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Person A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name` FROM `person` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Person();
            $obj->hydrate($row);
            PersonPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Person|Person[]|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Person[]|mixed the list of results, formatted by the current formatter
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
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PersonPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PersonPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PersonPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PersonPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PersonPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related Friend object
     *
     * @param   Friend|PropelObjectCollection $friend  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PersonQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFriendRelatedByPerson1($friend, $comparison = null)
    {
        if ($friend instanceof Friend) {
            return $this
                ->addUsingAlias(PersonPeer::ID, $friend->getPerson1(), $comparison);
        } elseif ($friend instanceof PropelObjectCollection) {
            return $this
                ->useFriendRelatedByPerson1Query()
                ->filterByPrimaryKeys($friend->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFriendRelatedByPerson1() only accepts arguments of type Friend or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FriendRelatedByPerson1 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function joinFriendRelatedByPerson1($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FriendRelatedByPerson1');

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
            $this->addJoinObject($join, 'FriendRelatedByPerson1');
        }

        return $this;
    }

    /**
     * Use the FriendRelatedByPerson1 relation Friend object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PropelORM\Model\FriendQuery A secondary query class using the current class as primary query
     */
    public function useFriendRelatedByPerson1Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFriendRelatedByPerson1($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FriendRelatedByPerson1', '\PropelORM\Model\FriendQuery');
    }

    /**
     * Filter the query by a related Friend object
     *
     * @param   Friend|PropelObjectCollection $friend  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PersonQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFriendRelatedByPerson2($friend, $comparison = null)
    {
        if ($friend instanceof Friend) {
            return $this
                ->addUsingAlias(PersonPeer::ID, $friend->getPerson2(), $comparison);
        } elseif ($friend instanceof PropelObjectCollection) {
            return $this
                ->useFriendRelatedByPerson2Query()
                ->filterByPrimaryKeys($friend->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFriendRelatedByPerson2() only accepts arguments of type Friend or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FriendRelatedByPerson2 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function joinFriendRelatedByPerson2($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FriendRelatedByPerson2');

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
            $this->addJoinObject($join, 'FriendRelatedByPerson2');
        }

        return $this;
    }

    /**
     * Use the FriendRelatedByPerson2 relation Friend object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PropelORM\Model\FriendQuery A secondary query class using the current class as primary query
     */
    public function useFriendRelatedByPerson2Query($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFriendRelatedByPerson2($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FriendRelatedByPerson2', '\PropelORM\Model\FriendQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Person $person Object to remove from the list of results
     *
     * @return PersonQuery The current query, for fluid interface
     */
    public function prune($person = null)
    {
        if ($person) {
            $this->addUsingAlias(PersonPeer::ID, $person->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // equal_nest_parent behavior
    
    /**
     * Find equal nest Friends of the supplied Person object
     *
     * @param  Person $person * @param  PropelPDO $con
     * @return Person[]|PropelObjectCollection
     */
    public function findFriendsOf(Person $person, $con = null)
    {
        $obj = clone $person;
        $obj->clearListFriendsPKs();
        $obj->clearFriends();
    
        return $obj->getFriends($this, $con);
    }
    
    /**
     * Count equal nest Friends of the supplied Person object
     *
     * @param  Person $person * @param  PropelPDO $con
     * @return integer
     */
    public function countFriendsOf(Person $person, PropelPDO $con = null)
    {
        return $person->getFriends()->count();
    }

}
