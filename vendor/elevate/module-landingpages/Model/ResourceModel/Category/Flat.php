<?php
namespace  Elevate\LandingPages\Model\ResourceModel\Category;

class Flat extends \Magento\Catalog\Model\ResourceModel\Category\Flat {
    /**
     * Return children categories of category
     *
     * @param \Magento\Catalog\Model\Category $category
     * @return \Magento\Catalog\Model\Category[]
     */
    public function getChildrenCategories($category)
    {
        $categories = $this->_loadNodes($category, 1, $category->getStoreId(), false);
        return $categories;
    }
}