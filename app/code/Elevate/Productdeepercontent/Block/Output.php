<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Elevate\Productdeepercontent\Block;

class Output extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Elevate\Framework\Helper\Data;
     */
    protected $evFrameworkHelper;

    /**
     * @var \Elevate\Productdeepercontent\Api\DeepercontentRepositoryInterface
     */
    protected $deeperContentRepository;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /* @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $scopeConfig;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Elevate\Productdeepercontent\Api\DeepercontentRepositoryInterface $deeperContentRepository,
        \Elevate\Framework\Helper\Data $evFrameworkHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->deeperContentRepository = $deeperContentRepository;
        $this->evFrameworkHelper = $evFrameworkHelper;
        $this->scopeConfig = $scopeConfig;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve current Product object
     *
     * @return \Magento\Catalog\Model\Product|null
     */
    public function getCurrentProduct() {
        return $this->registry->registry('current_product');
    }

    /**
     * @return string
     */
    public function outputDeeperContent()
    {
        //Your block code
        $output = '';
        $randomEnabled = $this->scopeConfig->getValue('elevate_deepercontent/general/enable_random_display');

        if ($randomEnabled == 1) {
            $number_to_get = $this->scopeConfig->getValue('elevate_deepercontent/general/random_number_to_display');
            if (is_numeric($number_to_get)) {
                $output = $this->getRandomDeeperContent($number_to_get);
            } else {
                // Fall Back
                // Log Error
                $output = $this->getRandomDeeperContent(3);
            }
        } else {
            // get Value from Product Page
        $product = $this->getCurrentProduct();
            //product_deepercontent_ids
        $deeper_content_ids = $product->getProductDeeperContentIds();

            if (!empty($deeper_content_ids)) {

                $output = $this->getSpecificDeeperContent();
            } else {
                $output = '';
            }


        }


        return $output;
    }


    public function getRandomDeeperContent($number_to_get) {


        $filters = array(
            array(
                'field' => 'deepercontent_id',
                'value' => '',
                'condition_type' => 'notnull'
            )
        );

        $sortorder = array(
            'field'     => 'deepercontent_id',
            'direction' => 'DESC'
        );

        $searchCriteria = $this->evFrameworkHelper->buildSearchCriteria($filters, $sortorder);
        $deeperContent = $this->deeperContentRepository->getList($searchCriteria);
        $items_to_get = array_rand($deeperContent->getItems(), intval($number_to_get));
        $deeperContentItems = $deeperContent->getItems();

        $output_html = '';


        /*
         *
         *
    <div class="pview-desc-deepercontent-row">
        <div class="pview-desc-deepercontent-img">
            <img src="/pub/media/theme_assets/productpage/deepercontent-1.jpg"/>
        </div>
        <div class="pview-desc-deepercontent-content">
            <div class="pview-desc-deepercontent-title">Lorem ipsum dolor sit amet</div>
            <div class="pview-desc-deepercontent-desc">A beautifully presented gift box of 12 Gluten Free Cherry Flavour Gower Cottage Brownies, home baked by Kate, using finest quality Belgian chocolate with no nuts.</div>
        </div>
    </div>
    <div class="pview-desc-deeper-content-row-reverse pview-desc-deepercontent-row">
        <div class="pview-desc-deepercontent-img">
            <img src="/pub/media/theme_assets/productpage/deepercontent-2.jpg"/>
        </div>
        <div class="pview-desc-deepercontent-content">
            <div class="pview-desc-deepercontent-title">Lorem ipsum dolor sit amet</div>
            <div class="pview-desc-deepercontent-desc">A beautifully presented gift box of 12 Gluten Free Cherry Flavour Gower Cottage Brownies, home baked by Kate, using finest quality Belgian chocolate with no nuts.</div>
        </div>
    </div>
    <div class="pview-desc-deepercontent-row">
        <div class="pview-desc-deepercontent-img">
            <img src="/pub/media/theme_assets/productpage/deepercontent-3.jpg"/>
        </div>
        <div class="pview-desc-deepercontent-content">
            <div class="pview-desc-deepercontent-title">Lorem ipsum dolor sit amet</div>
            <div class="pview-desc-deepercontent-desc">A beautifully presented gift box of 12 Gluten Free Cherry Flavour Gower Cottage Brownies, home baked by Kate, using finest quality Belgian chocolate with no nuts.</div>
        </div>
    </div>

         */



        $count = 1;
        foreach ($items_to_get as $item) {
            $the_item = $deeperContentItems[$item];




            if ($count % 2 == 0) {
                $row_output_class = 'pview-desc-deepercontent-row pview-desc-deepercontent-row-reverse';
            } else {
                $row_output_class = 'pview-desc-deepercontent-row';
            }

            $output_html .= '<div class="'.$row_output_class.'">';
                $output_html .= '<div class="pview-desc-deepercontent-img">';
                    $output_html .= '<img src="/pub/media/elevate/proddeepecontent/image/'.$the_item->getDeepercontentImage().'"/>';
                $output_html .= '</div>'; // pview-desc-deepercontent-img

                $output_html .= '<div class="pview-desc-deepercontent-content">';
                    $output_html .= '<div class="pview-desc-deepercontent-title">';
                    $output_html .=  $the_item->getDeepercontentTitle();
                    $output_html .= '</div>'; // pview-desc-deepercontent-title
                    $output_html .= '<div class="pview-desc-deepercontent-desc">';
                    $output_html .=  $the_item->getDeepercontent();
                    $output_html .= '</div>'; // pview-desc-deepercontent-desc
                $output_html .= '</div>'; // pview-desc-deepercontent-content
            $output_html .= '</div>'; // pview-desc-deepercontent-row
            $count++;
        }



        return $output_html;
    }
    public function getSpecificDeeperContent($specific_ids) {

        $filters = array(
            array(
                'field' => 'deepercontent_id',
                'value' => $specific_ids,
                'condition_type' => 'in'
            )
        );

        $sortorder = array(
            'field'     => 'deepercontent_id',
            'direction' => 'DESC'
        );

        $searchCriteria = $this->evFrameworkHelper->buildSearchCriteria($filters, $sortorder);
        $deeperContent = $this->deeperContentRepository->getList($searchCriteria);
        $deeperContentItems = $deeperContent->getItems();

        $output_html = '';
        $count = 1;
        foreach ($deeperContentItems as $item) {

            if ($count % 2 == 0) {
                $row_output_class = 'pview-desc-deepercontent-row pview-desc-deepercontent-row-reverse';
            } else {
                $row_output_class = 'pview-desc-deepercontent-row';
            }

            $output_html .= '<div class="'.$row_output_class.'">';

            $output_html .= '<div class="pview-desc-deepercontent-img">';
            $output_html .= '<img src="/pub/media/elevate/proddeepecontent/image/'.$item->getDeepercontentImage().'"/>';
            $output_html .= '</div>'; // pview-desc-deepercontent-img
                $output_html .= '<div class="pview-desc-deepercontent-content">';
                    $output_html .= '<div class="pview-desc-deepercontent-title">';
                    $output_html .=  $item->getDeepercontentTitle();
                    $output_html .= '</div>'; // pview-desc-deepercontent-title
                    $output_html .= '<div class="pview-desc-deepercontent-desc">';
                    $output_html .=  $item->getDeepercontent();
                    $output_html .= '</div>'; // pview-desc-deepercontent-desc
                $output_html .= '</div>'; // pview-desc-deepercontent-content

            $output_html .= '</div>'; // pview-desc-deepercontent-row
            $count++;
        }

        return $output_html;
    }


}

