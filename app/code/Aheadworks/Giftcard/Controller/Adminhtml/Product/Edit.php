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
namespace Aheadworks\Giftcard\Controller\Adminhtml\Product;

/**
 * Class Edit
 *
 * @package Aheadworks\Giftcard\Controller\Adminhtml\Product
 */
class Edit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Giftcard::giftcard_products';

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->_getSession()->setBackToAwGiftcardGridFlag(true);
        $this->_getSession()->setResetBackToAwGiftcardGridFlag(false);

        $awGcParams = [];
        foreach ($this->getRequest()->getParams() as $key => $value) {
            $result = strpos($key, 'awgc');
            if ($result === 0) {
                $awGcParams[$key] = $value;
            }
        }
        return $this->_redirect(
            'catalog/product/edit',
            array_merge(
                [
                    'id' => $this->getRequest()->getParam('id'),
                    'store' => $this->getRequest()->getParam('store')
                ],
                $awGcParams
            )
        );
    }
}
