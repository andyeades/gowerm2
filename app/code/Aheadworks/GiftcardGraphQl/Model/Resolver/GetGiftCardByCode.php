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
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Aheadworks\Giftcard\Api\GiftcardRepositoryInterface;
use Aheadworks\Giftcard\Api\Data\GiftcardInterface;

/**
 * Class GetGiftCardByCode
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Resolver
 */
class GetGiftCardByCode implements ResolverInterface
{
    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var GiftcardRepositoryInterface
     */
    private $giftcardRepository;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     * @param GiftcardRepositoryInterface $giftcardRepository
     */
    public function __construct(
        DataObjectProcessor $dataObjectProcessor,
        GiftcardRepositoryInterface $giftcardRepository
    ) {
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->giftcardRepository = $giftcardRepository;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($args['gift_card_code']) || empty($args['gift_card_code'])) {
            throw new GraphQlInputException(__('Required parameter "gift_card_code" is missing'));
        }

        $giftcardCode = $args['gift_card_code'];
        $websiteId = (int)$context->getExtensionAttributes()->getStore()->getWebsiteId();

        $giftcard = $this->giftcardRepository->getByCode($giftcardCode, $websiteId);

        return $this->dataObjectProcessor->buildOutputDataArray(
            $giftcard,
            GiftcardInterface::class
        );
    }
}
