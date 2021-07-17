<?php

namespace MX\WidgetComponent\Helper;

use Magento\Catalog\Helper\Data as DataHelper;
use Magento\Framework\Filter\Template;

/**
 * @deprecated use \MX\WidgetComponent\Helper\Media instead
 */
class Image
{
    /**
     * @var Template
     */
    private $templateProcessor;

    /**
     * @param DataHelper $catalogHelper
     */
    public function __construct(DataHelper $catalogHelper)
    {
        $this->templateProcessor = $catalogHelper->getPageTemplateProcessor();
    }

    /**
     * @param  string $imagePath
     *
     * @return string
     */
    public function getImageUrl($imagePath)
    {
        if (empty($imagePath) || $this->isUrl($imagePath)) {
            return $imagePath;
        }

        return $this->templateProcessor->filter(sprintf("{{media url='%s'}}", $imagePath));
    }

    /**
     * @param  string  $mediaPath
     *
     * @return boolean
     */
    private function isUrl($mediaPath)
    {
        return strpos($mediaPath, 'http') === 0;
    }
}
