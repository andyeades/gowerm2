<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SecureTrading\Trust\Model\Config\Source;

use Magento\Payment\Model\MethodInterface;

class Endpoint implements \Magento\Framework\Option\ArrayInterface
{
	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray()
	{
		return [['value' => 'https://payments.securetrading.net/', 'label' => 'EU'],
				['value' => 'https://payments.securetrading.us/', 'label' => 'US']];
	}
}