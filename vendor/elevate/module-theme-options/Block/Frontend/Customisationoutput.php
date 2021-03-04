<?php


namespace Elevate\Themeoptions\Block\Frontend;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Customisationoutput extends \Magento\Framework\View\Element\Template {

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $custom_css;

    protected $include_font;

    protected $google_font_to_include;

    protected $google_font_weights;



    /**
     * @param \Magento\Backend\Block\Template\Context            $context
     * @param \Magento\Framework\Registry                        $registry
     * @param \Magento\Framework\Data\FormFactory                $formFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array                                              $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;

        $this->custom_css = $this->scopeConfig->getValue('theme_options/customisation/custom_css', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $this->include_font = $this->scopeConfig->getValue('theme_options/googlefont/include_font', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $this->google_font_to_include = $this->scopeConfig->getValue('theme_options/googlefont/google_font_to_include', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $this->google_font_weights = $this->scopeConfig->getValue('theme_options/googlefont/google_font_weights', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);


    }

    public function getCustomCss() {
        return $this->custom_css;
    }


    public function getIncludeFont() {
        return $this->include_font;
    }

    public function getGoogleFontToInclude() {
        return $this->google_font_to_include;
    }

    public function getGoogleFontWeights() {
        return $this->google_font_weights;
    }

}
