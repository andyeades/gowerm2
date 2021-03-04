<?php

namespace Elevate\Firmness\Controller\Index;

use Magento\Framework\App\Action\Context;

class Setbodyweight2 extends \Magento\Framework\App\Action\Action
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

        $bw_input = $this->getRequest()->getParam('bodyweight');

        $bw_arr[6] = 6;
        $bw_arr[7] = 7;
        $bw_arr[8] = 8;
        $bw_arr[9] = 9;
        $bw_arr[10] = 10;
        $bw_arr[11] = 11;
        $bw_arr[12] = 12;
        $bw_arr[13] = 13;
        $bw_arr[14] = 14;
        $bw_arr[15] = 15;
        $bw_arr[16] = 16;
        $bw_arr[17] = 17;
        $bw_arr[18] = 18;
        $bw_arr[19] = 19;
        $bw_arr[20] = 20;
        $bw_arr[21] = 21;
        $bw_arr[22] = 22;
        $bw_arr[23] = 23;
        $bw_arr[24] = 24;
        $bw_arr[25] = 25;
        $bw_arr[26] = 26;
        $bw_arr[27] = 27;
        $bw_arr[28] = 28;

        if (array_key_exists($bw_input, $bw_arr)) {

            $this->_coreSession->setBodyWeight2($bw_input);
        }
        $firmnessRatingHelper = \Magento\Framework\App\ObjectManager::getInstance()->get("Elevate\Firmness\Helper\Data");


        $adjustment = $firmnessRatingHelper->getBodyweightAdjustment2();

        $response['bodyweight'] = $bw_input;
        $response['adjustment'] = $adjustment;
        $response_output =json_encode($response);

        echo $response_output;

        return false;







        
    }


}