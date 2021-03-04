<?php


namespace Amasty\Xnotif\Block\Adminhtml\Stock\Renderer;

class Action extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    public function render(\Magento\Framework\DataObject $row)
    {
        $class = "grid-button-action";
        $send_url = $this->getUrl('xnotif/stock/sendforproductid', array('id' => $row->getProductId()));


        $button_send = '<a href="' . $send_url . '" class="' . $class . '"><span>' . __('Send For Sku') . '</span></a>';


        return $button_send;
    }

}
