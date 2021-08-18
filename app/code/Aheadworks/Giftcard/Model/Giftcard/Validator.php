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
namespace Aheadworks\Giftcard\Model\Giftcard;

use Aheadworks\Giftcard\Api\Data\GiftcardInterface;
use Magento\Framework\Validator\AbstractValidator;
use Aheadworks\Giftcard\Model\Source\Giftcard\Status;

/**
 * Class Validator
 *
 * @package Aheadworks\Giftcard\Model\Giftcard
 */
class Validator extends AbstractValidator
{
    /**
     * Returns true if and only Gift Card is valid for processing
     *
     * @param GiftcardInterface $giftcard
     * @return bool
     */
    public function isValid($giftcard)
    {
        $this->_clearMessages();

        if ($giftcard->getState() == Status::DEACTIVATED) {
            $this->_addMessages([__('The specified Gift Card code deactivated')]);
        }
        if ($giftcard->getState() == Status::EXPIRED) {
            $this->_addMessages([__('The specified Gift Card code expired')]);
        }
        if ($giftcard->getState() == Status::USED) {
            $this->_addMessages([__('The specified Gift Card code used')]);
        }

        return empty($this->getMessages());
    }
}
