<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\LandingPages\Block\Category;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Category;

class View extends \Magento\Catalog\Block\Category\View
{
    protected $_urlInterface;
    protected $_stateFilter;
    protected $_storeManager;
    protected $_filterProvider;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\LayeredNavigation\Block\Navigation\State $stateFilter,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
        
        array $data = []
    ) {
 $this->_urlInterface = $urlInterface;
        $this->_categoryHelper = $categoryHelper;
        $this->_catalogLayer = $layerResolver->get();
        $this->_coreRegistry = $registry;
        $this->_stateFilter = $stateFilter;
        $this->_storeManager = $storeManager;
        $this->_filterProvider = $filterProvider;
        parent::__construct($context, $layerResolver, $registry, $categoryHelper, $data);
        
    }
 
 	public function getFilterProvider()
	{
		return $this->_filterProvider;
	}
    
    	public function getStoreManager()
	{
		return $this->_storeManager;
	}               
public function getStateFilter(){

return $this->_stateFilter;
}
      protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->getLayout()->createBlock(\Magento\Catalog\Block\Breadcrumbs::class);

        $category = $this->getCurrentCategory();
        if ($category) {
            $title = $category->getMetaTitle();
            if ($title) {
                $this->pageConfig->getTitle()->set($title);
            }
            $description = $category->getMetaDescription();
            if ($description) {
                $this->pageConfig->setDescription($description);
            }
            $keywords = $category->getMetaKeywords();
            if ($keywords) {
                $this->pageConfig->setKeywords($keywords);
            }
            if ($this->_categoryHelper->canUseCanonicalTag()) {
                $this->pageConfig->addRemotePageAsset(
                    $category->getUrl(),
                    'canonical',
                    ['attributes' => ['rel' => 'canonical']]
                );
            }

            $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
            if ($pageMainTitle) {
                $pageMainTitle->setPageTitle($this->getCurrentCategory()->getName());
            }
        }

        return $this;
    }
}