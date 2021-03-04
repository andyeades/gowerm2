<?php

declare(strict_types=1);

namespace Elevate\EmailPreview\Block;

class Dashboard extends \Magento\Framework\View\Element\Template
{
    protected $_storeHelper;

    /**
     * @var string
     */
    protected $_template = 'Elevate_EmailPreview::dashboard/index.phtml';


    public function __construct(\Magento\Backend\Block\Template\Context $context,
                                \Elevate\EmailPreview\Helper\Store $storeHelper,
                                array $data = [])
    {
        $this->_storeHelper = $storeHelper;

        parent::__construct($context, $data);
    }

    /**
     * Prepare html output
     *
     * @return string
     */
    protected function _toHtml()
    {

        return parent::_toHtml();
    }

    public function getStoreId(){

        $selectedStore = (int)$this->getRequest()->getParam('store');
        return $this->_storeHelper->getStoreId($selectedStore);
    }
    /**
     * @return string
     */
    public function getSwitchUrl()
    {
        if ($url = $this->getData('switch_url')) {
            return $url;
        }
        return $this->getUrl('adminhtml/*/*', ['_current' => true, 'period' => null]);
    }



}
