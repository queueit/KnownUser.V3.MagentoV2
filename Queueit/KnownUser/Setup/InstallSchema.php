<?php
namespace Queueit\KnownUser\Setup;
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
     /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $table = $installer->getConnection()->newTable(
            $installer->getTable('queueit_integrationinfo')
        )
        ->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true,'auto_increment'],
            'id'
        )
        ->addColumn(
            'info',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['unsigned' => true, 'nullable' => false],
            'info'
        )
        ->setComment(
            'Integration Configuration Info'
        );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
/*
regenerating database 
delete from setup_module where  module ='module_name';
sudo bin/magento setup:upgrade
sudo bin/magento setup:di:compile
sudo bin/magento cache:clean
*/