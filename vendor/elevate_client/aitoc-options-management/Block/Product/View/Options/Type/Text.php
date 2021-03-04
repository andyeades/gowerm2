<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Block\Product\View\Options\Type;

/**
 * Product options text type block
 */
class Text extends \Magento\Catalog\Block\Product\View\Options\AbstractOptions
{
    /**
     * Returns default value to show in text input
     *
     * @return string
     */
    public function getDefaultValue()
    {
        $text = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $this->getOption()->getId());
        if (is_null($text)) {
            $text = $this->getOption()->getDefaultText();
        }
        return $text;
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        if ($template == 'product/view/options/type/text.phtml') {
            $template = 'Magento_Catalog::product/view/options/type/text.phtml';
        }
        return parent::setTemplate($template);
    }
}
