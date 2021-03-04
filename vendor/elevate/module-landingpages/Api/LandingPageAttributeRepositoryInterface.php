<?php
namespace Elevate\LandingPages\Api;
                      
use Elevate\LandingPages\Api\Data\LandingPageAttributeInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaInterface;

interface LandingPageAttributeRepositoryInterface 
{
    public function save(LandingPageAttributeInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(LandingPageAttributeInterface $page);

    public function deleteById($id);
}
