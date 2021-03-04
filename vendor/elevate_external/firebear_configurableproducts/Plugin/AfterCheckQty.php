<?php

namespace Firebear\ConfigurableProducts\Plugin;

class AfterCheckQty
{
    public function afterCheckQty(\Magento\CatalogInventory\Model\StockStateProvider $stockStateProvider, $result)
    {

        return true;
    }
}
