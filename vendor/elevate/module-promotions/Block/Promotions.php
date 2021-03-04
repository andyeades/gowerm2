<?php
namespace Elevate\Promotions\Block;

class Promotions extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }


    public function getPromotions()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $widgets[] = '{{widget 
        type="Elevate\Widgets\Block\Widget\Jumbo"
        desktop_image="homepage/back_to_school_Banner.jpg"
        mobile_image="homepage/Back__to_School_Email_Banner.jpg"
        }}';


        $widgets[] = '{{widget type="Elevate\Widgets\Block\Widget\SixBlock" image_1="homepage/swp/left.jpg" image_2="homepage/swp/middle.jpg" image_3="homepage/swp/right.jpg"}}';


        $widgets[] = '{{widget type="Elevate\Widgets\Block\Widget\RecommendedLarge" cat_1_id="7" cat_1_name="Beds" cat_2_id="11" cat_2_name="Mattresses" cat_3_id="33" cat_3_name="Test"}} ';


        //emulate the widget
        foreach($widgets AS $widget){
            preg_match('/type="([^"]+)"/',$widget,$type);
            $filterManager = $objectManager->get('Magento\Widget\Model\Widget')->getConfigAsObject($type[1]);

            $widget_name = $filterManager->getName();
            $widget_description = $filterManager->getDescription();

            $filterManager = $objectManager->get('Magento\Cms\Model\Template\FilterProvider')->getPageFilter()->filter($widget);
            $widget_output[] = array(
                'name' => $widget_name,
                'description' => $widget_description,
                'html' => $filterManager
            );



        }
        return $widget_output;
    }
}