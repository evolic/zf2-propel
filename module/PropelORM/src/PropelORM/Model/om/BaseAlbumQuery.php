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
use PropelORM\Model\Album;
use PropelORM\Model\AlbumPeer;
use PropelORM\Model\AlbumQuery;
use PropelORM\Model\Songs;

/**
 * Base class that represents a query for the 'album' table.
 *
 *
 *
 * @method AlbumQuery orderById($order = Criteria::ASC) Order by the id column
 * @method AlbumQuery orderByArtist($order = Criteria::ASC) Order by the artist column
 * @method AlbumQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method AlbumQuery orderByDiscs($order = Criteria::ASC) Order by the discs column
 *
 * @method AlbumQuery groupById() Group by the id column
 * @method AlbumQuery groupByArtist() Group by the artist column
 * @method AlbumQuery groupByTitle() Group by the title column
 * @method AlbumQuery groupByDiscs() Group by the discs column
 *
 * @method AlbumQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method AlbumQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method AlbumQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method AlbumQuery leftJoinSongs($relationAlias = null) Adds a LEFT JOIN clause to the query using the Songs relation
 * @method AlbumQuery rightJoinSongs($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Songs relation
 * @method AlbumQuery innerJoinSongs($relationAlias = null) Adds a INNER JOIN clause to the query using the Songs relation
 *
 * @method Album findOne(PropelPDO $con = null) Return the first Album matching the query
 * @method Album findOneOrCreate(PropelPDO $con = null) Return the first Album matching the query, or a new Album object populated from the query conditions when no match is found
 *
 * @method Album findOneByArtist(string $artist) Return the first Album filtered by the artist column
 * @method Album findOneByTitle(string $title) Return the first Album filtered by the title column
 * @method Album findOneByDiscs(int $discs) Return the first Album filtered by the discs column
 *
 * @method array findById(int $id) Return Album objects filtered by the id column
 * @method array findByArtist(string $artist) Return Album objects filtered by the artist column
 * @method array findByTitle(string $title) Return Album objects filtered by the title column
 * @method array findByDiscs(int $discs) Return Album objects filtered by the discs column
 *
 * @package    propel.generator..om
 */
abstract class BaseAlbumQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseAlbumQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'zf2tutorial-blog', $modelName = 'PropelORM\\Model\\Album', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new AlbumQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   AlbumQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return AlbumQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof AlbumQuery) {
            return $criteria;
        }
        $query = new AlbumQuery();
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
     * @return   Album|Album[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AlbumPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(AlbumPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Album A model object, or null if the key is not found
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
     * @return                 Album A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `artist`, `title`, `discs` FROM `album` WHERE `id` = :p0';
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
            $obj = new Album();
            $obj->hydrate($row);
            AlbumPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Album|Album[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Album[]|mixed the list of results, formatted by the current formatter
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
     * @return AlbumQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AlbumPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return AlbumQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AlbumPeer::ID, $keys, Criteria::IN);
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
     * @return AlbumQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AlbumPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AlbumPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AlbumPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the artist column
     *
     * Example usage:
     * <code>
     * $query->filterByArtist('fooValue');   // WHERE artist = 'fooValue'
     * $query->filterByArtist('%fooValue%'); // WHERE artist LIKE '%fooValue%'
     * </code>
     *
     * @param     string $artist The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AlbumQuery The current query, for fluid interface
     */
    public function filterByArtist($artist = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($artist)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $artist)) {
                $artist = str_replace('*', '%', $artist);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AlbumPeer::ARTIST, $artist, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AlbumQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AlbumPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the discs column
     *
     * Example usage:
     * <code>
     * $query->filterByDiscs(1234); // WHERE discs = 1234
     * $query->filterByDiscs(array(12, 34)); // WHERE discs IN (12, 34)
     * $query->filterByDiscs(array('min' => 12)); // WHERE discs >= 12
     * $query->filterByDiscs(array('max' => 12)); // WHERE discs <= 12
     * </code>
     *
     * @param     mixed $discs The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AlbumQuery The current query, for fluid interface
     */
    public function filterByDiscs($discs = null, $comparison = null)
    {
        if (is_array($discs)) {
            $useMinMax = false;
            if (isset($discs['min'])) {
                $this->addUsingAlias(AlbumPeer::DISCS, $discs['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($discs['max'])) {
                $this->addUsingAlias(AlbumPeer::DISCS, $discs['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AlbumPeer::DISCS, $discs, $comparison);
    }

    /**
     * Filter the query by a related Songs object
     *
     * @param   Songs|PropelObjectCollection $songs  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AlbumQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySongs($songs, $comparison = null)
    {
        if ($songs instanceof Songs) {
            return $this
                ->addUsingAlias(AlbumPeer::ID, $songs->getAlbumId(), $comparison);
        } elseif ($songs instanceof PropelObjectCollection) {
            return $this
                ->useSongsQuery()
                ->filterByPrimaryKeys($songs->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySongs() only accepts arguments of type Songs or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Songs relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AlbumQuery The current query, for fluid interface
     */
    public function joinSongs($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Songs');

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
            $this->addJoinObject($join, 'Songs');
        }

        return $this;
    }

    /**
     * Use the Songs relation Songs object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PropelORM\Model\SongsQuery A secondary query class using the current class as primary query
     */
    public function useSongsQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSongs($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Songs', '\PropelORM\Model\SongsQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Album $album Object to remove from the list of results
     *
     * @return AlbumQuery The current query, for fluid interface
     */
    public function prune($album = null)
    {
        if ($album) {
            $this->addUsingAlias(AlbumPeer::ID, $album->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
