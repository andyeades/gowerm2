<?php

namespace Elevate\LandingPages\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeData implements UpgradeDataInterface
{
	protected $eavSetupFactory;

	public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory)
	{
		$this->eavSetupFactory = $eavSetupFactory;
	}


	public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
	{
    
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        //landing page attributes
        //bottom description
        //include filters
        //exclude filters
        //top pages
        //h1 overrides
      
        $installer = $setup;
   $installer->startSetup();
         $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
           
        if (version_compare($context->getVersion(), '0.0.6', '<')) {
        
        
 
         $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_hide_leftnav',
            [
                'type' => 'int',
                'label' => 'Hide Left Nav',
                'input' => 'select',
                'sort_order' => 333,
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'group' => '',
                'backend' => ''
            ]
        );
     
     
         $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_noindex',
            [
                'type' => 'int',
                'label' => 'No Index',
                'input' => 'select',
                'sort_order' => 333,
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'group' => '',
                'backend' => ''
            ]
        );
     
           $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_canonical',
            [
                'type'         => 'varchar',
                'label'        => 'Canonical Tag',
                'input'        => 'text',
                'sort_order'   => 100,
                'source'       => '',
                'global'       => 1,
                'visible'      => true,
                'required'     => false,
                'visible_on_front' => true,
                'user_defined' => false,
                'default'      => null,
                'group'        => '',
                'backend'      => ''
            ]
        );       
        
        
        
        
        
        
        }
   
                 
      if (version_compare($context->getVersion(), '0.0.7', '<')) {
     
     
        $setup->getConnection()->addColumn(
        $setup->getTable('cms_page'), 'ev_landingpages_noindex', [
    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
    'length' => 11,
    'default' => '0',
    'nullable' => true,
    'comment' => 'No Index'
        ]
        );


      
        $setup->getConnection()->addColumn(
        $setup->getTable('cms_page'), 'ev_landingpages_canonical', [
    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
    'length' => '255',
    'nullable' => true,
    'comment' => 'Canonical'
        ]
        );



        
        }
   
   
           if (version_compare($context->getVersion(), '0.0.9', '<')) {
   $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
           
                 $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'ev_landingpages_canonical');
                   $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'ev_landingpages_canonical',
            [
                'type'         => 'varchar',
                'label'        => 'Canonical Tag',
                'input'        => 'text',
                'sort_order'   => 110,
                'source'       => '',
                'global'       => 1,
                'visible'      => true,
                'required'     => false,
                'visible_on_front' => true,
                'user_defined' => false,
                'default'      => null,
                'group'        => 'Search Engine Optimization',
                'backend'      => ''
            ]
        );       
                     
        
                $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'ev_landingpages_noindex');
         $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'ev_landingpages_noindex',
            [
                'type' => 'int',
                'label' => 'No Index',
                'input' => 'select',
                'sort_order' => 120,
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => 0,
                'group' => 'Search Engine Optimization',
                'backend' => ''
            ]
        );
   
        
        

        }
          $installer->endSetup();  
        
    }
}