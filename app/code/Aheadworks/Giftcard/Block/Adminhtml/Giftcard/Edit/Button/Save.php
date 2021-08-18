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
namespace Aheadworks\Giftcard\Block\Adminhtml\Giftcard\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Aheadworks\Giftcard\Model\GiftcardRepository;
use Magento\Backend\Block\Widget\Context;

/**
 * Class Save
 *
 * @package Aheadworks\Giftcard\Block\Adminhtml\Giftcard\Edit\Button
 */
class Save implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var GiftcardRepository
     */
    private $giftcardRepository;

    /**
     * @param Context $context
     * @param GiftcardRepository $giftcardRepository
     */
    public function __construct(
        Context $context,
        GiftcardRepository $giftcardRepository
    ) {
        $this->context = $context;
        $this->giftcardRepository = $giftcardRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        $id = $this->getId();
        return [
            'label' => __('Save'),
            'class' => 'save' . ($id ? ' primary' : ''),
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
                'new-entity' => $id ? '' : 'save-button'
            ],
            'sort_order' => $id ? 70 : 60,
        ];
    }

    /**
     * Retrieve gift card id
     *
     * @return int|null
     */
    public function getId()
    {
        $id = $this->context->getRequest()->getParam('id');
        if ($id && $this->giftcardRepository->get($id)) {
            return $this->giftcardRepository->get($id)->getId();
        }
        return null;
    }
}
