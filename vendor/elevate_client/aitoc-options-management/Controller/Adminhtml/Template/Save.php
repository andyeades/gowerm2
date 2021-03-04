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

/**
 * Class Save template
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Save extends \Aitoc\OptionsManagement\Controller\Adminhtml\Template
{
    /**
     * Template save
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function execute()
    {
        $templateId = (int)$this->getRequest()->getParam('template_id');
        $storeId = (int)$this->getRequest()->getParam('store_id');

        if ($data = $this->getRequest()->getPostValue()) {
            try {
                $model = $this->initCurrentTemplate();

                // process store data
                if ($storeId > 0) {
                    $data['options'] = $this->initializationHelper->mergeProductOptions(
                        $data['options'],
                        $this->getRequest()->getPost('options_use_default')
                    );
                }

                $model->setData($data);

                if (isset($data['template_products']) && is_string($data['template_products'])) {
                    $products = json_decode($data['template_products'], true);
                    $postedProducts = [];
                    if ($products) {
                        foreach($products as $productId) {
                            $productId = intval($productId);
                            if ($productId > 0) {
                                $postedProducts[] = $productId;
                            }
                        }
                    }

                    $model->setPostedProducts($postedProducts);
                }

                $this->templateRepository->save($model);
                $this->messageManager->addSuccess(__('You saved the template.'));

                $templateId = $model->getId();

                $redirectBack = $this->getRequest()->getParam('back', false);

                if ($redirectBack == 'grid') {
                    return $this->_redirect('*/*');
                }

                if ($redirectBack == 'duplicate') {
                    $duplicate = $this->templateRepository->duplicate($model);
                    $this->messageManager->addSuccessMessage(__('You duplicated the template.'));
                    $templateId = $duplicate->getId();
                }

            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_getSession()->setPostData($data);
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    __('Something went wrong while saving. Please review the error log.' . $e->getMessage())
                );
                $this->_getSession()->setPostData($data);
            }
        }

        if ($templateId) {
            $params = ['id' => $templateId];
            if ($storeId > 0) {
                $params['store'] = $storeId;
            }
            return $this->_redirect('*/*/edit', $params);
        } else {
            return $this->_redirect('*/*');
        }
    }
}
