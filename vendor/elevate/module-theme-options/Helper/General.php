<?php
namespace Elevate\Themeoptions\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class General extends AbstractHelper
{

    protected $searchCriteriaBuilder;
    protected $sortOrderBuilder;
    protected $filter;
    protected $filterBuilder;
    protected $filterGroup;
    protected $filterGroupBuilder;
    protected $logger;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder,
        \Magento\Framework\Api\Filter $filter,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filter = $filter;
        $this->filterGroup = $filterGroup;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->logger = $logger;
    }


    /**
     *
     * Build Search Criteria
     *
     *
     * @param array $filters
     * @param array $sortorder
     *
     * @return \Magento\Framework\Api\SearchCriteria
     */
    public function buildSearchCriteria(array $filters, array $sortorder) {

        $filtergroups = $this->buildFilters($filters,$sortorder);

        $sortorder = $this->buildSortOrder($sortorder);

        $searchCriteria = $this->searchCriteriaBuilder->setFilterGroups($filtergroups)->setSortOrders([$sortorder])->create();

        return $searchCriteria;
    }

    /**
     *
     * Builds Filters & Filter Groups (For AND based Queries) based on an array of filters and array of sortorders
     *
     * Example:
     *  $filters = array( array(
     * 'field' => 'deliverymethod_id',
     * 'value' => $delivery_method_id,
     * 'condition_type' => 'in'),
     *   array(
     * 'field' => 'deliveryarea_id',
     * 'value' => $delivery_area_id,
     * 'condition_type' => 'like'),
     * );
     *
     * condition_type is not needed, will be defaulted to eq if not present
     *
     * $sortOrder = array(
     * 'field' => 'day',
     * 'direction' => 'DESC'
     * );
     *
     *
     * @param $filters
     * @param $sortorder
     *
     * @return array
     */
    public function buildFilters(array $filters, array $sortorder) : array {

        foreach ($filters as $filter) {
            if (array_key_exists('condition_type', $filter)) {
                $filters_built[] = $this->filterBuilder->create()->setField($filter['field'])->setValue($filter['value'])->setConditionType($filter['condition_type']);
            } else {
                // Will be default Condition Type (i.e. eq)
                $filters_built[] = $this->filterBuilder->create()->setField($filter['field'])->setValue($filter['value']);
            }

        }

        foreach ($filters_built as $filter) {
            $filter_groups[] = $this->filterGroupBuilder->create()->setFilters([$filter]);
        }

        return $filter_groups;
    }

    /**
     *
     * Builds a SortOrder Query for SearchCriteria
     *
     *
     * $sortOrder = array(
     * 'field' => 'day',
     * 'direction' => 'DESC'
     * );
     *
     * @param array $sortorder
     *
     * @return \Magento\Framework\Api\SortOrder
     */
    public function buildSortOrder(array $sortorder) {

        $field = $sortorder['field'];
        $direction = $sortorder['direction'];

        return $this->sortOrderBuilder->setField($field)->setDirection($direction)->create();
    }
}
