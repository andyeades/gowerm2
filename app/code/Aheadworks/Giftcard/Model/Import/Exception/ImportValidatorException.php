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
namespace Aheadworks\Giftcard\Model\Import\Exception;

use Aheadworks\Giftcard\Api\Exception\ImportValidatorExceptionInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ImportValidatorException
 *
 * @package Aheadworks\Giftcard\Model\Import\Exception
 */
class ImportValidatorException extends LocalizedException implements ImportValidatorExceptionInterface
{
}
