<?php

namespace PropelORM\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'songs' table.
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
class SongsTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.map.SongsTableMap';

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
        $this->setName('songs');
        $this->setPhpName('Songs');
        $this->setClassname('PropelORM\\Model\\Songs');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('album_id', 'AlbumId', 'INTEGER', 'album', 'id', true, null, null);
        $this->addColumn('position', 'Position', 'SMALLINT', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 64, null);
        $this->addColumn('duration', 'Duration', 'TIME', true, null, null);
        $this->addColumn('disc', 'Disc', 'SMALLINT', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Album', 'PropelORM\\Model\\Album', RelationMap::MANY_TO_ONE, array('album_id' => 'id', ), null, null);
    } // buildRelations()

} // SongsTableMap
