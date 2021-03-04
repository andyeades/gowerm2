<?php
namespace Elevate\LandingPages\Api;
                      
use Elevate\LandingPages\Api\Data\LandingPageInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaInterface;

interface LandingPageRepositoryInterface 
{
    public function save(LandingPageInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(LandingPageInterface $page);

    public function deleteById($id);
}
