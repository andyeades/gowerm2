<?php

namespace Elevate\CartAssignments\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ElevateCheckoutCartAddProductComplete implements ObserverInterface
{
    /**
     * Below is the method that will fire whenever the event runs!
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {

      //after add to cart execution


    }
}