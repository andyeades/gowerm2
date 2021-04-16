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
           elseif ($currentOrder == 'position') {
				$subject->getCollection()->setOrder('position', 'asc');
			}elseif ($currentOrder == 'name') {
				$subject->getCollection()->setOrder('name', 'asc');
			}
            elseif ($currentOrder == 'product_rating') {
				$subject->getCollection()->setOrder('product_rating', 'desc');
			}  
		}     
          

		return $result;
	}
    
    
        public function setAvailableOrders($orders)
    {
    
  
        $this->_availableOrder = $orders;
       
        	$this->_availableOrder['price_desc'] = __('Price : High to Low' );
		$this->_availableOrder['price_asc'] = __('Price : Low to High' );    
        return $this;
    }
}
