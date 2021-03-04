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



namespace Mirasvit\Kb\Ui\Component\DataProvider;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;

class ArticlesectionsGridDataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param string                                                             $name
     * @param string                                                             $primaryFieldName
     * @param string                                                             $requestFieldName
     * @param \Magento\Framework\View\Element\UiComponent\DataProvider\Reporting $reporting
     * @param SearchCriteriaBuilder                                              $searchCriteriaBuilder
     * @param RequestInterface                                                   $request
     * @param FilterBuilder                                                      $filterBuilder
     * @param \Magento\Framework\Registry                                        $registry
     * @param array                                                              $meta
     * @param array                                                              $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Framework\View\Element\UiComponent\DataProvider\Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        \Magento\Framework\Registry $registry,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->registry = $registry;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {

        parent::addFilter($filter);
    }

    /**
     * Returns Search result
     *
     * @return \Mirasvit\Kb\Model\ResourceModel\Articlesections\Collection
     */
    public function getSearchResult()
    {
        /** @var mixed $res */
        $res = $this->reporting->search($this->getSearchCriteria());
        return $res;
        //return $res->joinStoreIds()->joinCategoryIds();
    }
}
