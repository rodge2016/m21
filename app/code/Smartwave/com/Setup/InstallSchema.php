<?php
namespace Smartwave\com\Setup;
/**
 * Copyright © 2016 BL. All rights reserved.
 *
 */
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        /**
         * Create table 'compatibility'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('compatibility'))
            ->addColumn(
                'c_table_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'c_table_id'
            )
            ->addColumn(
                'ebay_pid',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [ 'unsigned' => true, 'nullable' => false ],
                'ebay_pid'
            )
            ->addColumn(
                'year',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'year'
            )
            ->addColumn(
                'make',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'simple'],
                'make'
            )
            ->addColumn(
                'model',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false, 'default' => 'simple'],
                'model'
            )
            ->addColumn(
                'submodel',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'submodel'
            )
            ->addColumn(
                'engine',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'engine'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Update Time'
            )
            ->addIndex(
                $installer->getIdxName('compatibility', ['c_table_id']),
                ['c_table_id']
            )
            ->addIndex(
                $installer->getIdxName('compatibility', ['ebay_pid']),
                ['ebay_pid']
            )
            ->addIndex(
                $installer->getIdxName('compatibility', ['year']),
                ['year']
            )
            ->addIndex(
                $installer->getIdxName('compatibility', ['make']),
                ['make']
            )
            ->addIndex(
                $installer->getIdxName('compatibility', ['model']),
                ['model']
            )

            ->setComment(
                'compatibility Table'
            );
        $installer->getConnection()->createTable($table);



        /**
         * Create table 'relation_id'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('relation_id'))
            ->addColumn(
                'r_table_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'r_table_id '
            )
            ->addColumn(
                'c_table_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, ],
                'c_table_id'
            )
            ->addColumn(
                'sku',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                64,
                [],
                'sku'
            )
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [ 'unsigned' => true, 'nullable' => false, ],
                'entity_id'
            )
            ->addIndex(
                $installer->getIdxName('relation_id', ['r_table_id']),
                ['r_table_id']
            )
            ->addIndex(
                $installer->getIdxName('relation_id', ['c_table_id']),
                ['c_table_id']
            )
            ->addIndex(
                $installer->getIdxName('relation_id', ['sku']),
                ['sku']
            )
            ->addIndex(
                $installer->getIdxName('relation_id', ['entity_id']),
                ['entity_id']
            )
            ->setComment('compatibility and product_id Index Table');
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}