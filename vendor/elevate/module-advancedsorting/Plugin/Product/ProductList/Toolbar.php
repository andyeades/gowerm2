<?php

namespace Elevate\AdvancedSorting\Plugin\Product\ProductList;

/**
 * Class Toolbar
 * @package Elevate\AdvancedSorting\Plugin\Product\ProductList
 */
class Toolbar
{
	/**
	 * Plugin
	 *
	 * @param \Magento\Catalog\Block\Product\ProductList\Toolbar $subject
	 * @param \Closure $proceed
	 * @param \Magento\Framework\Data\Collection $collection
	 * @return \Magento\Catalog\Block\Product\ProductList\Toolbar
	 */
	public function aroundSetCollection(
		\Magento\Catalog\Block\Product\ProductList\Toolbar $subject,
		\Closure $proceed,
		$collection
	) {

		$currentOrder = $subject->getCurrentOrder();
        $currentOrder = $subject->getCurrentOrder();
		$result = $proceed($collection);

		if ($currentOrder) {
        if ($currentOrder == 'bestseller') {
            $subject->getCollection()->setOrder('bestseller', 'desc');
        }
        elseif ($currentOrder == 'price_desc') {                 
				$subject->getCollection()->setOrder('price', 'desc');
			} elseif ($currentOrder == 'price_asc') {
				$subject->getCollection()->setOrder('price', 'asc');
			}
		}     
          

		return $result;
	}
    
    
        public function setAvailableOrders($orders)
    {
    
    echo "TEST";
    exit;
        $this->_availableOrder = $orders;
       
        	$this->_availableOrder['price_desc'] = __('Price : High to Low' );
		$this->_availableOrder['price_asc'] = __('Price : Low to High' );    
        return $this;
    }
}
