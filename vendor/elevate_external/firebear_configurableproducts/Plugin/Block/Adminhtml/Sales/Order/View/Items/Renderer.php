<?php

namespace Firebear\ConfigurableProducts\Plugin\Block\Adminhtml\Sales\Order\View\Items;

use Closure;
use Magento\Bundle\Block\Adminhtml\Sales\Order\View\Items\Renderer as Render;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

class Renderer
{
    protected $eavAttribute;

    /**
     * Renderer constructor.
     * @param Attribute $eavAttribute
     */
    public function __construct(Attribute $eavAttribute)
    {
        $this->eavAttribute = $eavAttribute;
    }

    /**
     * @param Render $subject
     * @param Closure $proceed
     * @param $item
     * @return string
     */
    public function aroundGetValueHtml(Render $subject, Closure $proceed, $item)
    {
        if ($item->getProductType() == 'configurable') {
            $result = $subject->escapeHtml($item->getProductOptions()['simple_name']);
        } else {
            $result = $subject->escapeHtml($item->getName());
        }
        if (!$subject->isShipmentSeparately($item)) {
            $attributes = $subject->getSelectionAttributes($item);
            if ($attributes) {
                $result = sprintf('%d', $attributes['qty']) . ' x ' . $result;
            }
        }
        if (!$subject->isChildCalculated($item)) {
            $attributes = $subject->getSelectionAttributes($item);
            if ($attributes) {
                $result .= " " . $subject->getItem()->getOrder()->formatPrice($attributes['price']);
            }
        }
        if (isset($item->getProductOptions()['options'])) {
            foreach ($item->getProductOptions()['options'] as $option) {
                $result .= '<div style="margin-left: 28px;">' .
                    '<i>' .
                    $option['label'] .
                    ': ' .
                    $option['value'] .
                    '</i><br />' .
                    '</div>';
            }
        }
        return $result;
    }
}
