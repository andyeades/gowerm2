<?php

namespace Elevate\Themeoptions\Model\Config\Source;

class Footer implements \Magento\Framework\Data\OptionSourceInterface
{

    protected $helper;
    protected $footerRepository;
    /**

     * @param \Elevate\Themeoptions\Api\FooterRepositoryInterface $footerRepository
     * @param \Elevate\Themeoptions\Helper\General $helper
     *

     *
     *
     */
    public function __construct(
        \Elevate\Themeoptions\Api\FooterRepositoryInterface $footerRepository,
        \Elevate\Themeoptions\Helper\General $helper
    ) {
        $this->footerRepository = $footerRepository;
        $this->helper = $helper;
    }
    /**
     * Return list of Theme Footer
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
        $footer = $this->footerRepository->getList($searchCriteria);


        $output = array();

        foreach ($footer->getItems() as $option) {

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