<?php
namespace Elevate\LandingPages\Model\Rewrite\Catalog;

use Magento\Catalog\Helper\Data as MainHelper;

class Data extends MainHelper
{
  public function getBreadcrumbPath()
    {
   
        if (!$this->_categoryPath) {
            $path = [];
            $category = $this->getCategory();
            if ($category) {
                $pathInStore = $category->getPathInStore();
                $pathIds = array_reverse(explode(',', $pathInStore));

                $categories = $category->getParentCategories();


                
    $is_landing_page = $this->_coreRegistry->registry('elevate_landingpage');


    
            
                // add category path breadcrumb
                foreach ($pathIds as $categoryId) {
                
                
     if($is_landing_page){
           $catCheck = true;
    }
    else{
    $catCheck = $this->_isCategoryLink($categoryId);
    }
    
                    if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) {
                        $path['category' . $categoryId] = [
                            'label' => $categories[$categoryId]->getName(),
                            'link' => $catCheck ? $categories[$categoryId]->getUrl() : ''
                        ];
                    }
                }
                
                
                
                
                
            }

            if ($this->getProduct()) {
                $path['product'] = ['label' => $this->getProduct()->getName()];
            }

            $this->_categoryPath = $path;
        }
        return $this->_categoryPath;
    }
}
