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
namespace Aheadworks\GiftcardGraphQl\Model;

use Magento\Framework\GraphQl\Query\Resolver\TypeResolverInterface;
use Aheadworks\Giftcard\Model\Product\Type\Giftcard;

/**
 * Class GiftCardProductTypeResolver
 *
 * @package Aheadworks\GiftcardGraphQl\Model
 */
class GiftCardProductTypeResolver implements TypeResolverInterface
{
    /**
     * Graph QL product type
     */
    const AW_GC_PRODUCT = 'AwGiftCardProduct';
    
    /**
     * @inheritdoc
     */
    public function resolveType(array $data) : string
    {
        if (isset($data['type_id']) && $data['type_id'] == Giftcard::TYPE_CODE) {
            return self::AW_GC_PRODUCT;
        }

        return '';
    }
}
