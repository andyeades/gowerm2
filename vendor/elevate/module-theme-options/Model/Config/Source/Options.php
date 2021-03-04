<?php

namespace Elevate\Themeoptions\Model\Config\Source;

class Options implements \Magento\Framework\Data\OptionSourceInterface
{

    protected $helper;
    protected $optionsRepository;
    /**

     * @param \Elevate\Themeoptions\Api\OptionsRepositoryInterface $optionsRepository
     * @param \Elevate\Themeoptions\Helper\General $helper
     *

     *
     *
     */
    public function __construct(
        \Elevate\Themeoptions\Api\OptionsRepositoryInterface $optionsRepository,
        \Elevate\Themeoptions\Helper\General $helper
    ) {
        $this->optionsRepository = $optionsRepository;
        $this->helper = $helper;
    }
    /**
     * Return list of Theme Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $filters = array(
            array(
                'field' => 'entity_id',
                'value' => '',
                'condition_type' => 'notnull'
            )
        );

        $sortorder = array(
            'field' => 'entity_id',
            'direction' => 'DESC'
        );


        $searchCriteria = $this->helper->buildSearchCriteria($filters, $sortorder);
        $options = $this->optionsRepository->getList($searchCriteria);


        $output = array();

        foreach ($options->getItems() as $option) {

          $inneroption = $option->getAllData();

          $new_array = array(
              'value' => $inneroption['entity_id'],
              'label' => $inneroption['entity_id']
          );

          $output[] = $new_array;
        }

        return $output;
    }
}