<?php 

namespace Elevate\ProductIcons\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Elevate\ProductIcons\Api\Data\ProducticonsInterface;
use Elevate\ProductIcons\Api\Data\ProducticonsSearchResultInterface;
use Elevate\ProductIcons\Api\Data\ProducticonsSearchResultInterfaceFactory;
use Elevate\ProductIcons\Api\ProducticonsRepositoryInterface;
use Elevate\ProductIcons\Model\ResourceModel\Metadata;
use Elevate\ProductIcons\Model\ResourceModel\Producticons\CollectionFactory as ProducticonsCollectionFactory;
use Elevate\Producticons\Model\ResourceModel\Producticons\Collection;

/**
 * Class ProducticonsRepository
 *
 * @category Elevate
 * @package  Elevate\ProductIcons\Model
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class ProducticonsRepository implements ProducticonsRepositoryInterface
{

  /**
   * @var Metadata
   */
  private $metadata;

  /**
   * @var ProducticonsFactory
   */
  protected $producticonsFactory;

  /**
   * @var collectionFactory
   */
  protected $collectionFactory;

  /**
   * @var megamenu
   */
  protected $producticons;

  /**
   * @var ProductIconsSearchResultInterfaceFactory
   */
  protected $searchResultFactory;

  /**
   * Repository constructor.
   * @param Metadata $producticonsMetadata
   * @param Producticons $producticonsFactory
   * @param ProducticonsCollectionFactory $collectionFactory
   * @param ProducticonsSearchResultInterfaceFactory $producticonsSearchResultInterfaceFactory
   *
   */
  public function __construct(
    Metadata $producticonsMetadata,
    Producticons $producticonsFactory,
    ProducticonsCollectionFactory $collectionFactory,
    ProducticonsInterface $producticons,
    ProducticonsSearchResultInterfaceFactory $producticonsSearchResultInterfaceFactory
  ) {
    $this->metadata = $producticonsMetadata;
    $this->producticonsFactory = $producticonsFactory;
    $this->collectionFactory = $collectionFactory;
    $this->producticons = $producticons;
    $this->searchResultFactory = $producticonsSearchResultInterfaceFactory;
  }
  public function get($id)
  {
    $producticons = $this->create();
    $producticons->getResource()->load($producticons, $id);
    if (! $producticons->getId()) {
      throw new NoSuchEntityException(__('Unable to find Product Icon with ID "%1"', $id));
    }
    return $producticons;
  }

  public function getById($id)
  {
    $producticons = $this->create();
    $producticons->getResource()->load($producticons, $id);
    if (! $producticons->getId()) {
      throw new NoSuchEntityException(__('Unable to find Product Icon with ID "%1"', $id));
    }
    return $producticons;
  }
  public function getByEntityId($id)
  {
    $producticons = $this->create();
    $producticons->getResource()->load($producticons, $id);
    if (! $producticons->getEntityId()) {
      throw new NoSuchEntityException(__('Unable to find Product Icon with ID "%1"', $id));
    }
    return $producticons;
  }
  public function getByIconId($id)
  {
    $producticons = $this->create();
    $producticons->getResource()->load($producticons, $id);
    if (! $producticons->getId()) {
      throw new NoSuchEntityException(__('Unable to find Product Icon with ID "%1"', $id));
    }
    return $producticons;
  }

  /**
   * @return ProducticonsInterface|\Magento\Framework\Api\ExtensibleDataInterface
   */
  public function create() {
    return $this->metadata->getNewInstance();

  }

  public function save(ProducticonsInterface $producticons)
  {
    $producticons->getResource()->save($producticons);
    return $producticons;
  }

  public function delete(ProducticonsInterface $producticons)
  {
    $producticons->getResource()->delete($producticons);
  }


  /**
   * Delete entity by Id
   *
   * @param int $id
   * @return bool
   */
  public function deleteById($id)
  {
    $entity = $this->get($id);
    return $this->delete($entity);
  }

  /**
   * Delete entity by Id
   *
   * @param int $id
   * @return bool
   */
  public function deleteByEntityId($id)
  {
    $entity = $this->get($id);
    return $this->delete($entity);
  }

  /**
   * Delete entity by Id
   *
   * @param int $id
   * @return bool
   */
  public function deleteByIconId($id)
  {
    $entity = $this->get($id);
    return $this->delete($entity);
  }

  /**
   * @param SearchCriteriaInterface $searchCriteria
   *
   * @return ProducticonsSearchResultInterface
   */
  public function getList(SearchCriteriaInterface $searchCriteria)
  {

    $collection = $this->collectionFactory->create();

    $this->addFiltersToCollection($searchCriteria, $collection);
    $this->addSortOrdersToCollection($searchCriteria, $collection);
    $this->addPagingToCollection($searchCriteria, $collection);

    $collection->load();

    return $this->buildSearchResult($searchCriteria, $collection);
  }

  private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, $collection)
  {
    foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
      $fields = $conditions = [];
      foreach ($filterGroup->getFilters() as $filter) {
        $fields[] = $filter->getField();
        $conditions[] = [$filter->getConditionType() => $filter->getValue()];
      }
      $collection->addFieldToFilter($fields, $conditions);
    }
  }

  private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, $collection)
  {
    foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
      $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
      $collection->addOrder($sortOrder->getField(), $direction);
    }
  }

  private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, $collection)
  {
    $collection->setPageSize($searchCriteria->getPageSize());
    $collection->setCurPage($searchCriteria->getCurrentPage());
  }

  private function buildSearchResult(SearchCriteriaInterface $searchCriteria, $collection)
  {
    $searchResults = $this->searchResultFactory->create();

    $searchResults->setSearchCriteria($searchCriteria);
    $searchResults->setItems($collection->getItems());
    $searchResults->setTotalCount($collection->getSize());

    return $searchResults;
  }
}