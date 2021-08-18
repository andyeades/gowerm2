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
namespace Aheadworks\GiftcardGraphQl\Model\GiftCard;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Aheadworks\Giftcard\Api\Data\GiftcardInterface;
use Aheadworks\Giftcard\Api\Data\GiftcardInterfaceFactory;
use Aheadworks\Giftcard\Api\GiftcardRepositoryInterface;
use Aheadworks\Giftcard\Model\DataProcessor\PostDataProcessorInterface;

/**
 * Class Save
 *
 * @package Aheadworks\GiftcardGraphQl\Model\GiftCard
 */
class Save
{
    /**
     * @var GiftcardRepositoryInterface
     */
    private $giftcardRepository;

    /**
     * @var GiftCardInterfaceFactory
     */
    private $giftcardFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var PostDataProcessorInterface
     */
    private $postDataProcessor;

    /**
     * @param GiftcardRepositoryInterface $giftcardRepository
     * @param GiftcardInterfaceFactory $giftcardFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param PostDataProcessorInterface $postDataProcessor
     */
    public function __construct(
        GiftcardRepositoryInterface $giftcardRepository,
        GiftCardInterfaceFactory $giftcardFactory,
        DataObjectHelper $dataObjectHelper,
        PostDataProcessorInterface $postDataProcessor
    ) {
        $this->giftcardRepository = $giftcardRepository;
        $this->giftcardFactory = $giftcardFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->postDataProcessor = $postDataProcessor;
    }

    /**
     * Save gift card
     *
     * @param array $data
     * @return GiftCardInterface
     * @throws GraphQlInputException
     */
    public function execute(array $data)
    {
        try {
            $data = $this->postDataProcessor->prepareEntityData($data);
            $giftcard = $this->createGiftCard($data);
            return $this->giftcardRepository->save($giftcard);
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
    }

    /**
     * Create gift card
     *
     * @param array $data
     * @return GiftCardInterface
     * @throws LocalizedException
     */
    private function createGiftCard(array $data)
    {
        $id = $data[GiftCardInterface::ID] ?? null;
        $giftcard = $id
            ? $this->giftcardRepository->get($id)
            : $this->giftcardFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $giftcard,
            $data,
            GiftCardInterface::class
        );

        return $giftcard;
    }
}
