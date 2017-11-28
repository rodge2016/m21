<?php 
namespace Magebees\Finder\Setup;
 
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;
 
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
 
        //handle all possible upgrade versions
 
        if(!$context->getVersion()) {
            //no previous version found, installation, InstallSchema was just executed
            //be careful, since everything below is true for installation !
        }
 
        if (version_compare($context->getVersion(), '1.3.0') < 0) {
            //code to upgrade to 1.3.0
			
			$table = $installer->getConnection()
            ->newTable($installer->getTable('magebees_finder_universal_products'))
            ->addColumn(
                'universal_product_id',
                Table::TYPE_INTEGER,
                null,
                [
				'identity' => true,'unsigned' => true, 'nullable' => false, 'primary' => true
				]
            )
            ->addColumn('finder_id', Table::TYPE_INTEGER, 10, ['nullable' => false,'unsigned' => true])
            ->addColumn('sku', Table::TYPE_TEXT, 255, ['nullable' => false])
			->addColumn('product_id', Table::TYPE_INTEGER, 10, ['nullable' => false])
			->addIndex(
                $installer->getIdxName('IDX_FINDER_UNIVERSAL_PRODUCT_ID', ['finder_id']),
                ['finder_id']
            )
			->addForeignKey(
                    $installer->getFkName(
                        'magebees_finder_universal_products',
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
			->setComment('Finder To Universal Products Relations');

        	$installer->getConnection()->createTable($table);
		}
 
        $installer->endSetup();
    }
}