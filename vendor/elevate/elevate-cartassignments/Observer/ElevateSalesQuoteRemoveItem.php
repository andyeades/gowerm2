<?php

namespace Elevate\CartAssignments\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ElevateSalesQuoteRemoveItem implements ObserverInterface
{
    /**
     * Below is the method that will fire whenever the event runs!
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
       // $product = $observer->getProduct();
    //    $originalName = $product->getName();
      //  $modifiedName = $originalName . ' - Modified by Magento 2 Events and Observers';
      //  $product->setName($modifiedName);
    }
}