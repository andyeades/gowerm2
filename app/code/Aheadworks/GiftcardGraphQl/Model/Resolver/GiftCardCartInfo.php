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
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Aheadworks\Giftcard\Api\GiftcardCartManagementInterface;
use Aheadworks\Giftcard\Api\Data\Giftcard\QuoteInterface;

/**
 * Class GiftCardCartInfo
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver
 */
class GiftCardCartInfo extends AbstractResolver
{
    /**
     * @var GetCartForUser
     */
    private $getCartForUser;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var GiftcardCartManagementInterface
     */
    private $giftcardManagement;

    /**
     * @param GetCartForUser $getCartForUser
     * @param GiftcardCartManagementInterface $giftcardManagement
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        GetCartForUser $getCartForUser,
        GiftcardCartManagementInterface $giftcardManagement,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->getCartForUser = $getCartForUser;
        $this->giftcardManagement = $giftcardManagement;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * @inheritdoc
     */
    public function performResolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {

        if (!isset($args['cart_id']) || empty($args['cart_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing'));
        }
        $maskedCartId = $args['cart_id'];

        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
        $cart = $this->getCartForUser->execute($maskedCartId, $context->getUserId(), $storeId);

        $giftcards = $this->giftcardManagement->get($cart->getId());
        $result = [];
        foreach ($giftcards as $giftcard) {
            $result['items'][] = $this->dataObjectProcessor->buildOutputDataArray(
                $giftcard,
                QuoteInterface::class
            );
        }

        return $result;
    }
}
