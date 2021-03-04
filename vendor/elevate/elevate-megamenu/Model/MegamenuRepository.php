<?php 

namespace Elevate\Megamenu\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Elevate\Megamenu\Api\Data\MegamenuInterface;
use Elevate\Megamenu\Api\Data\MegamenuSearchResultInterface;
use Elevate\Megamenu\Api\Data\MegamenuSearchResultInterfaceFactory;
use Elevate\Megamenu\Api\MegamenuRepositoryInterface;
use Elevate\Megamenu\Model\ResourceModel\Metadata;
use Elevate\Megamenu\Model\ResourceModel\Megamenu\Collection as MegamenuCollectionFactory;

class MegamenuRepository implements MegamenuRepositoryInterface
{

  /**
   * @var Metadata
   */
  private $metadata;

  /**
   * @var MegamenuFactory
   */
  protected $megamenuFactory;

  /**
   * @var collectionFactory
   */
  protected $collectionFactory;

  /**
   * @var megamenu
   */
  protected $megamenu;

  /**
   * @var MegamenuSearchResultInterfaceFactory
   */
  protected $searchResultFactory;

  /**
   * Repository constructor.
   * @param Metadata $megamenuMetadata
   * @param Megamenu $megamenuFactory
   * @param MegamenuCollectionFactory $CollectionFactory
   * @param MegamenuSearchResultInterfaceFactory $megamenuSearchResultInterfaceFactory
   *
   */
  public function __construct(
    Metadata $megamenuMetadata,
    Megamenu $megamenuFactory,
    MegamenuCollectionFactory $collectionFactory,
    MegamenuInterface $megamenu,
    MegamenuSearchResultInterfaceFactory $megamenuSearchResultInterfaceFactory
  ) {
    $this->metadata = $megamenuMetadata;
    $this->megamenuFactory = $megamenuFactory;
    $this->collectionFactory = $collectionFactory;
    $this->megamenu = $megamenu;
    $this->searchResultFactory = $megamenuSearchResultInterfaceFactory;
  }
  public function get($id)
  {
    $megamenu = $this->create();
    $megamenu->getResource()->load($megamenu, $id);
    if (! $megamenu->getId()) {
      throw new NoSuchEntityException(__('Unable to find megamenu with ID "%1"', $id));
    }
    return $megamenu;
  }

  public function getById($id)
  {
    $megamenu = $this->create();
    $megamenu->getResource()->load($megamenu, $id);
    if (! $megamenu->getId()) {
      throw new NoSuchEntityException(__('Unable to find megamenu with ID "%1"', $id));
    }
    return $megamenu;
  }
  public function getByEntityId($id)
  {
    $megamenu = $this->create();
    $megamenu->getResource()->load($megamenu, $id);
    if (! $megamenu->getEntityId()) {
      throw new NoSuchEntityException(__('Unable to find megamenu with ID "%1"', $id));
    }
    return $megamenu;
  }

  public function create() {
    return $this->metadata->getNewInstance();

  }

  public function save(MegamenuInterface $megamenu)
  {
    $megamenu->getResource()->save($megamenu);
    return $megamenu;
  }

  public function delete(MegamenuInterface $megamenu)
  {
    $megamenu->getResource()->delete($megamenu);
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

  public function getList(SearchCriteriaInterface $searchCriteria)
  {
    $collection = $this->collectionFactory;

    $this->addFiltersToCollection($searchCriteria, $collection);
    $this->addSortOrdersToCollection($searchCriteria, $collection);
    $this->addPagingToCollection($searchCriteria, $collection);

    $collection->load();

    return $this->buildSearchResult($searchCriteria, $collection);
  }

  private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, MegamenuCollectionFactory $collection)
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

  private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, MegamenuCollectionFactory $collection)
  {
    foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
      $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
      $collection->addOrder($sortOrder->getField(), $direction);
    }
  }

  private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, MegamenuCollectionFactory $collection)
  {
    $collection->setPageSize($searchCriteria->getPageSize());
    $collection->setCurPage($searchCriteria->getCurrentPage());
  }

  private function buildSearchResult(SearchCriteriaInterface $searchCriteria, MegamenuCollectionFactory $collection)
  {
    $searchResults = $this->searchResultFactory->create();

    $searchResults->setSearchCriteria($searchCriteria);
    $searchResults->setItems($collection->getItems());
    $searchResults->setTotalCount($collection->getSize());

    return $searchResults;
  }
}