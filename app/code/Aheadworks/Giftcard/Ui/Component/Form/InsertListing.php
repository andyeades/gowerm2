<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Ui\Component\Form;

use Magento\Ui\Component\Container;

/**
 * Class InsertListing
 *
 * @package Aheadworks\Giftcard\Ui\Component\Form
 */
class InsertListing extends Container
{
    /**
     * {@inheritdoc}
     */
    public function prepare()
    {
        $id = $this->getContext()->getRequestParam(
            $this->getContext()->getDataProvider()->getRequestFieldName(),
            'new'
        );
        $config = $this->getData('config');
        $config['params'][$config['addParamToFilter']] = $id;
        $this->setData('config', $config);

        parent::prepare();
    }
}
