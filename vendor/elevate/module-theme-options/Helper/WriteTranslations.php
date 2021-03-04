<?php

namespace Elevate\Themeoptions\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class WriteTranslations extends AbstractHelper
{
    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $moduleReader;

    /**
     * @var \Elevate\Themeoptions\Api\TranslationsRepositoryInterface
     */
    protected $translationsRepository;

    /**
     * @var \Magento\Theme\Model\Theme\ThemeProvider
     */
    protected $themeProvider;

    /**
     * @var \Elevate\Themeoptions\Helper\General
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

    /**
     * @var \Magento\Framework\File\Csv $csvProcessor
     */
    protected $csvProcessor;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    protected $directorylist;

    /**
     * @var \Magento\Framework\Filesystem $filesystem
     */
    protected $filesystem;

    /**
     * @var \Elevate\Framework\Helper\Data
     */
    protected $ev_helper;
    /**
     *
     * @param \Magento\Framework\App\Helper\Context $context
     *  @param \Elevate\Framework\Helper\Data $ev_helper
     * @param \Elevate\Themeoptions\Helper\General $helper
     * @param \Elevate\Themeoptions\Api\TranslationsRepositoryInterface $translationsRepository
     * @param \Magento\Theme\Model\Theme\ThemeProvider $themeProvider
     * @param \Magento\Framework\Module\Dir\Reader $moduleReader
     * @param \Magento\Framework\View\Design\ThemeInterface $theme
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\File\Csv $csvProcessor,
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Elevate\Framework\Helper\Data $ev_helper,
        \Elevate\Themeoptions\Helper\General $helper,
        \Elevate\Themeoptions\Api\TranslationsRepositoryInterface $translationsRepository,
        \Magento\Theme\Model\Theme\ThemeProvider $themeProvider,
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        \Magento\Framework\View\Design\ThemeInterface $theme,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->ev_helper = $ev_helper;
        $this->helper = $helper;
        $this->translationsRepository = $translationsRepository;
        $this->themeProvider = $themeProvider;
        $this->theme = $theme;
        $this->moduleReader = $moduleReader;
        $this->importPaths = ('../');
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->csvProcessor = $csvProcessor;
        $this->directorylist = $directoryList;
        $this->filesystem = $filesystem;

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

    function generateTranslationsCommandLine() {

        $themeId = $this->scopeConfig->getValue(\Magento\Framework\View\DesignInterface::XML_PATH_THEME_ID,\Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore()->getId());
        $current_theme_id = $this->themeProvider->getThemeById($themeId);


        $theme_dir = $this->getThemeDirectory();
        $realpath_base = $this->getDirectory();
        $rp_b = realpath($realpath_base);
        $theme_output_path = str_replace("/module-theme-options/view","/theme/i18n/",$rp_b);



        $filters = array(
            array(
                'field' => 'entity_id',
                'value' => '',
                'condition_type' => 'notnull'
            )
        );

        $sortorder = array(
            'field'     => 'entity_id',
            'direction' => 'DESC'
        );

        $searchCriteria = $this->ev_helper->buildSearchCriteria($filters, $sortorder);
        $translations = $this->translationsRepository->getList($searchCriteria);
        $translations_count = $translations->getTotalCount();

        $output_count = 0;
        $output_array = array();

        if ($translations_count > 0) {
            /** @var \Elevate\Themeoptions\Api\Data\TranslationsInterface $translation */
            $translations = $translations->getItems();
            foreach ($translations as $translation) {


                $content = $translation->getTranslationsContent();
                $file_name = $translation->getTranslationsAreaCode().".csv";

                file_put_contents($theme_output_path.$file_name,$content);
                $output_count++;
                $output_array[] = $translation->getTranslationsAreaCode();
            }
        }

        if ($output_count >= 1) {
            return array('success' => 'true','success_array' => $output_array);
        } else {
            return array('failed' => 'true');
        }


    }

    function writeToCsv(){
        $fileDirectoryPath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);

        if(!is_dir($fileDirectoryPath))
            mkdir($fileDirectoryPath, 0777, true);
        $fileName = 'export.csv';
        $filePath =  $fileDirectoryPath . '/' . $fileName;

        $data = [];
        /* pass data array to write in csv file */

        $this->csvProcessor
            ->setEnclosure('')
            ->setDelimiter(',')
            ->appendData($filePath, $data);

        return true;
    }
}
