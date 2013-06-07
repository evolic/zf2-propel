<?php

namespace PropelORM\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'friend' table.
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
class FriendTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.map.FriendTableMap';

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
        $this->setName('friend');
        $this->setPhpName('Friend');
        $this->setClassname('PropelORM\\Model\\Friend');
        $this->setPackage('');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('person_1', 'Person1', 'INTEGER' , 'person', 'id', true, null, null);
        $this->addForeignPrimaryKey('person_2', 'Person2', 'INTEGER' , 'person', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PersonRelatedByPerson1', 'PropelORM\\Model\\Person', RelationMap::MANY_TO_ONE, array('person_1' => 'id', ), 'CASCADE', null);
        $this->addRelation('PersonRelatedByPerson2', 'PropelORM\\Model\\Person', RelationMap::MANY_TO_ONE, array('person_2' => 'id', ), 'CASCADE', null);
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
            'equal_nest' =>  array (
  'parent_table' => 'person',
  'reference_column_1' => NULL,
  'reference_column_2' => NULL,
),
        );
    } // getBehaviors()

} // FriendTableMap
