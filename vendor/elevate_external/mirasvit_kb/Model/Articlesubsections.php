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

use Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface;
use Mirasvit\Kb\Api\Data\ArticlesubsectionsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Articlesubsections extends \Magento\Framework\Model\AbstractModel
{
    protected $articlesubsectionsDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'mst_kb_articlesubsections';

    const CACHE_TAG = 'mst_kb_articlesubsections';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ArticlesubsectionsInterfaceFactory $articlesubsectionsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Mirasvit\Kb\Model\ResourceModel\Articlesubsections $resource
     * @param \Mirasvit\Kb\Model\ResourceModel\Articlesubsections\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ArticlesubsectionsInterfaceFactory $articlesubsectionsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Mirasvit\Kb\Model\ResourceModel\Articlesubsections $resource,
        \Mirasvit\Kb\Model\ResourceModel\Articlesubsections\Collection $resourceCollection,
        array $data = []
    ) {
        $this->articlesubsectionsDataFactory = $articlesubsectionsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve Articlesubsections model with Articlesubsections data
     * @return ArticlesubsectionsInterface
     */
    public function getDataModel()
    {
        $articlesubsectionsData = $this->getData();

        $articlesubsectionsDataObject = $this->articlesubsectionsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $articlesubsectionsDataObject,
            $articlesubsectionsData,
            ArticlesubsectionsInterface::class
        );

        return $articlesubsectionsDataObject;
    }
}
