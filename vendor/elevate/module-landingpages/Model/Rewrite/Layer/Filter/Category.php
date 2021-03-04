<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Elevate\LandingPages\Model\Rewrite\Layer\Filter;

use Magento\Catalog\Model\Layer\Filter\DataProvider\Category as CategoryDataProvider;
use Magento\Catalog\Model\Layer\Filter\DataProvider\CategoryFactory;

/**
 * Layer category filter
 */
class Category extends \Magento\Catalog\Model\Layer\Filter\Category
{




    /** @var \Magento\Framework\Escaper */
    private $escaper;

    /** @var  \Magento\Catalog\Model\Layer\Filter\DataProvider\Category */
    private $dataProvider;

    protected $_landingHelper;
   	/** @var bool Is Filterable Flag */
	protected $_isFilter = false;
    /**
     * Category constructor.
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Catalog\Model\Layer\Filter\DataProvider\CategoryFactory $categoryDataProviderFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Escaper $escaper,
        \Elevate\LandingPages\Helper\Data $landingHelper,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\CategoryFactory $categoryDataProviderFactory,
        array $data = []
    )
    {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $escaper,
            $categoryDataProviderFactory,
            $data
        );
        $this->_landingHelper = $landingHelper;
        $this->escaper       = $escaper;
        $this->dataProvider  = $categoryDataProviderFactory->create(['layer' => $this->getLayer()]);
    }

    /**
     * Get filter value for reset current filter state
     *
     * @return mixed|null
     */
    public function getResetValue()
    {
        return $this->dataProvider->getResetValue();
    }

    /**
     * Apply category filter to layer
     *
     * @param   \Magento\Framework\App\RequestInterface $request
     * @return  $this
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {                         
                                   //   $this->getRequestVar()
                             
    //print_r($request->getParams());
        $categoryId = (int)$request->getParam('id');
        
         $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
   $category = $objectManager->get('Magento\Framework\Registry')->registry('current_category');//get current category
    if($category){
        $categoryId = $category->getId(); 
    }
         
        if (!$categoryId) {
    
            return $this;
        }



        $this->dataProvider->setCategoryId($categoryId);
                           
        if ($this->dataProvider->isValid()) {
        
       
            $category = $this->dataProvider->getCategory();
             if((int)$category->getLevel() > 2){
            $this->getLayer()->getProductCollection()->addCategoryFilter($category);
            $this->getLayer()->getState()->addFilter($this->_createItem($category->getName(), $categoryId)); 
            }
        }

        return $this;
    }

    /**
     * Get filter name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getName()
    {
        return __('Category');
    }

    /**
     * Get data array for building category filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        $excludes = array_flip(explode(',', $this->_landingHelper->getLandingPageCategoryExcludes()));


        /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
		$productCollection = $this->getLayer()->getProductCollection();

		if ($this->_isFilter) {
			$productCollection = $productCollection->getCollectionClone()
				->removeAttributeSearch('category_ids');
		}

		$optionsFacetedData = $productCollection->getFacetedData('category');
		$category           = $this->dataProvider->getCategory();
		$categories         = $category->getChildrenCategories();

		$collectionSize = $productCollection->getSize();

		if ($category->getIsActive()) {
		    //print_r($category->getData('include_in_menu'));
			foreach ($categories as $category) {



                if($category->getData('include_in_menu') != 1){
                //    continue;
                }
				$count = isset($optionsFacetedData[$category->getId()]) ? $optionsFacetedData[$category->getId()]['count'] : 0;




				if ($category->getIsActive() && $count > 0) {

					$this->itemDataBuilder->addItemData(
						$this->escaper->escapeHtml($category->getName()),
						$category->getId(),
						$count
					);
				}
			}
		}

		return $this->itemDataBuilder->build();
	}
}
