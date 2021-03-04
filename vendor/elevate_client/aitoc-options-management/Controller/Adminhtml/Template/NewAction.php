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

class NewAction extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Aitoc_OptionsManagement::templates';

    /**
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
