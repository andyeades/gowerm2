<?php
namespace Elevate\Microsite\Model\ResourceModel;


class Microsite extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}

	protected function _construct()
	{
		$this->_init('elevate_microsite_microsite', 'microsite_id');
	}

}