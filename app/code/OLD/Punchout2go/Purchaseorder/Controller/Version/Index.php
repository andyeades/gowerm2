<?php

namespace Punchout2go\Purchaseorder\Controller\Version;

use Magento\Framework\Registry;
//use Symfony\Component\Config\Definition\Exception\Exception;

class Index extends \Magento\Framework\App\Action\Action
{

    /** @var \Punchout2go\Purchaseorder\Helper\Data $helper */
    protected $helper;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Punchout2go\Purchaseorder\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Punchout2go\Purchaseorder\Helper\Data $helper
    ){
        $this->resultFactory = $context->getResultFactory();
        $this->helper = $helper;

        parent::__construct($context);
    }

    public function execute()
    {
        //running the version output through send response
        return $this->sendResponse($this->helper->getModuleVersion());
    }

    /**
     * @param $response
     * @return mixed
     */
    public function sendResponse($response, $success = true)
    {
        $result = $resultRedirect = $this->resultFactory->create(
            \Magento\Framework\Controller\ResultFactory::TYPE_RAW
        );

        $statusCode = '200';
        if ($success == false) {
            $statusCode = \Magento\Framework\Webapi\Exception::HTTP_BAD_REQUEST;
        }

        return $result
            ->setHeader('Content-Type','text/plain')
            ->setHttpResponseCode($statusCode)
            ->setContents($response);
    }
}