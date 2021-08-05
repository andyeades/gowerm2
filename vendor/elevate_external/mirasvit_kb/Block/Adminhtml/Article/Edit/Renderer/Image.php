<?php

namespace Mirasvit\Kb\Block\Adminhtml\Article\Renderer;

use Magento\Store\Model\StoreManagerInterface;

class Image extends \Magento\Framework\Data\Form\Element\AbstractElement {

    private $_storeManager;
    /**
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(\Magento\Backend\Block\Context $context,
                                StoreManagerInterface $storemanager, array $data = [])
    {
        $this->_storeManager = $storemanager;
        parent::__construct($context, $data);
    }
    /**

    /**
     * get category name
     *
     * @param DataObject $row
     *
     * @return string
     */
    public function getElementHtml() {
        // here you can write your code.
        $html = '';

        echo $this->getValue();
        die();
        if ($this->getValue()) {
            $html = $this->getMediaImageHtml($this->getValue());
        }

        return $html;
    }

    public function getMediaImageHtml($imageName) {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );

        $Image = $this->getValue();
        $html = "<img src='" . $mediaDirectory . 'mirasvit/kb/article/'  . $Image . "' height='250px' width='250px'>";

        return $html;
    }
}
