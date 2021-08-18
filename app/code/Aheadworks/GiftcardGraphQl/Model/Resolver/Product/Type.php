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
namespace Aheadworks\GiftcardGraphQl\Model\Resolver\Product;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\EnumLookup;
use Magento\Catalog\Model\Product;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Aheadworks\Giftcard\Api\Data\ProductAttributeInterface;
use Aheadworks\Giftcard\Model\Product\Type\Giftcard as GiftCardType;

/**
 * Class Type
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver\Product
 */
class Type implements ResolverInterface
{
    /**
     * @var EnumLookup
     */
    private $enumLookup;

    /**
     * @param EnumLookup $enumLookup
     */
    public function __construct(EnumLookup $enumLookup)
    {
        $this->enumLookup = $enumLookup;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($value['model'])) {
            throw new GraphQlInputException(__('"model" value should be specified'));
        }

        /** @var Product $product */
        $product = $value['model'];

        $data = null;
        if ($product->getTypeId() === GiftCardType::TYPE_CODE) {
            $data = $this->enumLookup->getEnumValueFromField(
                'awGcGiftCardTypeEnum',
                $product->getData(ProductAttributeInterface::CODE_AW_GC_TYPE)
            );
        }

        return $data;
    }
}
