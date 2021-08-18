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
namespace Aheadworks\Giftcard\Model\DateTime;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class FormatConverter
 * @package Aheadworks\Giftcard\Model\DateTime
 */
class FormatConverter
{
    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * @param TimezoneInterface $localeDate
     */
    public function __construct(TimezoneInterface $localeDate)
    {
        $this->localeDate = $localeDate;
    }

    /**
     * Converts PHP IntlFormatter format to Js Calendar format
     *
     * @param string $format
     * @return string
     */
    public function convertToJsCalendarFormat($format = null)
    {
        $format = $format ? : $this->getDateFormat();
        $format = preg_replace('/d+/i', 'dd', $format);
        $format = preg_replace('/m+/i', 'mm', $format);
        $format = preg_replace('/y+/i', 'yyyy', $format);
        $format = preg_replace('/\s+\S+/', '', $format);

        return $format;
    }

    /**
     * Converts PHP IntlFormatter format to moment Js format
     *
     * @param string $format
     * @return string
     */
    public function convertToMomentJsFormat($format = null)
    {
        $format = $format ? : $this->getDateFormat();
        $format = preg_replace('/d+/i', 'DD', $format);
        $format = preg_replace('/m+/i', 'MM', $format);
        $format = preg_replace('/y+/i', 'YYYY', $format);
        $format = preg_replace('/\s+\S+/', '', $format);

        return $format;
    }

    /**
     * Retrieve short date format
     *
     * @return string
     */
    private function getDateFormat()
    {
        return $this->localeDate->getDateFormat();
    }
}
