<?php

namespace PropelORM\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use PropelORM\Model\Friend;
use PropelORM\Model\FriendPeer;
use PropelORM\Model\FriendQuery;
use PropelORM\Model\Person;
use PropelORM\Model\PersonPeer;
use PropelORM\Model\PersonQuery;

/**
 * Base class that represents a row from the 'person' table.
 *
 *
 *
 * @package    propel.generator..om
 */
abstract class BasePerson extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'PropelORM\\Model\\PersonPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        PersonPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * @var        PropelObjectCollection|Friend[] Collection to store aggregation of Friend objects.
     */
    protected $collFriendsRelatedByPerson1;
    protected $collFriendsRelatedByPerson1Partial;

    /**
     * @var        PropelObjectCollection|Friend[] Collection to store aggregation of Friend objects.
     */
    protected $collFriendsRelatedByPerson2;
    protected $collFriendsRelatedByPerson2Partial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    // equal_nest_parent behavior
    
    /**
     * @var array List of PKs of Friend for this Person */
    protected $listEqualNestFriendsPKs;
    
    /**
     * @var PropelObjectCollection Person[] Collection to store Equal Nest Friend of this Person */
    protected $collEqualNestFriends;
    
    /**
     * @var boolean Flag to prevent endless processing loop which occurs when 2 new objects are set as twins
     */
    protected $alreadyInEqualNestProcessing = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $friendsRelatedByPerson1ScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $friendsRelatedByPerson2ScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Person The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = PersonPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return Person The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = PersonPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 2; // 2 = PersonPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Person object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = PersonPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collFriendsRelatedByPerson1 = null;

            $this->collFriendsRelatedByPerson2 = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = PersonQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                // equal_nest_parent behavior
                $this->processEqualNestQueries($con);

                PersonPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->friendsRelatedByPerson1ScheduledForDeletion !== null) {
                if (!$this->friendsRelatedByPerson1ScheduledForDeletion->isEmpty()) {
                    FriendQuery::create()
                        ->filterByPrimaryKeys($this->friendsRelatedByPerson1ScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->friendsRelatedByPerson1ScheduledForDeletion = null;
                }
            }

            if ($this->collFriendsRelatedByPerson1 !== null) {
                foreach ($this->collFriendsRelatedByPerson1 as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->friendsRelatedByPerson2ScheduledForDeletion !== null) {
                if (!$this->friendsRelatedByPerson2ScheduledForDeletion->isEmpty()) {
                    FriendQuery::create()
                        ->filterByPrimaryKeys($this->friendsRelatedByPerson2ScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->friendsRelatedByPerson2ScheduledForDeletion = null;
                }
            }

            if ($this->collFriendsRelatedByPerson2 !== null) {
                foreach ($this->collFriendsRelatedByPerson2 as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = PersonPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PersonPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PersonPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(PersonPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }

        $sql = sprintf(
            'INSERT INTO `person` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            if (($retval = PersonPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collFriendsRelatedByPerson1 !== null) {
                    foreach ($this->collFriendsRelatedByPerson1 as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collFriendsRelatedByPerson2 !== null) {
                    foreach ($this->collFriendsRelatedByPerson2 as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = PersonPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Person'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Person'][$this->getPrimaryKey()] = true;
        $keys = PersonPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->collFriendsRelatedByPerson1) {
                $result['FriendsRelatedByPerson1'] = $this->collFriendsRelatedByPerson1->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFriendsRelatedByPerson2) {
                $result['FriendsRelatedByPerson2'] = $this->collFriendsRelatedByPerson2->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = PersonPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = PersonPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PersonPeer::DATABASE_NAME);

        if ($this->isColumnModified(PersonPeer::ID)) $criteria->add(PersonPeer::ID, $this->id);
        if ($this->isColumnModified(PersonPeer::NAME)) $criteria->add(PersonPeer::NAME, $this->name);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(PersonPeer::DATABASE_NAME);
        $criteria->add(PersonPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Person (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getFriendsRelatedByPerson1() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFriendRelatedByPerson1($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFriendsRelatedByPerson2() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFriendRelatedByPerson2($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Person Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return PersonPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new PersonPeer();
        }

        return self::$peer;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('FriendRelatedByPerson1' == $relationName) {
            $this->initFriendsRelatedByPerson1();
        }
        if ('FriendRelatedByPerson2' == $relationName) {
            $this->initFriendsRelatedByPerson2();
        }
    }

    /**
     * Clears out the collFriendsRelatedByPerson1 collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Person The current object (for fluent API support)
     * @see        addFriendsRelatedByPerson1()
     */
    public function clearFriendsRelatedByPerson1()
    {
        $this->collFriendsRelatedByPerson1 = null; // important to set this to null since that means it is uninitialized
        $this->collFriendsRelatedByPerson1Partial = null;

        return $this;
    }

    /**
     * reset is the collFriendsRelatedByPerson1 collection loaded partially
     *
     * @return void
     */
    public function resetPartialFriendsRelatedByPerson1($v = true)
    {
        $this->collFriendsRelatedByPerson1Partial = $v;
    }

    /**
     * Initializes the collFriendsRelatedByPerson1 collection.
     *
     * By default this just sets the collFriendsRelatedByPerson1 collection to an empty array (like clearcollFriendsRelatedByPerson1());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFriendsRelatedByPerson1($overrideExisting = true)
    {
        if (null !== $this->collFriendsRelatedByPerson1 && !$overrideExisting) {
            return;
        }
        $this->collFriendsRelatedByPerson1 = new PropelObjectCollection();
        $this->collFriendsRelatedByPerson1->setModel('Friend');
    }

    /**
     * Gets an array of Friend objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Person is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Friend[] List of Friend objects
     * @throws PropelException
     */
    public function getFriendsRelatedByPerson1($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collFriendsRelatedByPerson1Partial && !$this->isNew();
        if (null === $this->collFriendsRelatedByPerson1 || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFriendsRelatedByPerson1) {
                // return empty collection
                $this->initFriendsRelatedByPerson1();
            } else {
                $collFriendsRelatedByPerson1 = FriendQuery::create(null, $criteria)
                    ->filterByPersonRelatedByPerson1($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collFriendsRelatedByPerson1Partial && count($collFriendsRelatedByPerson1)) {
                      $this->initFriendsRelatedByPerson1(false);

                      foreach($collFriendsRelatedByPerson1 as $obj) {
                        if (false == $this->collFriendsRelatedByPerson1->contains($obj)) {
                          $this->collFriendsRelatedByPerson1->append($obj);
                        }
                      }

                      $this->collFriendsRelatedByPerson1Partial = true;
                    }

                    $collFriendsRelatedByPerson1->getInternalIterator()->rewind();
                    return $collFriendsRelatedByPerson1;
                }

                if($partial && $this->collFriendsRelatedByPerson1) {
                    foreach($this->collFriendsRelatedByPerson1 as $obj) {
                        if($obj->isNew()) {
                            $collFriendsRelatedByPerson1[] = $obj;
                        }
                    }
                }

                $this->collFriendsRelatedByPerson1 = $collFriendsRelatedByPerson1;
                $this->collFriendsRelatedByPerson1Partial = false;
            }
        }

        return $this->collFriendsRelatedByPerson1;
    }

    /**
     * Sets a collection of FriendRelatedByPerson1 objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $friendsRelatedByPerson1 A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Person The current object (for fluent API support)
     */
    public function setFriendsRelatedByPerson1(PropelCollection $friendsRelatedByPerson1, PropelPDO $con = null)
    {
        $friendsRelatedByPerson1ToDelete = $this->getFriendsRelatedByPerson1(new Criteria(), $con)->diff($friendsRelatedByPerson1);

        $this->friendsRelatedByPerson1ScheduledForDeletion = unserialize(serialize($friendsRelatedByPerson1ToDelete));

        foreach ($friendsRelatedByPerson1ToDelete as $friendRelatedByPerson1Removed) {
            $friendRelatedByPerson1Removed->setPersonRelatedByPerson1(null);
        }

        $this->collFriendsRelatedByPerson1 = null;
        foreach ($friendsRelatedByPerson1 as $friendRelatedByPerson1) {
            $this->addFriendRelatedByPerson1($friendRelatedByPerson1);
        }

        $this->collFriendsRelatedByPerson1 = $friendsRelatedByPerson1;
        $this->collFriendsRelatedByPerson1Partial = false;

        return $this;
    }

    /**
     * Returns the number of related Friend objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Friend objects.
     * @throws PropelException
     */
    public function countFriendsRelatedByPerson1(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collFriendsRelatedByPerson1Partial && !$this->isNew();
        if (null === $this->collFriendsRelatedByPerson1 || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFriendsRelatedByPerson1) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getFriendsRelatedByPerson1());
            }
            $query = FriendQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPersonRelatedByPerson1($this)
                ->count($con);
        }

        return count($this->collFriendsRelatedByPerson1);
    }

    /**
     * Method called to associate a Friend object to this object
     * through the Friend foreign key attribute.
     *
     * @param    Friend $l Friend
     * @return Person The current object (for fluent API support)
     */
    public function addFriendRelatedByPerson1(Friend $l)
    {
        if ($this->collFriendsRelatedByPerson1 === null) {
            $this->initFriendsRelatedByPerson1();
            $this->collFriendsRelatedByPerson1Partial = true;
        }
        if (!in_array($l, $this->collFriendsRelatedByPerson1->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddFriendRelatedByPerson1($l);
        }

        return $this;
    }

    /**
     * @param	FriendRelatedByPerson1 $friendRelatedByPerson1 The friendRelatedByPerson1 object to add.
     */
    protected function doAddFriendRelatedByPerson1($friendRelatedByPerson1)
    {
        $this->collFriendsRelatedByPerson1[]= $friendRelatedByPerson1;
        $friendRelatedByPerson1->setPersonRelatedByPerson1($this);
    }

    /**
     * @param	FriendRelatedByPerson1 $friendRelatedByPerson1 The friendRelatedByPerson1 object to remove.
     * @return Person The current object (for fluent API support)
     */
    public function removeFriendRelatedByPerson1($friendRelatedByPerson1)
    {
        if ($this->getFriendsRelatedByPerson1()->contains($friendRelatedByPerson1)) {
            $this->collFriendsRelatedByPerson1->remove($this->collFriendsRelatedByPerson1->search($friendRelatedByPerson1));
            if (null === $this->friendsRelatedByPerson1ScheduledForDeletion) {
                $this->friendsRelatedByPerson1ScheduledForDeletion = clone $this->collFriendsRelatedByPerson1;
                $this->friendsRelatedByPerson1ScheduledForDeletion->clear();
            }
            $this->friendsRelatedByPerson1ScheduledForDeletion[]= clone $friendRelatedByPerson1;
            $friendRelatedByPerson1->setPersonRelatedByPerson1(null);
        }

        return $this;
    }

    /**
     * Clears out the collFriendsRelatedByPerson2 collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Person The current object (for fluent API support)
     * @see        addFriendsRelatedByPerson2()
     */
    public function clearFriendsRelatedByPerson2()
    {
        $this->collFriendsRelatedByPerson2 = null; // important to set this to null since that means it is uninitialized
        $this->collFriendsRelatedByPerson2Partial = null;

        return $this;
    }

    /**
     * reset is the collFriendsRelatedByPerson2 collection loaded partially
     *
     * @return void
     */
    public function resetPartialFriendsRelatedByPerson2($v = true)
    {
        $this->collFriendsRelatedByPerson2Partial = $v;
    }

    /**
     * Initializes the collFriendsRelatedByPerson2 collection.
     *
     * By default this just sets the collFriendsRelatedByPerson2 collection to an empty array (like clearcollFriendsRelatedByPerson2());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFriendsRelatedByPerson2($overrideExisting = true)
    {
        if (null !== $this->collFriendsRelatedByPerson2 && !$overrideExisting) {
            return;
        }
        $this->collFriendsRelatedByPerson2 = new PropelObjectCollection();
        $this->collFriendsRelatedByPerson2->setModel('Friend');
    }

    /**
     * Gets an array of Friend objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Person is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Friend[] List of Friend objects
     * @throws PropelException
     */
    public function getFriendsRelatedByPerson2($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collFriendsRelatedByPerson2Partial && !$this->isNew();
        if (null === $this->collFriendsRelatedByPerson2 || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFriendsRelatedByPerson2) {
                // return empty collection
                $this->initFriendsRelatedByPerson2();
            } else {
                $collFriendsRelatedByPerson2 = FriendQuery::create(null, $criteria)
                    ->filterByPersonRelatedByPerson2($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collFriendsRelatedByPerson2Partial && count($collFriendsRelatedByPerson2)) {
                      $this->initFriendsRelatedByPerson2(false);

                      foreach($collFriendsRelatedByPerson2 as $obj) {
                        if (false == $this->collFriendsRelatedByPerson2->contains($obj)) {
                          $this->collFriendsRelatedByPerson2->append($obj);
                        }
                      }

                      $this->collFriendsRelatedByPerson2Partial = true;
                    }

                    $collFriendsRelatedByPerson2->getInternalIterator()->rewind();
                    return $collFriendsRelatedByPerson2;
                }

                if($partial && $this->collFriendsRelatedByPerson2) {
                    foreach($this->collFriendsRelatedByPerson2 as $obj) {
                        if($obj->isNew()) {
                            $collFriendsRelatedByPerson2[] = $obj;
                        }
                    }
                }

                $this->collFriendsRelatedByPerson2 = $collFriendsRelatedByPerson2;
                $this->collFriendsRelatedByPerson2Partial = false;
            }
        }

        return $this->collFriendsRelatedByPerson2;
    }

    /**
     * Sets a collection of FriendRelatedByPerson2 objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $friendsRelatedByPerson2 A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Person The current object (for fluent API support)
     */
    public function setFriendsRelatedByPerson2(PropelCollection $friendsRelatedByPerson2, PropelPDO $con = null)
    {
        $friendsRelatedByPerson2ToDelete = $this->getFriendsRelatedByPerson2(new Criteria(), $con)->diff($friendsRelatedByPerson2);

        $this->friendsRelatedByPerson2ScheduledForDeletion = unserialize(serialize($friendsRelatedByPerson2ToDelete));

        foreach ($friendsRelatedByPerson2ToDelete as $friendRelatedByPerson2Removed) {
            $friendRelatedByPerson2Removed->setPersonRelatedByPerson2(null);
        }

        $this->collFriendsRelatedByPerson2 = null;
        foreach ($friendsRelatedByPerson2 as $friendRelatedByPerson2) {
            $this->addFriendRelatedByPerson2($friendRelatedByPerson2);
        }

        $this->collFriendsRelatedByPerson2 = $friendsRelatedByPerson2;
        $this->collFriendsRelatedByPerson2Partial = false;

        return $this;
    }

    /**
     * Returns the number of related Friend objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Friend objects.
     * @throws PropelException
     */
    public function countFriendsRelatedByPerson2(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collFriendsRelatedByPerson2Partial && !$this->isNew();
        if (null === $this->collFriendsRelatedByPerson2 || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFriendsRelatedByPerson2) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getFriendsRelatedByPerson2());
            }
            $query = FriendQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPersonRelatedByPerson2($this)
                ->count($con);
        }

        return count($this->collFriendsRelatedByPerson2);
    }

    /**
     * Method called to associate a Friend object to this object
     * through the Friend foreign key attribute.
     *
     * @param    Friend $l Friend
     * @return Person The current object (for fluent API support)
     */
    public function addFriendRelatedByPerson2(Friend $l)
    {
        if ($this->collFriendsRelatedByPerson2 === null) {
            $this->initFriendsRelatedByPerson2();
            $this->collFriendsRelatedByPerson2Partial = true;
        }
        if (!in_array($l, $this->collFriendsRelatedByPerson2->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddFriendRelatedByPerson2($l);
        }

        return $this;
    }

    /**
     * @param	FriendRelatedByPerson2 $friendRelatedByPerson2 The friendRelatedByPerson2 object to add.
     */
    protected function doAddFriendRelatedByPerson2($friendRelatedByPerson2)
    {
        $this->collFriendsRelatedByPerson2[]= $friendRelatedByPerson2;
        $friendRelatedByPerson2->setPersonRelatedByPerson2($this);
    }

    /**
     * @param	FriendRelatedByPerson2 $friendRelatedByPerson2 The friendRelatedByPerson2 object to remove.
     * @return Person The current object (for fluent API support)
     */
    public function removeFriendRelatedByPerson2($friendRelatedByPerson2)
    {
        if ($this->getFriendsRelatedByPerson2()->contains($friendRelatedByPerson2)) {
            $this->collFriendsRelatedByPerson2->remove($this->collFriendsRelatedByPerson2->search($friendRelatedByPerson2));
            if (null === $this->friendsRelatedByPerson2ScheduledForDeletion) {
                $this->friendsRelatedByPerson2ScheduledForDeletion = clone $this->collFriendsRelatedByPerson2;
                $this->friendsRelatedByPerson2ScheduledForDeletion->clear();
            }
            $this->friendsRelatedByPerson2ScheduledForDeletion[]= clone $friendRelatedByPerson2;
            $friendRelatedByPerson2->setPersonRelatedByPerson2(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collFriendsRelatedByPerson1) {
                foreach ($this->collFriendsRelatedByPerson1 as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFriendsRelatedByPerson2) {
                foreach ($this->collFriendsRelatedByPerson2 as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        // equal_nest_parent behavior
        
        if ($deep) {
            if ($this->collEqualNestFriends) {
                foreach ($this->collEqualNestFriends as $obj) {
                    $obj->clearAllReferences($deep);
                }
            }
        }
        
        $this->listEqualNestFriendsPKs = null;
        $this->collEqualNestFriends = null;

        if ($this->collFriendsRelatedByPerson1 instanceof PropelCollection) {
            $this->collFriendsRelatedByPerson1->clearIterator();
        }
        $this->collFriendsRelatedByPerson1 = null;
        if ($this->collFriendsRelatedByPerson2 instanceof PropelCollection) {
            $this->collFriendsRelatedByPerson2->clearIterator();
        }
        $this->collFriendsRelatedByPerson2 = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PersonPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // equal_nest_parent behavior
    
    /**
     * This function checks the local equal nest collection against the database
     * and creates new relations or deletes ones that have been removed
     *
     * @param PropelPDO $con
     */
    public function processEqualNestQueries(PropelPDO $con = null)
    {
        if (false === $this->alreadyInEqualNestProcessing && null !== $this->collEqualNestFriends) {
    
            if (null === $con) {
                $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
            }
    
            $this->alreadyInEqualNestProcessing = true;
    
            $this->clearListFriendsPKs();
            $this->initListFriendsPKs($con);
    
            $this->collEqualNestFriends->save();
    
            $con->beginTransaction();
    
            try {
                foreach ($this->getFriends()->getPrimaryKeys($usePrefix = false) as $columnKey => $pk) {
                    if (!in_array($pk, $this->listEqualNestFriendsPKs)) {
                        // save new equal nest relation
                        FriendPeer::buildEqualNestFriendRelation($this, $pk, $con);
                        // add this object to the sibling's collection
                        $this->getFriends()->get($columnKey)->addFriend($this);
                    } else {
                        // remove the pk from the list of db keys
                        unset($this->listEqualNestFriendsPKs[array_search($pk, $this->listEqualNestFriendsPKs)]);
                    }
                }
    
                // if we have keys still left, this means they are relations that have to be removed
                foreach ($this->listEqualNestFriendsPKs as $oldPk) {
                    FriendPeer::removeEqualNestFriendRelation($this, $oldPk, $con);
                }
    
                $con->commit();
                $this->alreadyInEqualNestProcessing = false;
            } catch (PropelException $e) {
                $con->rollBack();
                throw $e;
            }
        }
    }
    
    /**
     * Clears out the list of Equal Nest Friends PKs
     */
    public function clearListFriendsPKs()
    {
        $this->listEqualNestFriendsPKs = null;
    }
    
    /**
     * Initializes the list of Equal Nest Friends PKs.
     *
     * This will query the database for Equal Nest Friends relations to this Person object.
     * It will set the list to an empty array if the object is newly created.
     *
     * @param PropelPDO $con
     */
    protected function initListFriendsPKs(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PersonPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
    
        if (null === $this->listEqualNestFriendsPKs) {
            if ($this->isNew()) {
                $this->listEqualNestFriendsPKs = array();
            } else {
                $sql = "
    SELECT DISTINCT person.id
    FROM person
    INNER JOIN friend ON
    person.id = friend.person_1
    OR
    person.id = friend.person_2
    WHERE
    person.id IN (
        SELECT friend.person_1
        FROM friend
        WHERE friend.person_2 = ?
    )
    OR
    person.id IN (
        SELECT friend.person_2
        FROM friend
        WHERE friend.person_1 = ?
    )";
    
                $stmt = $con->prepare($sql);
                $stmt->bindValue(1, $this->getPrimaryKey(), PDO::PARAM_INT);
                $stmt->bindValue(2, $this->getPrimaryKey(), PDO::PARAM_INT);
                $stmt->execute();
    
                $this->listEqualNestFriendsPKs = $stmt->fetchAll(PDO::FETCH_COLUMN);
            }
        }
    }
    
    /**
     * Clears out the collection of Equal Nest Friends *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to the accessor method.
     *
     * @see addFriend()
     * @see setFriends()
     * @see removeFriends()
     */
    public function clearFriends()
    {
        $this->collEqualNestFriends = null;
    }
    
    /**
     * Initializes the collEqualNestFriends collection.
     *
     * By default this just sets the collEqualNestFriends collection to an empty PropelObjectCollection;
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database (ie, calling getFriends).
     */
    protected function initFriends()
    {
        $this->collEqualNestFriends = new PropelObjectCollection();
        $this->collEqualNestFriends->setModel('PropelORM\Model\Person');
    }
    
    /**
     * Removes all Equal Nest Friends relations
     *
     * @see addFriend()
     * @see setFriends()
     */
    public function removeFriends()
    {
        foreach ($this->getFriends() as $obj) {
            $obj->removeFriend($this);
        }
    }
    
    /**
     * Gets an array of Person objects which are Equal Nest Friends of this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Person object is new, it will return an empty collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria
     * @param      PropelPDO $con
     * @return     PropelObjectCollection Person[] List of Equal Nest Friends of this Person.
     * @throws     PropelException
     */
    public function getFriends(Criteria $criteria = null, PropelPDO $con = null)
    {
        if (null === $this->listEqualNestFriendsPKs) {
            $this->initListFriendsPKs($con);
        }
    
        if (null === $this->collEqualNestFriends || null !== $criteria) {
            if (0 === count($this->listEqualNestFriendsPKs) && null === $this->collEqualNestFriends) {
                // return empty collection
                $this->initFriends();
            } else {
                $newCollection = PersonQuery::create(null, $criteria)
                    ->addUsingAlias(PersonPeer::ID, $this->listEqualNestFriendsPKs, Criteria::IN)
                    ->find($con);
    
                if (null !== $criteria) {
                    return $newCollection;
                }
    
                $this->collEqualNestFriends = $newCollection;
            }
        }
    
        return $this->collEqualNestFriends;
    }
    
    /**
     * Set an array of Person objects as Friends of the this object
     *
     * @param  Person[] $objects The Person objects to set as Friends of the current object
     * @throws PropelException
     * @see    addFriend()
     */
    public function setFriends($objects)
    {
        $this->clearFriends();
        foreach ($objects as $aFriend) {
            if (!$aFriend instanceof Person) {
                throw new PropelException(sprintf(
                    '[Equal Nest] Cannot set object of type %s as Friend, expected Person',
                    is_object($aFriend) ? get_class($aFriend) : gettype($aFriend)
                ));
            }
    
            $this->addFriend($aFriend);
        }
    }
    
    /**
     * Checks for Equal Nest relation
     *
     * @param  Person $aFriend The object to check for Equal Nest Friend relation to the current object
     * @return boolean
     */
    public function hasFriend(Person $aFriend)
    {
        if (null === $this->collEqualNestFriends) {
            $this->getFriends();
        }
    
        return $aFriend->isNew() || $this->isNew()
            ? in_array($aFriend, $this->collEqualNestFriends->getArrayCopy())
            : in_array($aFriend->getPrimaryKey(), $this->collEqualNestFriends->getPrimaryKeys());
    }
    
    /**
     * Method called to associate another Person object as a Friend of this one
     * through the Equal Nest Friends relation.
     *
     * @param  Person $aFriend The Person object to set as Equal Nest Friends relation of the current object
     * @throws PropelException
     */
    public function addFriend(Person $aFriend)
    {
        if (!$this->hasFriend($aFriend)) {
            $this->collEqualNestFriends[] = $aFriend;
            $aFriend->addFriend($this);
        }
    }
    
    /**
     * Method called to associate multiple Person objects as Equal Nest Friends of this one
     *
     * @param   Person[] Friends The Person objects to set as
     *          Equal Nest Friends relation of the current object.
     * @throws  PropelException
     */
    public function addFriends($Friends)
    {
        foreach ($Friends as $aFriends) {
            $this->addFriend($aFriends);
        }
    }
    
    /**
     * Method called to remove a Person object from the Equal Nest Friends relation
     *
     * @param  Person $friend The Person object
     *         to remove as a Friend of the current object
     * @param  PropelPDO $con
     * @throws PropelException
     */
    public function removeFriend(Person $friend, PropelPDO $con = null)
    {
        if (null === $this->collEqualNestFriends) {
            $this->getFriends(null, $con);
        }
    
        if ($this->collEqualNestFriends->contains($friend)) {
            $this->collEqualNestFriends->remove($this->collEqualNestFriends->search($friend));
    
            $coll = $friend->getFriends(null, $con);
            if ($coll->contains($this)) {
                $coll->remove($coll->search($this));
            }
        } else {
            throw new PropelException(sprintf('[Equal Nest] Cannot remove Friend from Equal Nest relation because it is not set as one!'));
        }
    }
    
    /**
     * Returns the number of Equal Nest Friends of this object.
     *
     * @param      Criteria   $criteria
     * @param      boolean    $distinct
     * @param      PropelPDO  $con
     * @return     integer    Count of Friends
     * @throws     PropelException
     */
    public function countFriends(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->listEqualNestFriendsPKs) {
            $this->initListFriendsPKs($con);
        }
    
        if (null === $this->collEqualNestFriends || null !== $criteria) {
            if ($this->isNew() && null === $this->collEqualNestFriends) {
                return 0;
            } else {
                $query = PersonQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }
    
                return $query
                    ->addUsingAlias(PersonPeer::ID, $this->listEqualNestFriendsPKs, Criteria::IN)
                    ->count($con);
            }
        } else {
            return count($this->collEqualNestFriends);
        }
    }

}
