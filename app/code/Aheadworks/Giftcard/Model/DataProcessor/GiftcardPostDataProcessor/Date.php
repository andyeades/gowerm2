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
namespace Aheadworks\Giftcard\Model\DataProcessor\GiftcardPostDataProcessor;

use Aheadworks\Giftcard\Model\DataProcessor\PostDataProcessorInterface;
use Magento\Catalog\Model\Product\Filter\DateTime as DateTimeFilter;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class Date
 * @package Aheadworks\Giftcard\Model\DataProcessor\GiftcardPostDataProcessor
 */
class Date implements PostDataProcessorInterface
{
    /**
     * @var DateTimeFilter
     */
    private $dateTimeFilter;

    /**
     * @var array
     */
    private $dateFields = [];

    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * Date constructor.
     * @param TimezoneInterface $localeDate
     * @param DateTimeFilter|null $dateTimeFilter
     * @param array $dateFields
     */
    public function __construct(
        TimezoneInterface $localeDate,
        ?DateTimeFilter $dateTimeFilter = null,
        $dateFields = []
    ) {
        $objectManager = ObjectManager::getInstance();
        $this->dateTimeFilter = $dateTimeFilter ?? $objectManager->get(DateTimeFilter::class);
        $this->dateFields = array_merge($this->dateFields, $dateFields);
        $this->localeDate = $localeDate;
    }

    /**
     * @param array $data
     * @return array|mixed|null
     */
    public function prepareEntityData($data)
    {
        foreach ($this->dateFields as $field) {
            if (isset($data[$field]) && $data[$field]) {
                $data[$field] = $this->dateTimeFilter->filter($data[$field]);
                $deliveryDate = $this->localeDate->date(strtotime($data[$field]));
                $deliveryDate->setTimezone(new \DateTimeZone('UTC'));
                $data[$field] = $deliveryDate->format(StdlibDateTime::DATETIME_PHP_FORMAT);
            } else {
                $data[$field] = null;
            }
        }

        return $data;
    }
}