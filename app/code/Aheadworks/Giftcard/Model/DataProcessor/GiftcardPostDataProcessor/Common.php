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

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Aheadworks\Giftcard\Api\PoolManagementInterface;
use Aheadworks\Giftcard\Model\Config;
use Aheadworks\Giftcard\Model\DataProcessor\PostDataProcessorInterface;

/**
 * Class Common
 *
 * @package Aheadworks\Giftcard\Model\DataProcessor\GiftcardPostDataProcessor
 */
class Common implements PostDataProcessorInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * @var PoolManagementInterface
     */
    private $poolManagement;

    /**
     * @param TimezoneInterface $localeDate
     * @param DateTime $dateTime
     * @param PoolManagementInterface $poolManagement
     * @param Config $config
     */
    public function __construct(
        TimezoneInterface $localeDate,
        DateTime $dateTime,
        PoolManagementInterface $poolManagement,
        Config $config
    ) {
        $this->localeDate = $localeDate;
        $this->dateTime = $dateTime;
        $this->poolManagement = $poolManagement;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function prepareEntityData($data)
    {
        if (!$data['id']) {
            $expireAfter = null;
            $codeFromPool = true;
            if (isset($data['use_default'])) {
                if (isset($data['use_default']['expire_after']) && (bool)$data['use_default']['expire_after']) {
                    $expireAfter = $this->config->getGiftcardExpireDays();
                }
                if (isset($data['use_default']['code_pool']) && (bool)$data['use_default']['code_pool']) {
                    $codeFromPool = false;
                }
            }
            if (null === $expireAfter && isset($data['expire_after'])) {
                $expireAfter = $data['expire_after'];
            }
            if ($expireAfter) {
                $data['expire_at'] = $this->localeDate
                    ->date('+' . $expireAfter . 'days', null, false, false)
                    ->format(StdlibDateTime::DATETIME_PHP_FORMAT);
            }
            if ($codeFromPool) {
                $data['code'] = $this->poolManagement->pullCodeFromPool($data['code_pool']);
            }
        }

        if (isset($data['created_at'])) {
            unset($data['created_at']);
        }

        return $data;
    }
}
