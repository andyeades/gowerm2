<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            9G1Oomm4OxmHfebVSyQu6iPnh7aZSqugsHLyXe5f85g=
 * Last Modified: 2015-09-10T15:24:06+00:00
 * File:          app/code/Xtento/OrderExport/Block/Adminhtml/History.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\OrderExport\Block\Adminhtml;

class History extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->removeButton('add');
    }
}
