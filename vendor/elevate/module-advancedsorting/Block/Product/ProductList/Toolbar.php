<?php
namespace Elevate\AdvancedSorting\Block\Product\ProductList;

class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar {
  public function setCollection($collection) {
    $this->_collection = $collection;
    $this->_collection->setCurPage($this->getCurrentPage());
    $limit = (int) $this->getLimit();
    if ($limit) {
        $this->_collection->setPageSize($limit);
    }
   $currentOrder = $this->getCurrentOrder();
       
		if ($currentOrder) {
        if ($currentOrder == 'bestseller') {
            $this->_collection->setOrder('bestseller', 'desc');
        }
        elseif ($currentOrder == 'price_desc') {                 
				$this->_collection->setOrder('price', 'desc');
			} elseif ($currentOrder == 'price_asc') {
				$this->_collection->setOrder('price', 'asc');
			}
		}     
    
    return $this;
  }
          public function setAvailableOrders($orders)
    {
    
  
        $this->_availableOrder = $orders;
       
        	$this->_availableOrder['price_desc'] = __('Price - High to Low' );
		$this->_availableOrder['price_asc'] = __('Price - Low to High' );    
        return $this;
    }
}