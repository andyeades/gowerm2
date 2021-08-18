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
namespace Aheadworks\GiftcardGraphQl\Model\Resolver\Mutation;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
use Aheadworks\Giftcard\Api\GiftcardCartManagementInterface;

/**
 * Class RemoveGiftCardFromCart
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver\Mutation
 */
class RemoveGiftCardFromCart implements ResolverInterface
{
    /**
     * @var GetCartForUser
     */
    private $getCartForUser;

    /**
     * @var GiftcardCartManagementInterface
     */
    private $giftcardManagement;

    /**
     * @param GetCartForUser $getCartForUser
     * @param GiftcardCartManagementInterface $giftcardManagement
     */
    public function __construct(
        GetCartForUser $getCartForUser,
        GiftcardCartManagementInterface $giftcardManagement
    ) {
        $this->getCartForUser = $getCartForUser;
        $this->giftcardManagement = $giftcardManagement;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($args['cart_id']) || empty($args['cart_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing'));
        }
        if (!isset($args['gift_card_code']) || empty($args['gift_card_code'])) {
            throw new GraphQlInputException(__('Required parameter "gift_card_code" is missing'));
        }
        $maskedCartId = $args['cart_id'];
        $giftcardCode = $args['gift_card_code'];

        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
        $cart = $this->getCartForUser->execute($maskedCartId, $context->getUserId(), $storeId);

        return $this->giftcardManagement->remove($cart->getId(), $giftcardCode);
    }
}
