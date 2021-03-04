<?php

namespace Elevate\Firmness\Controller\Index;

use Magento\Framework\App\Action\Context;

class Setbodyweighttype extends \Magento\Framework\App\Action\Action
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $_coreSession = null;




    /**
     * Index constructor.
     * @param Context $context
     * @param  \Magento\Framework\Session\SessionManagerInterface $coreSession
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Session\SessionManagerInterface $coreSession

    ) {
        $this->_coreSession = $coreSession;
        parent::__construct($context);


    }
    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $wt_input = $this->getRequest()->getParam('type');
        $this->_coreSession->setBodyWeightType($wt_input);
        return false;
    }


}