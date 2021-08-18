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
namespace Aheadworks\Giftcard\Controller\Adminhtml\Pool\Code;

use Magento\Backend\App\Action\Context;
use Aheadworks\Giftcard\Model\FileUploader;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Json;
use Magento\Backend\App\Action;

/**
 * Class Upload
 *
 * @package Aheadworks\Giftcard\Controller\Adminhtml\Pool\Code
 */
class Upload extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Giftcard::giftcard_pools';

    /**
     * @var FileUploader
     */
    private $fileUploader;

    /**
     * @param Context $context
     * @param FileUploader $fileUploader
     */
    public function __construct(
        Context $context,
        FileUploader $fileUploader
    ) {
        parent::__construct($context);
        $this->fileUploader = $fileUploader;
    }

    /**
     * Image uploader action
     *
     * @return Json
     */
    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        try {
            $result = $this->fileUploader->saveToTmpFolder('csv_file');
        } catch (\Exception $exception) {
            $result = [
                'error' => $exception->getMessage(),
                'errorcode' => $exception->getCode()
            ];
        }
        return $resultJson->setData($result);
    }
}
