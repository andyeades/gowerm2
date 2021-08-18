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
namespace Aheadworks\Giftcard\Ui\Component\Form;

use Aheadworks\Giftcard\Api\GiftcardRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class Field
 *
 * @package Aheadworks\Giftcard\Ui\Component\Form
 */
class Field extends \Magento\Ui\Component\Form\Field
{
    /**
     * @var GiftcardRepositoryInterface
     */
    private $giftcardRepository;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param GiftcardRepositoryInterface $giftcardRepository
     * @param Logger $logger
     * @param UiComponentInterface[] $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        GiftcardRepositoryInterface $giftcardRepository,
        Logger $logger,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->giftcardRepository = $giftcardRepository;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare()
    {
        parent::prepare();
        $config = $this->getData('config');
        $giftcardId = $this->getGiftcardId();

        if ((isset($config['visibleIsSetGcId']) && !$config['visibleIsSetGcId'] && $giftcardId) ||
            (isset($config['visibleIsSetGcId']) && $config['visibleIsSetGcId'] && !$giftcardId)
        ) {
            $config['componentDisabled'] = true;
        }

        if ($configSettingsUrl = $this->getData('config/service/configSettingsUrl')) {
            $config['service']['configSettingsUrl'] = $this->getContext()->getUrl($configSettingsUrl);
        }
        $this->setData('config', $config);
    }

    /**
     * Retrieve current gift card id
     *
     * @return int|null
     */
    public function getGiftcardId()
    {
        $giftcardId = $this->getContext()->getRequestParam(
            $this->getContext()->getDataProvider()->getRequestFieldName(),
            null
        );
        try {
            return $this->giftcardRepository->get($giftcardId)->getId();
        } catch (NoSuchEntityException $exception) {
            $this->logger->critical($exception->getMessage());
        }
        return null;
    }
}
