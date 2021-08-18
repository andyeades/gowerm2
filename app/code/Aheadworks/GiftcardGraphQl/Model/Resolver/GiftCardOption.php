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
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Aheadworks\Giftcard\Model\Product\Configuration;

/**
 * Class GiftCardOption
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver
 */
class GiftCardOption implements ResolverInterface
{
    /**
     * @var Configuration
     */
    private $productConfiguration;

    /**
     * @param Configuration $productConfiguration
     */
    public function __construct(
        Configuration $productConfiguration
    ) {
        $this->productConfiguration = $productConfiguration;
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($value['model'])) {
            throw new GraphQlInputException(__('Value must contain "model" property.'));
        }

        return $this->productConfiguration->getOptions($value['model']);
    }
}
