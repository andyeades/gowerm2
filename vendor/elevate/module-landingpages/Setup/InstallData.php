<?php
namespace Elevate\LandingPages\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    )
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        //landing page attributes
        //bottom description
        //include filters
        //exclude filters
        //top pages
        //h1 overrides
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_include_filters',
            [
                'type'         => 'varchar',
                'label'        => 'Include Filters',
                'input'        => 'text',
                'visible_on_front' => true,
                'sort_order'   => 100,
                'source'       => '',
                'global'       => 1,
                'visible'      => true,
                'required'     => false,
                'user_defined' => false,
                'default'      => null,
                'group'        => '',
                'backend'      => ''
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_exclude_filters',
            [
                'type'         => 'varchar',
                'label'        => 'Exclude Filters',
                'input'        => 'text',
                'sort_order'   => 100,
                'source'       => '',
                'global'       => 1,
                'visible'      => true,
                'visible_on_front' => true,
                'required'     => false,
                'user_defined' => false,
                'default'      => null,
                'group'        => '',
                'backend'      => ''
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_top_pages',
            [
                'type'         => 'varchar',
                'label'        => 'Top Pages',
                'input'        => 'text',
                'sort_order'   => 100,
                'source'       => '',
                'global'       => 1,
                'visible'      => true,
                'required'     => false,
                'user_defined' => false,
                'default'      => null,
                'group'        => '',
                'backend'      => ''
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_h1_override',
            [
                'type'         => 'varchar',
                'label'        => 'H1 Override',
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
        
            if(!$eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, 'ev_landingpages_btm_desc')) {
       $eavSetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, 'ev_landingpages_btm_desc');                                                        
}
        
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_btm_desc',
            [
                'type'         => 'text',
                'label'        => 'Bottom Description',
                'input'        => 'textarea',
                'wysiwyg_enabled' => true,
                'sort_order'   => 100,
                'source'       => '',
                'global'       => 1,
                'visible_on_front' => true,
                'visible'      => true,
                'is_html_allowed_on_front' => true,
                'required'     => false,
                'user_defined' => false,
                'default'      => null,
                'group'        => '',
                'backend'      => ''
            ]
        );
        
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_top_desc_ajax',
            [
                'type'         => 'text',
                'label'        => 'Top Description Ajax',
                'input'        => 'textarea',
                'wysiwyg_enabled' => true,
                'sort_order'   => 100,
                'source'       => '',
                'global'       => 1,
                'visible_on_front' => true,
                'visible'      => true,
                'is_html_allowed_on_front' => true,
                'required'     => false,
                'user_defined' => false,
                'default'      => null,
                'group'        => '',
                'backend'      => ''
            ]
        );
        
                $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_btm_desc_ajax',
            [
                'type'         => 'text',
                'label'        => 'Bottom Description Attribute',
                'input'        => 'textarea',
                'wysiwyg_enabled' => true,
                'sort_order'   => 100,
                'source'       => '',
                'global'       => 1,
                'visible_on_front' => true,
                'visible'      => true,
                'is_html_allowed_on_front' => true,
                'required'     => false,
                'user_defined' => false,
                'default'      => null,
                'group'        => '',
                'backend'      => ''
            ]
        );  
        
                $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_top_desc_type',
            [
                'type'         => 'varchar',
                'label'        => 'Top description type',
                'input'        => 'select',
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
        
                $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_btm_desc_type',
            [
                'type'         => 'varchar',
                'label'        => 'bottom description type',
                'input'        => 'select',
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
        
         $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'ev_landingpages_hide_readmore',
            [
                'type' => 'int',
                'label' => 'Hide Readmore',
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
     
  
        
        
        
    }
}