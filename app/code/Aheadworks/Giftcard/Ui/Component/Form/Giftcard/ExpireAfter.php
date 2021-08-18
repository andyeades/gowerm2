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
namespace Aheadworks\Giftcard\Ui\Component\Form\Giftcard;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Aheadworks\Giftcard\Model\Config;
use Aheadworks\Giftcard\Api\GiftcardRepositoryInterface;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class ExpireAfter
 *
 * @package Aheadworks\Giftcard\Ui\Component\Form\Giftcard
 */
class ExpireAfter extends \Aheadworks\Giftcard\Ui\Component\Form\Field
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Config $config
     * @param GiftcardRepositoryInterface $giftcardRepository
     * @param Logger $logger
     * @param UiComponentInterface[] $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Config $config,
        GiftcardRepositoryInterface $giftcardRepository,
        Logger $logger,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $giftcardRepository, $logger, $components, $data);
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare()
    {
        parent::prepare();
        $config = $this->getData('config');
        $config['value'] = $this->config->getGiftcardExpireDays();
        $this->setData('config', $config);
    }
}
