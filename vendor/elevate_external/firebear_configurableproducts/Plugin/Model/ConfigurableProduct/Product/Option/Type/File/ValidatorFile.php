<?php

namespace Firebear\ConfigurableProducts\Plugin\Model\ConfigurableProduct\Product\Option\Type\File;

use \Magento\Catalog\Model\Product\Option\Type\File\ValidatorFile as OptionFileValidator;
use Firebear\ConfigurableProducts\Helper\Data as icpHelper;

class ValidatorFile
{
    /**
     * @var icpHelper
     */
    protected $icpHelper;

    /**
     * @var array $arrayFileOptions
     */
    protected $arrayFileOptions = [];

    /**
     * ValidatorFile constructor.
     * @param icpHelper $icpHelper
     */
    public function __construct(icpHelper $icpHelper)
    {
        $this->icpHelper = $icpHelper;
    }

    /**
     * @param OptionFileValidator $subject
     * @param callable $proceed
     * @param \Magento\Framework\DataObject $processingParams
     * @param \Magento\Catalog\Model\Product\Option $option
     * @return array
     */
    public function aroundValidate(OptionFileValidator $subject, callable $proceed, $processingParams, $option)
    {
        $file = $processingParams->getFilesPrefix() . 'options_' . $option->getId() . '_file';
        if (isset($this->arrayFileOptions[$file])
            && $this->arrayFileOptions[$file]
            && $this->icpHelper->getGeneralConfig('matrix/matrix_swatch')) {
            return $this->arrayFileOptions[$file];
        } else {
            return $proceed($processingParams, $option);
        }
    }

    public function afterValidate(OptionFileValidator $subject, $result, $processingParams, $option) {
        if ($this->icpHelper->getGeneralConfig('matrix/matrix_swatch')) {
            $file = $processingParams->getFilesPrefix() . 'options_' . $option->getId() . '_file';
            $this->arrayFileOptions[$file] = $result;
        }
        return $result;
    }

}
