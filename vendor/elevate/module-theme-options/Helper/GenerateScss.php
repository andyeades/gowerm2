<?php

namespace Elevate\Themeoptions\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class GenerateScss extends AbstractHelper
{
    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $moduleReader;

    /**
     * @var \ScssPhp\ScssPhp\Compiler
     */
    protected $scss;

    /**
     * @var \Elevate\Themeoptions\Api\OptionsRepositoryInterface $optionsRepository
     */
    protected $optionsRepository;

    /**
     * @var \Elevate\Themeoptions\Api\FooterRepositoryInterface $footerRepository
     */
    protected $footerRepository;

    /**
     * @var \Magento\Theme\Model\Theme\ThemeProvider $themeProvider
     */
    protected $themeProvider;
    /**
     * @var \Elevate\Themeoptions\Helper\General $helper
     */
    protected $helper;


    protected $importPaths;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /** @var \Magento\Framework\View\Design\ThemeInterface */

    protected $theme;

  /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $storeManager;

    protected $themeId;

    protected $headeroptionsinuse;

    protected $footeroptionsinuse;

    protected $footeroptionsset_in_use;

    protected $headeroptionsset_in_use;

    protected $headerstyle_selected;

    protected $temp_footerfile_to_include;

    protected $custom_scss;

    /**
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Elevate\Themeoptions\Helper\General $helper
     * @param \Elevate\Themeoptions\Api\OptionsRepositoryInterface $optionsRepository
     * @param \Elevate\Themeoptions\Api\FooterRepositoryInterface $footerRepository
     * @param \Magento\Theme\Model\Theme\ThemeProvider $themeProvider
     * @param \Magento\Framework\Module\Dir\Reader $moduleReader
     * @param \Magento\Framework\View\Design\ThemeInterface $theme
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \ScssPhp\ScssPhp\Compiler $scss
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Elevate\Themeoptions\Helper\General $helper,
        \Elevate\Themeoptions\Api\OptionsRepositoryInterface $optionsRepository,
        \Elevate\Themeoptions\Api\FooterRepositoryInterface $footerRepository,
        \Magento\Theme\Model\Theme\ThemeProvider $themeProvider,
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        \Magento\Framework\View\Design\ThemeInterface $theme,
        \ScssPhp\ScssPhp\Compiler $scss,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->helper = $helper;
        $this->optionsRepository = $optionsRepository;
        $this->footerRepository = $footerRepository;
        $this->themeProvider = $themeProvider;
        $this->theme = $theme;
        $this->scss = $scss;
        $this->moduleReader = $moduleReader;
        $this->importPaths = ('../');
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->custom_scss = $this->scopeConfig->getValue('theme_options/customisation/custom_scss', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->footeroptionsset_in_use = $this->scopeConfig->getValue('theme_options/themeoptions/footeroptionsset_in_use');
        $this->temp_footerfile_to_include = $this->scopeConfig->getValue('theme_options/themeoptions/temp_footerfile_to_include');
        $this->headeroptionsset_in_use = $this->scopeConfig->getValue('theme_options/themeoptions/headeroptionsset_in_use');
        $this->headerstyle_selected = $this->scopeConfig->getValue('theme_options/themeoptionsheader/header_style');


        parent::__construct($context);
    }

    public function getDirectory()
    {
        $moduleDirectory = $this->moduleReader->getModuleDir(\Magento\Framework\Module\Dir::MODULE_VIEW_DIR,'Elevate_Themeoptions'
        );
        return $moduleDirectory;
    }

    public function getThemeDirectory()
    {
        $moduleDirectory = $this->moduleReader->getModuleDir(\Magento\Framework\Module\Dir::MODULE_VIEW_DIR,'Elevate_Theme'
        );
        return $moduleDirectory;
    }

    public function processOptions($options, $excluded_options) {
        $options_array = array();
        foreach ($options as $option => $value) {

            if (isset($value) && (strlen($value) > 0)) {

                if(in_array($option,$excluded_options)){
                    // Don't Output Those
                    continue;
                }
                $new_array = array(
                    'name'  => $option,
                    'value' => $value
                );
                $options_array[] = $new_array;

            } else {
                //$this->_logger->info("Theme Options Module - $option is not set");
            }


        }

        return $options_array;
    }

    function generateScssCommandLine() {

        $themevals =  array();

        $themevals[] = array(
            'name' => 'footer_temp',
            'value' => $this->temp_footerfile_to_include
        );

        $themevals[] = array(
            'name' => 'header',
            'value' => $this->headeroptionsset_in_use
        );
        $themevals[] = array(
            'name' => 'headerstyle_selected',
            'value' => $this->headerstyle_selected
        );
        $themevals[] = array(
            'name' => 'footer',
            'value' => $this->footeroptionsset_in_use
        );
        $themevals[] =  array(
            'name' => 'headeroptionsinuse',
            'value' => $this->headeroptionsset_in_use,
        );

        $options_array_ids = array(
            'header'  => $this->headeroptionsset_in_use,
            'footer'  => $this->footeroptionsset_in_use
        );


        $custom_scss_admin_overrides = $this->custom_scss;

        $compile = $this->compileThemeOptions($options_array_ids, $themevals, $custom_scss_admin_overrides);

        return $compile;
    }

    function compileThemeOptions($options_ids, $imported_variables, $custom_scss_admin_overrides) {

        // Get Theme Option in Use

        $header_options_id = $options_ids['header'];
        $footer_options_id = $options_ids['footer'];

        //$header_options_in_use = $this->optionsRepository->getById($header_options_id)->getAllData();
        // TODO: Need to add a selector Box!
        //$footer_options_in_use = $this->footerRepository->getById($footer_options_id)->getAllData();
        $output = array();

        $excluded_options = array('entity_id','theme_options_name','footer_options_name');

        //$header_options = $this->processOptions($header_options_in_use, $excluded_options);
        //$footer_options = $this->processOptions($footer_options_in_use, $excluded_options);

        //$imported_variables = array_merge($header_options,$footer_options,$imported_variables);

        $themeId = $this->scopeConfig->getValue(\Magento\Framework\View\DesignInterface::XML_PATH_THEME_ID,\Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore()->getId());
        $current_theme_id = $this->themeProvider->getThemeById($themeId);


        $theme_dir = $this->getThemeDirectory();
        $realpath_base = $this->getDirectory();
        $rp_b = realpath($realpath_base);
        $output_filename = '_main.css';
        $output_filename_alt = '_main.scss';
        //$output_filename_alt_2 = 'ourmainfile.css';
        //$output_filename_alt_3 = '_adminvars.scss';
        $output_filename_alt_4 = '_adminvars_extended.scss';
        $output_filename_alt_5 = '_core_pre_vars.scss';
        $rp_b_alt = $rp_b;
        $theme_output_path = str_replace("/module-theme-options/view","/theme/web/css",$rp_b);
        $scss_output_path = $rp_b. DIRECTORY_SEPARATOR. 'frontend/web/scss';
        $css_output_path = $rp_b. DIRECTORY_SEPARATOR. 'frontend/web/css';

        //$scss_output_path_and_name = $theme_output_path.DIRECTORY_SEPARATOR.$output_filename_alt_3;

        $output_string_admin = $custom_scss_admin_overrides;

        //file_put_contents($scss_output_path_and_name,$custom_scss_admin_overrides);

        $imported_variables;
        $output_string = '';
        $core_output_string = '';

        foreach ($imported_variables as $scss_variable) {
            $output_string .= '$'.$scss_variable['name'].': '.$scss_variable['value'].' !global;'.PHP_EOL;
        }



        // Add Custom SCSS Admin Overides
        $output_string .= $custom_scss_admin_overrides;

        $output_string .= PHP_EOL;

        // Output Scss File with Vars For Compilation Later
        $theoutputfilename = $scss_output_path .DIRECTORY_SEPARATOR . $output_filename_alt;

        // Output Scss Vars To Theme Folder as well for Overrides!

        $scss_output_path_and_name_2  = $theme_output_path.DIRECTORY_SEPARATOR.$output_filename_alt_4;

        file_put_contents($scss_output_path_and_name_2,$output_string);

        // Output Scss sitename var to core variables (necessary for determining what to include in scss files);

        $re = '/(\$sitename:.*;)/m';
        preg_match($re, $custom_scss_admin_overrides, $matches);

        $re2 = '/(\$theme-brand-color.*:.*;)/m';

        preg_match($re2, $custom_scss_admin_overrides, $matches_other);

        if (!empty($matches)) {
            $core_output_string = $matches[0];

            if (!empty($matches_other)) {
                $core_output_string .= $matches_other[0];
            }

            $scss_output_path_and_name_3 = $theme_output_path.DIRECTORY_SEPARATOR.$output_filename_alt_5;

            file_put_contents($scss_output_path_and_name_3,$core_output_string);

        }




        if (!empty(file_put_contents($scss_output_path_and_name_2,$output_string))) {
            // Success
            //$this->scss->addImportPath($scss_output_path);
            //$in = $scss_output_path . DIRECTORY_SEPARATOR . 'ourmainfile.scss';
            //$final_output = $css_output_path .DIRECTORY_SEPARATOR . $output_filename_alt_2;
            //$hello = file_get_contents($in);
           // $this->scss->setFormatter('ScssPhp\ScssPhp\Formatter\Expanded');
            //$this->scss->setFormatter('ScssPhp\ScssPhp\Formatter\Compressed');
            //$output = $this->scss->compile(file_get_contents($in));


            //file_put_contents($final_output,$output);

            return array('success' => 'true');
        } else {
            // Failure?
            return array('success' => 'false');
        }
    }

    /**
     * Compile .scss file
     *
     * @param string $in  Input path (.scss)
     * @param string $out Output path (.css)
     *
     * @return array
     */
    protected function compile($in, $out)
    {
        $start   = microtime(true);
        $css     = $this->scss->compile(file_get_contents($in), $in);
        $elapsed = round((microtime(true) - $start), 4);

        $v    = Version::VERSION;
        $t    = gmdate('r');
        $css  = "/* compiled by scssphp $v on $t (${elapsed}s) */\n\n" . $css;
        $etag = md5($css);

        file_put_contents($out, $css);
        file_put_contents(
            $this->metadataName($out),
            serialize([
                          'etag'    => $etag,
                          'imports' => $this->scss->getParsedFiles(),
                          'vars'    => crc32(serialize($this->scss->getVariables())),
                      ])
        );

        return [$css, $etag];
    }
}
