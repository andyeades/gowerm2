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
namespace Aheadworks\Giftcard\Ui\Component\Listing\Column\Giftcard;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class Recipient
 *
 * @package Aheadworks\Giftcard\Ui\Component\Listing\Column\Giftcard
 */
class Recipient extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param Logger $logger
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CustomerRepositoryInterface $customerRepository,
        Logger $logger,
        array $components = [],
        array $data = []
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('name');
        foreach ($dataSource['data']['items'] as &$item) {
            try {
                /** @var CustomerInterface $customer */
                $customer = $this->customerRepository->get($item['recipient_email']);
                $item[$fieldName . '_url'] = $this->context->getUrl(
                    'customer/index/edit',
                    ['id' => $customer->getId()]
                );
            } catch (NoSuchEntityException $exception) {
                $this->logger->critical($exception->getMessage());
            }
            $item[$fieldName . '_label'] = $item[$fieldName];
        }
        return $dataSource;
    }
}
