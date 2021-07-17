<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            9G1Oomm4OxmHfebVSyQu6iPnh7aZSqugsHLyXe5f85g=
 * Last Modified: 2016-03-02T15:34:05+00:00
 * File:          app/code/Xtento/OrderExport/Block/Adminhtml/Destination/Edit/Tab/Type/Sftp.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\OrderExport\Block\Adminhtml\Destination\Edit\Tab\Type;

class Sftp extends Ftp
{
    // SFTP Configuration
    public function getFields(\Magento\Framework\Data\Form $form, $type = 'SFTP')
    {
        parent::getFields($form, $type);
    }
}