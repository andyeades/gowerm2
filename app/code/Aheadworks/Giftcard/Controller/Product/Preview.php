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
namespace Aheadworks\Giftcard\Controller\Product;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Giftcard\Model\Email\Previewer;

/**
 * Class Preview
 *
 * @package Aheadworks\Giftcard\Controller\Product
 */
class Preview extends \Magento\Framework\App\Action\Action
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var Previewer
     */
    private $previewer;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Previewer $previewer
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Previewer $previewer
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->previewer = $previewer;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $jsonData = [
            'success' => true,
            'content' => ''
        ];
        $storeId = $this->getRequest()->getParam('store');
        $productId = $this->getRequest()->getParam('product');
        $data = $this->getRequest()->getPostValue();
        try {
            $jsonData['content'] = $this->previewer->getPreview($data, $storeId, $productId);
        } catch (LocalizedException $e) {
            $jsonData['success'] = false;
            $jsonData['content'] = $e->getMessage();
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($jsonData);
    }
}
