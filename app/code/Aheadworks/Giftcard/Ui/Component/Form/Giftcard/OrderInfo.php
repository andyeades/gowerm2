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
namespace Aheadworks\Giftcard\Ui\Component\Form\Giftcard;

use Aheadworks\Giftcard\Model\Source\Entity\Attribute\GiftcardType;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Aheadworks\Giftcard\Api\GiftcardRepositoryInterface;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class OrderInfo
 *
 * @package Aheadworks\Giftcard\Ui\Component\Form\Giftcard
 */
class OrderInfo extends \Aheadworks\Giftcard\Ui\Component\Form\Field
{
    /**
     * @var GiftcardRepositoryInterface
     */
    private $giftcardRepository;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var GiftcardType
     */
    private $sourceGiftcardType;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param GiftcardRepositoryInterface $giftcardRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param ProductRepositoryInterface $productRepository
     * @param GiftcardType $sourceGiftcardType
     * @param Logger $logger
     * @param UiComponentInterface[] $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        GiftcardRepositoryInterface $giftcardRepository,
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,
        GiftcardType $sourceGiftcardType,
        Logger $logger,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $giftcardRepository, $logger, $components, $data);
        $this->giftcardRepository = $giftcardRepository;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->sourceGiftcardType = $sourceGiftcardType;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        parent::prepareDataSource($dataSource);
        $config = $this->getData('config');
        if (isset($dataSource['data']['order_id']) && $dataSource['data']['order_id']
            && isset($dataSource['data']['product_id']) && $dataSource['data']['product_id']
        ) {
            try {
                $order = $this->orderRepository->get($dataSource['data']['order_id']);
                $product = $this->productRepository->getById($dataSource['data']['product_id']);
            } catch (NoSuchEntityException $e) {
                return $dataSource;
            }

            switch ($config['dataScope']) {
                case 'product':
                    $dataSource['data']['product_url'] = $this->getUrl(
                        'aw_giftcard_admin/product/edit',
                        ['id' => $product->getId(), 'awgcBack' => 'editCode', 'awgcId' => $dataSource['data']['id']]
                    );
                    $dataSource['data']['product_label'] = $product->getName();
                    $dataSource['data']['product_after'] =
                        '(' . $this->sourceGiftcardType->getOptionText($product->getAwGcType()) . ')';
                    break;
                case 'order':
                    $dataSource['data']['order_url'] = $this->getUrl(
                        'sales/order/view',
                        ['order_id' => $order->getEntityId()]
                    );
                    $dataSource['data']['order_label'] = '#' . $order->getIncrementId();
                    break;
                case 'customer':
                    if ($order->getCustomerId()) {
                        $dataSource['data']['customer_url'] = $this->getUrl(
                            'customer/index/edit',
                            ['id' => $order->getCustomerId()]
                        );
                        $dataSource['data']['customer_label'] = $order->getCustomerName();
                    } else {
                        $dataSource['data']['customer_label'] = $order->getBillingAddress()->getName()
                            . '(' . $order->getCustomerEmail() . ')';
                    }
                    break;
            }
        }
        return $dataSource;
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    private function getUrl($route = '', $params = [])
    {
        return $this->getContext()->getUrl($route, $params);
    }
}
