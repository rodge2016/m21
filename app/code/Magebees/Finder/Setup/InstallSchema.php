<?php 
namespace Magebees\Finder\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

		$table = $installer->getConnection()
            ->newTable($installer->getTable('magebees_finder'))
            ->addColumn(
                'finder_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true,'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Finder ID'
            )
			->addColumn('title', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('number_of_dropdowns', Table::TYPE_SMALLINT, 6, ['nullable' => false])
            ->addColumn('dropdown_style', Table::TYPE_TEXT, 100, ['nullable' => false])
			->addColumn('no_of_columns', Table::TYPE_SMALLINT, 6, ['nullable' => false])
            ->addColumn('category_ids', Table::TYPE_TEXT, 255, ['nullable' => false])
			->addColumn('status', Table::TYPE_BOOLEAN, ['nullable' => false])
			->addColumn('created_time', Table::TYPE_DATETIME, ['nullable' => false])
            ->addColumn('update_time', Table::TYPE_DATETIME, ['nullable' => false])
			->setComment('Magebees YMM Finders Details');

        $installer->getConnection()->createTable($table);
		
		$table = $installer->getConnection()
            ->newTable($installer->getTable('magebees_finder_dropdowns'))
            ->addColumn(
                'dropdown_id',
                Table::TYPE_INTEGER,
                null,
                [
				'identity' => true,'unsigned' => true, 'nullable' => false, 'primary' => true
				]
            )
            ->addColumn('finder_id', Table::TYPE_INTEGER, 10, ['nullable' => false,'unsigned' => true])
            ->addColumn('name', Table::TYPE_TEXT, 255, ['nullable' => false])
			->addColumn('sort', Table::TYPE_TEXT, 255, ['nullable' => false])
			->addIndex(
                $installer->getIdxName('IDX_FINDER_DROPDOWN_ID', ['finder_id']),
                ['finder_id']
            )
			->addForeignKey(
                    $installer->getFkName(
                        'magebees_finder_dropdowns',
                        'finder_id',
                        'magebees_finder',
                        'finder_id'
                    ),
                    'finder_id',
                    $installer->getTable('magebees_finder'),
                    'finder_id',
                    Table::ACTION_CASCADE,
                    Table::ACTION_CASCADE
			)
			->setComment('Finder To Dropdown ids Relations');

        $installer->getConnection()->createTable($table);
		
		$table = $installer->getConnection()
            ->newTable($installer->getTable('magebees_finder_products'))
            ->addColumn(
                'finder_product_id',
                Table::TYPE_INTEGER,
                null,
                [
				'identity' => true,'unsigned' => true, 'nullable' => false, 'primary' => true
				]
            )
            ->addColumn('finder_id', Table::TYPE_INTEGER, 10, ['nullable' => false,'unsigned' => true])
            ->addColumn('sku', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field1', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field2', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field3', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field4', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field5', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field6', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field7', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field8', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field9', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field10', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field11', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field12', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field13', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field14', Table::TYPE_TEXT, 255, ['nullable' => false])
            ->addColumn('field15', Table::TYPE_TEXT, 255, ['nullable' => false])
			->addIndex(
                $installer->getIdxName('IDX_FINDER_PRODUCT_ID', ['finder_id']),
                ['finder_id']
            )
			->addForeignKey(
                    $installer->getFkName(
                        'magebees_finder_products',
                        'finder_id',
                        'magebees_finder',
                        'finder_id'
                    ),
                    'finder_id',
                    $installer->getTable('magebees_finder'),
                    'finder_id',
                    Table::ACTION_CASCADE,
                    Table::ACTION_CASCADE
			)
			->setComment('Finder To Product SKUs Relations');

        $installer->getConnection()->createTable($table);
		
        $installer->endSetup();
    }

}
