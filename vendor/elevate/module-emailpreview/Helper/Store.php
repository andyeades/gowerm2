<?php

namespace Elevate\EmailPreview\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Store extends AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
    }

    protected function getAnyStoreView()
    {
        $store = $this->_storeManager->getDefaultStoreView();
        if ($store) {
            return $store;
        }
        foreach ($this->_storeManager->getStores() as $store) {
            return $store;
        }
        return null;
    }

    public function getStoreId($selectedStore)
    {
        $storeId = (is_numeric ($selectedStore)) ? $selectedStore : $this->getAnyStoreView()->getId();
        return $storeId;
    }
}