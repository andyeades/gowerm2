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
namespace Aheadworks\Giftcard\Model\System\Config\CodeLength;

use Magento\Framework\Validator\AbstractValidator;
use Magento\Framework\App\Config\Value as ConfigValue;

/**
 * Class Validator
 *
 * @package Aheadworks\Giftcard\Model\System\Config\CodeLength
 */
class Validator extends AbstractValidator
{
    /**
     * Returns true if and only if value meets the validation requirements
     *
     * @param ConfigValue $entity
     * @return bool
     */
    public function isValid($entity)
    {
        $this->_clearMessages();

        if ($entity->getValue() < 4) {
            $this->_addMessages(['Code Length must be greater than or equal to 4']);
        }

        return empty($this->getMessages());
    }
}
