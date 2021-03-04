<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-kb
 * @version   1.0.69
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */

namespace Mirasvit\Kb\Service\Articlesections;

use Mirasvit\Kb\Controller\Adminhtml\Articlesections;

class ArticlesectionsManagement implements \Mirasvit\Kb\Api\Service\Articlesections\ArticlesectionsManagementInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    private $layoutFactory;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * ArticlesectionsManagement constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->storeManager  = $storeManager;
        $this->request       = $request;
        $this->layoutFactory = $layoutFactory;
        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritdoc}
     */
    public function isAvailableForStore($articlesections, $currentStore = 0)
    {
        $storeIds = (array)$articlesections->getStoreIds();
        if (!$currentStore) {
            $currentStore = $this->storeManager->getStore()->getId();
        }

        return in_array($currentStore, $storeIds) || in_array(0, $storeIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableStores($articlesections, $categories = [])
    {
        if (!$categories) {
            $categories = $articlesections->getCategoryIds();
        }
        /** @var \Mirasvit\Kb\Model\Category $category */
        $category = $this->objectManager->get('\Mirasvit\Kb\Model\Category');
        $articlesectionsStoreIds = [];
        foreach ($categories as $id) {
            /** @var \Mirasvit\Kb\Model\Category $articlesectionsCategory */
            $articlesectionsCategory = $category->loadPathArray((string)$id)[0];
            $parentCategoryId = $articlesectionsCategory->getParentRootCategory();
            /** @var \Mirasvit\Kb\Model\Category $parentCategory */
            $parentCategory = $category->loadPathArray((string)$parentCategoryId)[0];
            $storeIds = $parentCategory->getStoreIds();
            $articlesectionsStoreIds = array_merge($articlesectionsStoreIds, $storeIds);
        }

        return array_unique($articlesectionsStoreIds);
    }
}
