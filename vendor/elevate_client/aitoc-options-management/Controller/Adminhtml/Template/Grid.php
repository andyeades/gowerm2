<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\OptionsManagement\Controller\Adminhtml\Template;

class Grid extends \Aitoc\OptionsManagement\Controller\Adminhtml\Template
{
    /**
     * Grid Action
     * Display list of products related to current template
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $this->initCurrentTemplate();

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                \Aitoc\OptionsManagement\Block\Adminhtml\Template\Edit\Tab\Product::class,
                'template.product.grid'
            )->toHtml()
        );
    }
}
