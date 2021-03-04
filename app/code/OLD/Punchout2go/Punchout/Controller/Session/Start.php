<?php

namespace Punchout2go\Punchout\Controller\Session;

use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Customer\CustomerData\SectionPoolInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context as ActionContext;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory as MageCookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Punchout2go\Punchout\Helper\Data as HelperData;
use Punchout2go\Punchout\Model\Session as PUNSession;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPost;


class Start extends Action implements HttpPost,CsrfAwareActionInterface
{
    /** @var \Magento\Framework\View\Result\PageFactory */
    protected $resultPageFactory;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $storeManager;

    /** @var \Punchout2go\Punchout\Model\Session */
    protected $punchoutSession;

    /** @var \Punchout2go\Punchout\Helper\Data */
    protected $helper;
    /**
     * @var SectionPoolInterface
     */
    protected $sectionPool;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;
    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    protected $cookieFactory;

    /**
     * @param \Magento\Framework\App\Action\Context                  $context
     * @param \Punchout2go\Punchout\Model\Session                    $punchoutSession
     * @param \Magento\Framework\View\Result\PageFactory             $resultPageFactory
     * @param \Magento\Store\Model\StoreManagerInterface             $storeManager
     * @param \Punchout2go\Punchout\Helper\Data                      $helper
     * @param \Magento\Framework\Stdlib\CookieManagerInterface       $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     * @param \Magento\Customer\CustomerData\SectionPoolInterface    $sectionPool
     */
    public function __construct(
        ActionContext $context,
        PUNSession $punchoutSession,
        PageFactory $resultPageFactory,
        StoreManagerInterface $storeManager,
        HelperData $helper,
        CookieManagerInterface $cookieManager,
        MageCookieMetadataFactory $cookieMetadataFactory,
        SectionPoolInterface $sectionPool

    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieFactory = $cookieMetadataFactory;
        $this->helper = $helper;
        $this->storeManager = $storeManager;
        $punchoutSession->setInSetup(true);
        $this->punchoutSession = $punchoutSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->sectionPool = $sectionPool;
        parent::__construct($context);
    }

    /**
     * Default punchout controller
     *
     * @return void
     */
    public function execute()
    {

        $this->helper->debug("Beginning of Controller/Start");
        /** @var \Punchout2go\Punchout\Model\Session $punchoutSession */
        $punchoutSession = $this->punchoutSession;
        try {
            $this->helper->debug("GOT TO HERE 2");
            if ($punchoutSession->setupSession($this->getRequest()->getParams())) {
                $punchoutSession->startSession();
                $punchoutSession->updateHttpResponse($this->getResponse());
            }

            /**
             * Unused?
             *
             * @var \Magento\Framework\App\Response\Http\Interceptor $responseObj
             */
            $responseObj = $this->getResponse();

            // Add ->setDuration() ?
            $cookieMetadata = $this->cookieFactory->createPublicCookieMetadata()->setPath('')->setDomain('')->setSecure(0)->setHttpOnly(0);
            $this->cookieManager->setPublicCookie('punchout-reset-storage', true, $cookieMetadata);
            $this->helper->debug("NO ERRORS!");
        } catch (\Exception $e) {
            $this->helper->debug("GETS TO HERE");
            /** @todo move to better controls */
            $errorString = date('Y-m-d H:i:s') . PHP_EOL;
            $errorString .= 'Punchout/Start Exception : ' . $e->getMessage() . PHP_EOL;
            $errorString .= $e->getFile() . '(' . $e->getLine() . ')' . PHP_EOL;
            $errorString .= $e->getTraceAsString() . PHP_EOL;

            $this->helper->debug($errorString);
        }
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

}
