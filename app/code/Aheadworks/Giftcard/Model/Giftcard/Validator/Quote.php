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
namespace Aheadworks\Giftcard\Model\Giftcard\Validator;

use Aheadworks\Giftcard\Api\Data\Giftcard\QuoteInterface as GiftcardQuoteInterface;
use Aheadworks\Giftcard\Api\GiftcardRepositoryInterface;
use Aheadworks\Giftcard\Model\Giftcard\Validator as GiftcardValidator;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Quote
 *
 * @package Aheadworks\Giftcard\Model\Giftcard\Validator
 */
class Quote
{
    /**
     * @var GiftcardRepositoryInterface
     */
    private $giftcardRepository;

    /**
     * @var GiftcardValidator
     */
    private $giftcardValidator;

    /**
     * @param GiftcardRepositoryInterface $giftcardRepository
     * @param GiftcardValidator $giftcardValidator
     */
    public function __construct(
        GiftcardRepositoryInterface $giftcardRepository,
        GiftcardValidator $giftcardValidator
    ) {
        $this->giftcardRepository = $giftcardRepository;
        $this->giftcardValidator = $giftcardValidator;
    }

    /**
     * Check if specified gift card code is still valid
     *
     * @param string $giftcardCode
     * @param int $websiteId
     * @return bool
     */
    public function isValid($giftcardCode, $websiteId)
    {
        $isValid = true;
        try {
            $giftcard = $this->giftcardRepository->getByCode($giftcardCode, $websiteId);
            if (!$this->giftcardValidator->isValid($giftcard)) {
                $isValid = false;
            }
        } catch (LocalizedException $exception) {
            $isValid = false;
        }
        return $isValid;
    }
}
