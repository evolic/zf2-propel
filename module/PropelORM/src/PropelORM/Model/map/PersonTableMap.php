<?php

namespace PropelORM\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'person' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator..map
 */
class PersonTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.map.PersonTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('person');
        $this->setPhpName('Person');
        $this->setClassname('PropelORM\\Model\\Person');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('FriendRelatedByPerson1', 'PropelORM\\Model\\Friend', RelationMap::ONE_TO_MANY, array('id' => 'person_1', ), 'CASCADE', null, 'FriendsRelatedByPerson1');
        $this->addRelation('FriendRelatedByPerson2', 'PropelORM\\Model\\Friend', RelationMap::ONE_TO_MANY, array('id' => 'person_2', ), 'CASCADE', null, 'FriendsRelatedByPerson2');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'equal_nest_parent' =>  array (
  'middle_table' => 'friend',
),
        );
    } // getBehaviors()

} // PersonTableMap
