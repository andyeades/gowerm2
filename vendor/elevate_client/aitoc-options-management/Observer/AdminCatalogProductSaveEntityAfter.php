<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Observer;

class AdminCatalogProductSaveEntityAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Aitoc\OptionsManagement\Model\TemplateRepository
     */
    protected $templateRepository;

    public function __construct(\Aitoc\OptionsManagement\Model\TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    /**
     * Apply assigned templates
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Catalog\Controller\Adminhtml\Product\Save $controller */
        $controller = $observer->getEvent()->getController();
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getProduct();

        $data = $controller->getRequest()->getPostValue();

        $productOptions = $product->getOptions() ?: [];
        $templateIds = [];
        foreach($productOptions as $option) {
            if ($option->getTemplateId()) {
                $templateIds[] = $option->getTemplateId();
            }
        }
        $oldTemplateIds = array_unique($templateIds);

        if (isset($data['product']['assigned_templates'])) {
            $newTemplateIds = $data['product']['assigned_templates'];
            if (!$newTemplateIds) {
                $newTemplateIds = [];
            }
        } else {
            $newTemplateIds = [];
        }

        $unassignTemplateIds = array_diff($oldTemplateIds, $newTemplateIds);
        $assignNewTemplateIds = array_diff($newTemplateIds, $oldTemplateIds);

        if (!empty($unassignTemplateIds)) {
            $keepOptionsOnUnlink = $data['product']['keep_options_on_unlink'];

            foreach ($unassignTemplateIds as $templateId) {
                $template = $this->templateRepository->getById($templateId);
                $this->templateRepository->removeOptionsFromProduct($template, $product->getId(), $keepOptionsOnUnlink);
            }
        }

        if (!empty($assignNewTemplateIds)) {
            foreach ($assignNewTemplateIds as $templateId) {
                $template = $this->templateRepository->getById($templateId);
                $this->templateRepository->assignOptionsToProduct($template, $product->getId());
            }
        }

        return $this;
    }
}
