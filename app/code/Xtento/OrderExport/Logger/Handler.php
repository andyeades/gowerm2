<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            9G1Oomm4OxmHfebVSyQu6iPnh7aZSqugsHLyXe5f85g=
 * Last Modified: 2016-03-01T16:04:39+00:00
 * File:          app/code/Xtento/OrderExport/Logger/Handler.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */
namespace Xtento\OrderExport\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/xtento_orderexport.log';
}