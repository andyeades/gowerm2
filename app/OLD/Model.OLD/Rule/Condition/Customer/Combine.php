<?php


namespace Elevate\Microsite\Model\Rule\Condition\Customer;

class Combine extends \Magento\Rule\Model\Condition\Combine
{
    /**
     * @var \Amasty\RulesPro\Model\Rule\Condition\CustomerFactory
     */
    private $conditionCustomerFactory;

    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Elevate\Microsite\Model\Rule\Condition\CustomerFactory $conditionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->conditionCustomerFactory = $conditionFactory;
        $this->setType('Elevate\Microsite\Model\Rule\Condition\Customer\Combine');
    }

    /**
     * Get inherited conditions selectors
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $options = parent::getNewChildSelectOptions();

        /** @var \Amasty\RulesPro\Model\Rule\Condition\Customer $condition */
        $condition = $this->conditionCustomerFactory->create();
        $conditionAttributes = $condition->loadAttributeOptions()->getAttributeOption();

        $options[] = [
            'value' => 'Elevate\Microsite\Model\Rule\Condition\Customer\Combine',
            'label' => __('Conditions Combination'),
        ];
        $attributes = [];
        foreach ($conditionAttributes as $code => $label) {
            $attributes[] = [
                'value' => 'Elevate\Microsite\Model\Rule\Condition\Customer' . '|' . $code,
                'label' => $label,
            ];
        }
        $options[] = [
            'value' => $attributes,
            'label' => __('Customer attributes'),
        ];

        return $options;
    }

    /**
     * @param \Magento\Customer\Model\ResourceModel\Customer\Collection $productCollection
     * @return $this
     */
    public function collectValidatedAttributes($productCollection)
    {
        foreach ($this->getConditions() as $condition) {
            /** @var \Elevate\Microsite\Model\Rule\Condition\Customer $condition */
            $condition->collectValidatedAttributes($productCollection);
        }
        return $this;
    }
}
