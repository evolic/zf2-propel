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
use PropelORM\Model\Songs;
use PropelORM\Model\SongsPeer;
use PropelORM\Model\SongsQuery;

/**
 * Base class that represents a query for the 'songs' table.
 *
 *
 *
 * @method SongsQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SongsQuery orderByAlbumId($order = Criteria::ASC) Order by the album_id column
 * @method SongsQuery orderByPosition($order = Criteria::ASC) Order by the position column
 * @method SongsQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method SongsQuery orderByDuration($order = Criteria::ASC) Order by the duration column
 * @method SongsQuery orderByDisc($order = Criteria::ASC) Order by the disc column
 *
 * @method SongsQuery groupById() Group by the id column
 * @method SongsQuery groupByAlbumId() Group by the album_id column
 * @method SongsQuery groupByPosition() Group by the position column
 * @method SongsQuery groupByName() Group by the name column
 * @method SongsQuery groupByDuration() Group by the duration column
 * @method SongsQuery groupByDisc() Group by the disc column
 *
 * @method SongsQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SongsQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SongsQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SongsQuery leftJoinAlbum($relationAlias = null) Adds a LEFT JOIN clause to the query using the Album relation
 * @method SongsQuery rightJoinAlbum($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Album relation
 * @method SongsQuery innerJoinAlbum($relationAlias = null) Adds a INNER JOIN clause to the query using the Album relation
 *
 * @method Songs findOne(PropelPDO $con = null) Return the first Songs matching the query
 * @method Songs findOneOrCreate(PropelPDO $con = null) Return the first Songs matching the query, or a new Songs object populated from the query conditions when no match is found
 *
 * @method Songs findOneByAlbumId(int $album_id) Return the first Songs filtered by the album_id column
 * @method Songs findOneByPosition(int $position) Return the first Songs filtered by the position column
 * @method Songs findOneByName(string $name) Return the first Songs filtered by the name column
 * @method Songs findOneByDuration(string $duration) Return the first Songs filtered by the duration column
 * @method Songs findOneByDisc(int $disc) Return the first Songs filtered by the disc column
 *
 * @method array findById(int $id) Return Songs objects filtered by the id column
 * @method array findByAlbumId(int $album_id) Return Songs objects filtered by the album_id column
 * @method array findByPosition(int $position) Return Songs objects filtered by the position column
 * @method array findByName(string $name) Return Songs objects filtered by the name column
 * @method array findByDuration(string $duration) Return Songs objects filtered by the duration column
 * @method array findByDisc(int $disc) Return Songs objects filtered by the disc column
 *
 * @package    propel.generator..om
 */
abstract class BaseSongsQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSongsQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'zf2tutorial-blog', $modelName = 'PropelORM\\Model\\Songs', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new SongsQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SongsQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SongsQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SongsQuery) {
            return $criteria;
        }
        $query = new SongsQuery();
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
     * @return   Songs|Songs[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SongsPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SongsPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Songs A model object, or null if the key is not found
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
     * @return                 Songs A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `album_id`, `position`, `name`, `duration`, `disc` FROM `songs` WHERE `id` = :p0';
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
            $obj = new Songs();
            $obj->hydrate($row);
            SongsPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Songs|Songs[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Songs[]|mixed the list of results, formatted by the current formatter
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
     * @return SongsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SongsPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SongsQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SongsPeer::ID, $keys, Criteria::IN);
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
     * @return SongsQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SongsPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SongsPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SongsPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the album_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAlbumId(1234); // WHERE album_id = 1234
     * $query->filterByAlbumId(array(12, 34)); // WHERE album_id IN (12, 34)
     * $query->filterByAlbumId(array('min' => 12)); // WHERE album_id >= 12
     * $query->filterByAlbumId(array('max' => 12)); // WHERE album_id <= 12
     * </code>
     *
     * @see       filterByAlbum()
     *
     * @param     mixed $albumId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SongsQuery The current query, for fluid interface
     */
    public function filterByAlbumId($albumId = null, $comparison = null)
    {
        if (is_array($albumId)) {
            $useMinMax = false;
            if (isset($albumId['min'])) {
                $this->addUsingAlias(SongsPeer::ALBUM_ID, $albumId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($albumId['max'])) {
                $this->addUsingAlias(SongsPeer::ALBUM_ID, $albumId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SongsPeer::ALBUM_ID, $albumId, $comparison);
    }

    /**
     * Filter the query on the position column
     *
     * Example usage:
     * <code>
     * $query->filterByPosition(1234); // WHERE position = 1234
     * $query->filterByPosition(array(12, 34)); // WHERE position IN (12, 34)
     * $query->filterByPosition(array('min' => 12)); // WHERE position >= 12
     * $query->filterByPosition(array('max' => 12)); // WHERE position <= 12
     * </code>
     *
     * @param     mixed $position The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SongsQuery The current query, for fluid interface
     */
    public function filterByPosition($position = null, $comparison = null)
    {
        if (is_array($position)) {
            $useMinMax = false;
            if (isset($position['min'])) {
                $this->addUsingAlias(SongsPeer::POSITION, $position['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($position['max'])) {
                $this->addUsingAlias(SongsPeer::POSITION, $position['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SongsPeer::POSITION, $position, $comparison);
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
     * @return SongsQuery The current query, for fluid interface
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

        return $this->addUsingAlias(SongsPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the duration column
     *
     * Example usage:
     * <code>
     * $query->filterByDuration('2011-03-14'); // WHERE duration = '2011-03-14'
     * $query->filterByDuration('now'); // WHERE duration = '2011-03-14'
     * $query->filterByDuration(array('max' => 'yesterday')); // WHERE duration > '2011-03-13'
     * </code>
     *
     * @param     mixed $duration The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SongsQuery The current query, for fluid interface
     */
    public function filterByDuration($duration = null, $comparison = null)
    {
        if (is_array($duration)) {
            $useMinMax = false;
            if (isset($duration['min'])) {
                $this->addUsingAlias(SongsPeer::DURATION, $duration['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($duration['max'])) {
                $this->addUsingAlias(SongsPeer::DURATION, $duration['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SongsPeer::DURATION, $duration, $comparison);
    }

    /**
     * Filter the query on the disc column
     *
     * Example usage:
     * <code>
     * $query->filterByDisc(1234); // WHERE disc = 1234
     * $query->filterByDisc(array(12, 34)); // WHERE disc IN (12, 34)
     * $query->filterByDisc(array('min' => 12)); // WHERE disc >= 12
     * $query->filterByDisc(array('max' => 12)); // WHERE disc <= 12
     * </code>
     *
     * @param     mixed $disc The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SongsQuery The current query, for fluid interface
     */
    public function filterByDisc($disc = null, $comparison = null)
    {
        if (is_array($disc)) {
            $useMinMax = false;
            if (isset($disc['min'])) {
                $this->addUsingAlias(SongsPeer::DISC, $disc['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($disc['max'])) {
                $this->addUsingAlias(SongsPeer::DISC, $disc['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SongsPeer::DISC, $disc, $comparison);
    }

    /**
     * Filter the query by a related Album object
     *
     * @param   Album|PropelObjectCollection $album The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SongsQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAlbum($album, $comparison = null)
    {
        if ($album instanceof Album) {
            return $this
                ->addUsingAlias(SongsPeer::ALBUM_ID, $album->getId(), $comparison);
        } elseif ($album instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SongsPeer::ALBUM_ID, $album->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAlbum() only accepts arguments of type Album or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Album relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SongsQuery The current query, for fluid interface
     */
    public function joinAlbum($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Album');

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
            $this->addJoinObject($join, 'Album');
        }

        return $this;
    }

    /**
     * Use the Album relation Album object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \PropelORM\Model\AlbumQuery A secondary query class using the current class as primary query
     */
    public function useAlbumQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAlbum($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Album', '\PropelORM\Model\AlbumQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Songs $songs Object to remove from the list of results
     *
     * @return SongsQuery The current query, for fluid interface
     */
    public function prune($songs = null)
    {
        if ($songs) {
            $this->addUsingAlias(SongsPeer::ID, $songs->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
