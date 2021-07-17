<?php
namespace Elevate\LandingPages\Api;
                      
use Elevate\LandingPages\Api\Data\LandingPageFaqInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaInterface;

interface LandingPageFaqRepositoryInterface 
{
    public function save(LandingPageFaqInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(LandingPageFaqInterface $page);

    public function deleteById($id);
}
