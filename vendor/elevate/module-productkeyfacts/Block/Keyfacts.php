<?php


namespace Elevate\ProductKeyFacts\Block;

/**
 * Class Keyfacts
 *
 * @category Elevate
 * @package  Elevate\ProductKeyFacts\Block
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class Keyfacts extends \Magento\Framework\View\Element\Template {
  /**
   * @var \Magento\Framework\Registry
   */
  protected $_registry;

  /**
   * @var \Magento\Variable\Model\VariableFactory
   */
  protected $_varFactory;

  /**
   * Constructor
   *
   * @param \Magento\Variable\Model\VariableFactory          $varFactory
   * @param \Magento\Framework\View\Element\Template\Context $context
   * @param array                                            $data
   */
  public function __construct(
    \Magento\Variable\Model\VariableFactory $varFactory,
    \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Framework\Registry $registry, array $data = []) {
    $this->_varFactory = $varFactory;
    $this->_registry = $registry;
    parent::__construct($context, $data);
  }

  /**
   * Retrieve current Product object
   *
   * @return \Magento\Catalog\Model\Product|null
   */
  public function getCurrentProduct() {
    return $this->_registry->registry('current_product');
  }

  /**
   * Get Custom Variable Value
   *
   * @param string $var_to_get
   *
   * @return string
   */
  public function getVariableValue(string $var_to_get) {
    $var = $this->_varFactory->create();
    $var->loadByCode($var_to_get);

    return $var->getValue();
  }

}
