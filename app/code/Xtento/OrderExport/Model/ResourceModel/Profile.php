<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            9G1Oomm4OxmHfebVSyQu6iPnh7aZSqugsHLyXe5f85g=
 * Last Modified: 2016-02-25T18:38:44+00:00
 * File:          app/code/Xtento/OrderExport/Model/ResourceModel/Profile.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\OrderExport\Model\ResourceModel;

class Profile extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('xtento_orderexport_profile', 'profile_id');
    }
}
