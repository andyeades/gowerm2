<?php
/**
 * Front end class for showing the version number in the configuration for Purchase Order
 */

namespace Punchout2go\Xframe\Block\Forms\Config;


use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Punchout2go\Xframe\Helper\Data as Helper;

class Version extends Field
{
    protected $helper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Helper $helper,
        array $data = []
    )
    {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return __(
            '<label class="label"><span>' . $this->helper->getModuleVersion() . '</span></label>'
        );
    }
}