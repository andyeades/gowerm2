<?php

namespace Punchout2go\Xframe\App\Response\HeaderProvider;

use \Magento\Framework\App\Response\Http;
use Punchout2go\Xframe\Helper\Data as HelperData;

/**
 * Adds an X-FRAME-OPTIONS header to HTTP responses to safeguard against click-jacking.
 */
class XFrameOptions extends \Magento\Framework\App\Response\HeaderProvider\AbstractHeaderProvider
{
    /** Deployment config key for frontend x-frame-options header value */
    const DEPLOYMENT_CONFIG_X_FRAME_OPT = 'x-frame-options';

    /** Always send SAMEORIGIN in backend x-frame-options header */
    const BACKEND_X_FRAME_OPT = 'SAMEORIGIN';

    /**
     * x-frame-options Header name
     *
     * @var string
     */
    protected $headerName = Http::HEADER_X_FRAME_OPT;

    /**
     * x-frame-options header value
     *
     * @var string
     */
    protected $headerValue;

    /**
     * @var boolean
     */
    protected $_can_apply = true;

    /**
     * @var \Punchout2go\Xframe\Helper\Data
     */
    protected $helper;

    /**
     *
     * @param \Punchout2go\Xframe\Helper\Data   $dataHelper
     * @param string $xFrameOpt
     */
    public function __construct(
        HelperData $dataHelper,
        $xFrameOpt = 'SAMEORIGIN')
    {
        $this->helper = $dataHelper;
        $this->headerValue = $xFrameOpt;
    }

    public function getValue ()
    {
        return parent::getValue();
    }

    public function canApply()
    {
        if ($this->helper->isEnabled()) {
            $this->helper->debug('get xframe value');
            $header_value = $this->helper->getHeaderValue();
            $this->helper->debug('header value : '. $header_value);
            /** @var \Magento\Framework\App\Response\Http\Interceptor $responseObj */
            if (empty($header_value) && $header_value !== 0) {
                $this->helper->debug('no value provided, use default behavior');
            } elseif ($header_value === 0
                        || $header_value == "ALLOW") {
                $this->helper->debug('disable false - can_apply');
                $this->_can_apply = false;
            } else {
                $this->headerValue = $header_value;
            }
        }
        return $this->_can_apply;
    }

}
