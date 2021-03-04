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

namespace Mirasvit\Kb\Model;

use Mirasvit\Kb\Api\Data\ArticleCategoryInterface;
use Mirasvit\Kb\Api\Data\ArticleCategoryInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class ArticleCategory extends \Magento\Framework\Model\AbstractModel
{
    protected $ArticleCategoryDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'mst_kb_article_category';

    const CACHE_TAG = 'mst_kb_article_category';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ArticleCategoryInterfaceFactory $ArticleCategoryDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Mirasvit\Kb\Model\ResourceModel\ArticleCategory $resource
     * @param \Mirasvit\Kb\Model\ResourceModel\ArticleCategory\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ArticleCategoryInterfaceFactory $ArticleCategoryDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Mirasvit\Kb\Model\ResourceModel\ArticleCategory $resource,
        \Mirasvit\Kb\Model\ResourceModel\ArticleCategory\Collection $resourceCollection,
        array $data = []
    ) {
        $this->ArticleCategoryDataFactory = $ArticleCategoryDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve ArticleCategory model with ArticleCategory data
     * @return ArticleCategoryInterface
     */
    public function getDataModel()
    {
        $ArticleCategoryData = $this->getData();

        $ArticleCategoryDataObject = $this->ArticleCategoryDataFactory->create();
        //$this->dataObjectHelper->populateWithArray(
        //    $ArticleCategoryDataObject,
        //    $ArticleCategoryData,
        //    ArticleCategoryInterface::class
        //);

        return $ArticleCategoryDataObject;
    }
}
