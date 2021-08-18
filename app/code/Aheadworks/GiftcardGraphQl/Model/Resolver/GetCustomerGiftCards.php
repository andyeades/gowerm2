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
 * @package    GiftcardGraphQl
 * @version    1.0.0
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\GiftcardGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Reflection\DataObjectProcessor;
use Aheadworks\Giftcard\Api\GiftcardManagementInterface;
use Aheadworks\Giftcard\Api\Data\GiftcardInterface;

/**
 * Class GetCustomerGiftCards
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver
 */
class GetCustomerGiftCards implements ResolverInterface
{
    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var GiftcardManagementInterface
     */
    private $giftcardManagement;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     * @param GiftcardManagementInterface $giftcardManagement
     */
    public function __construct(
        DataObjectProcessor $dataObjectProcessor,
        GiftcardManagementInterface $giftcardManagement
    ) {
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->giftcardManagement = $giftcardManagement;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $customerEmail = $args['customer_email'] ?? null;
        $cartId = $args['customer_email'] ?? null;
        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();

        $giftcards = $this->giftcardManagement->getCustomerGiftcards(
            $customerEmail,
            $cartId,
            $storeId
        );

        $result = [];
        foreach ($giftcards as $giftcard) {
            $result['items'][] = $this->dataObjectProcessor->buildOutputDataArray(
                $giftcard,
                GiftcardInterface::class
            );
        }

        return $result;
    }
}
