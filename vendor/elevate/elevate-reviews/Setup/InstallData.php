<?php

namespace Elevate\Reviews\Setup;

 
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
	private $eavSetupFactory;

	public function __construct(EavSetupFactory $eavSetupFactory)
	{
		$this->eavSetupFactory = $eavSetupFactory;
	}
	
	public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
	{
		$eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
		$eavSetup->addAttribute(
			\Magento\Catalog\Model\Product::ENTITY,
			'product_rating',
			[
				'type' => 'text',
				'backend' => '',
				'frontend' => '',
				'label' => 'Product Rating',
				'input' => 'text',
				'class' => '',
				'source' => '',
				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => true,
				'required' => false,
				'user_defined' => false,
				'default' => '',
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => ''
			]
		);
        
        	$eavSetup->addAttribute(
			\Magento\Catalog\Model\Product::ENTITY,
			'rating_count',
			[
				'type' => 'text',
				'backend' => '',
				'frontend' => '',
				'label' => 'Number of Reviews',
				'input' => 'text',
				'class' => '',
				'source' => '',
				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => true,
				'required' => false,
				'user_defined' => false,
				'default' => '',
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => ''
			]
		);
        
            $eavSetup->addAttribute(
			\Magento\Catalog\Model\Product::ENTITY,
			'feefo_link_sku',
			[
				'type' => 'text',
				'backend' => '',
				'frontend' => '',
				'label' => 'Number of Reviews',
				'input' => 'text',
				'class' => '',
				'source' => '',
				'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
				'visible' => true,
				'required' => false,
				'user_defined' => false,
				'default' => '',
				'searchable' => false,
				'filterable' => false,
				'comparable' => false,
				'visible_on_front' => false,
				'used_in_product_listing' => false,
				'unique' => false,
				'apply_to' => ''
			]
		);
           
        $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'filter_rating',
        [
            'type' => 'text',
            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            'frontend' => '',
            'label' => 'Customer Reviews',
            'input' => 'multiselect',
            'class' => '',
    // instead values change your key as value and add your option like below with in `option_1`  array put key as store_id and label as value 
            'option' => ['value' => 
                            [
                            'option_1'=>[ 0=>'1'],
                            'option_2'=>[0=>'2'],
                            'option_3'=>[0=>'3'],
                            'option_4'=>[0=>'4']
                            ],
                           'order'=>//Here We can Set Sort Order For Each Value.
                                 [
                                      'option_1'=>4,
                                      'option_2'=>3,
                                      'option_3'=>2,
                                      'option_4'=>1
                                 ]
                        ], 
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => true,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'wysiwyg_enabled' => true,
            'unique' => false,
            'apply_to' => ''
        ]
    );
 
 
 
	}
}