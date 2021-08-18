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
namespace Aheadworks\Giftcard\Block\Adminhtml\Pool\Edit\Button;

use Aheadworks\Giftcard\Api\PoolRepositoryInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;

/**
 * Class Delete
 *
 * @package Aheadworks\Giftcard\Block\Adminhtml\Pool\Edit\Button
 */
class Delete implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var PoolRepositoryInterface
     */
    private $poolRepository;

    /**
     * @param Context $context
     * @param PoolRepositoryInterface $poolRepository
     */
    public function __construct(
        Context $context,
        PoolRepositoryInterface $poolRepository
    ) {
        $this->context = $context;
        $this->poolRepository = $poolRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        $data = [];
        if ($id = $this->getId()) {
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => sprintf(
                    "deleteConfirm('%s', '%s')",
                    __('Are you sure you want to do this?'),
                    $this->getUrl('*/*/delete', ['id' => $id])
                ),
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * Retrieve pool id
     *
     * @return int|null
     */
    public function getId()
    {
        $id = $this->context->getRequest()->getParam('id');
        if ($id && $this->poolRepository->get($id)) {
            return $this->poolRepository->get($id)->getId();
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
