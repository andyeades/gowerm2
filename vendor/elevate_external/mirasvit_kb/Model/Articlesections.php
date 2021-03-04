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

use Mirasvit\Kb\Api\Data\ArticlesectionsInterface;
use Mirasvit\Kb\Api\Data\ArticlesectionsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Articlesections extends \Magento\Framework\Model\AbstractModel
{
    protected $ArticlesectionsDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'mst_kb_articlesections';

    const CACHE_TAG = 'mst_kb_articlesections';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ArticlesectionsInterfaceFactory $ArticlesectionsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Mirasvit\Kb\Model\ResourceModel\Articlesections $resource
     * @param \Mirasvit\Kb\Model\ResourceModel\Articlesections\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ArticlesectionsInterfaceFactory $ArticlesectionsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Mirasvit\Kb\Model\ResourceModel\Articlesections $resource,
        \Mirasvit\Kb\Model\ResourceModel\Articlesections\Collection $resourceCollection,
        array $data = []
    ) {
        $this->ArticlesectionsDataFactory = $ArticlesectionsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve Articlesections model with Articlesections data
     * @return ArticlesectionsInterface
     */
    public function getDataModel()
    {
        $ArticlesectionsData = $this->getData();

        $ArticlesectionsDataObject = $this->ArticlesectionsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $ArticlesectionsDataObject,
            $ArticlesectionsData,
            ArticlesectionsInterface::class
        );

        return $ArticlesectionsDataObject;
    }
}
