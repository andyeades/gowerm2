<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Block\Adminhtml\System\Config\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\UrlInterface;

/**
 * Class IncludeTax
 * @package Aheadworks\Giftcard\Block\Adminhtml\System\Config\Form\Field
 */
class IncludeTax extends Field
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param Context $context
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        Context $context,
        UrlInterface $urlBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data, null);
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritdoc
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $element->setComment($this->prepareComment());
        return $element->getElementHtml();
    }

    /**
     * Prepare comment
     *
     * @return string
     */
    private function prepareComment()
    {
        $taxLink = $this->urlBuilder->getUrl('adminhtml/system_config/edit/section/tax');
        return __(
            'You must to enable Cross Border Trade for correct catalog price. Click <a href=%1>here</a> to edit',
            $taxLink
        );
    }
}
