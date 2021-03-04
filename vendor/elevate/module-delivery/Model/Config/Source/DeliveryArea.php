<?php

namespace Elevate\Delivery\Model\Config\Source;

use \Magento\Framework\Data\OptionSourceInterface;


/**
 * Class DeliveryArea
 *
 * @package Elevate\Delivery\Model\Config\Source
 */
class DeliveryArea implements OptionSourceInterface
{

    protected $helper;
    protected $deliveryAreaRepository;
    /**

     * @param \Elevate\Delivery\Api\DeliveryAreaRepositoryInterface $deliveryAreaRepository
     * @param \Elevate\Delivery\Helper\General $helper
     *

     *
     *
     */
    public function __construct(
        \Elevate\Delivery\Api\DeliveryAreaRepositoryInterface $deliveryAreaRepository,
        \Elevate\Delivery\Helper\General $helper
    ) {
        $this->deliveryAreaRepository = $deliveryAreaRepository;
        $this->helper = $helper;
    }
    
    /**
     *
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $filters = array(
            array(
                'field' => 'deliveryarea_id',
                'value' => '',
                'condition_type' => 'notnull'
            )
        );

        $sortorder = array(
            'field' => 'deliveryarea_id',
            'direction' => 'DESC'
        );


        $searchCriteria = $this->helper->buildSearchCriteria($filters, $sortorder);
        $options = $this->deliveryAreaRepository->getList($searchCriteria);


        $output = array();

        foreach ($options->getItems() as $option) {

            $inneroption = $option->getAllData();

            $new_array = array(
                'value' => $inneroption['deliveryarea_id'],
                'label' => $inneroption['deliveryarea_id'].' - '.$inneroption['name']
            );

            $output[] = $new_array;
        }

        return $output;
    }
}