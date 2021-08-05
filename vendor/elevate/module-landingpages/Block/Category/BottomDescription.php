<?php
namespace Elevate\LandingPages\Block\Category;


class BottomDescription extends \Magento\Framework\View\Element\Template
{


protected $_registry;
protected $_filterProvider;
protected $_storeManager;
protected $_faqCol;

	public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Framework\Registry $registry,
    \Magento\Cms\Model\Template\FilterProvider $filterProvider,
    \Elevate\LandingPages\Model\ResourceModel\LandingPageFaq\CollectionFactory $faqCol,
    \Magento\Store\Model\StoreManagerInterface $storeManager

    )
	{
    $this->_registry = $registry;
    $this->_filterProvider = $filterProvider;
     $this->_faqCol = $faqCol;
    $this->_storeManager = $storeManager;
    
		parent::__construct($context);
	}

	public function getFilterProvider()
	{
		return $this->_filterProvider;
	}
    	public function getFaqCol()
	{
		return $this->_faqCol;
	}
    	public function getStoreManager()
	{
		return $this->_storeManager;
	}
   public function getRegistry()
	{
		return $this->_registry;
	}
    
}