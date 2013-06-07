<?php

namespace PropelORM\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'album' table.
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
class AlbumTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.map.AlbumTableMap';

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
        $this->setName('album');
        $this->setPhpName('Album');
        $this->setClassname('PropelORM\\Model\\Album');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('artist', 'Artist', 'VARCHAR', true, 100, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 100, null);
        $this->addColumn('discs', 'Discs', 'SMALLINT', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Songs', 'PropelORM\\Model\\Songs', RelationMap::ONE_TO_MANY, array('id' => 'album_id', ), null, null, 'Songss');
    } // buildRelations()

} // AlbumTableMap
