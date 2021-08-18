<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Model\Template;

use Magento\Framework\ObjectManagerInterface;
use Magento\Cms\Model\Template\Filter;

/**
 * Template filter provider
 */
class FilterProvider
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var string
     */
    private $filterClassName;

    /**
     * @var \Magento\Framework\Filter\Template|null
     */
    private $filterInstance = null;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param string $filterClassName
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        $filterClassName = Filter::class
    ) {
        $this->objectManager = $objectManager;
        $this->filterClassName = $filterClassName;
    }

    /**
     * Retrieves filter instance
     *
     * @return \Magento\Framework\Filter\Template|mixed|null
     * @throws \Exception
     */
    public function getFilter()
    {
        if ($this->filterInstance === null) {
            $filterInstance = $this->objectManager->get($this->filterClassName);
            if (!$filterInstance instanceof \Magento\Framework\Filter\Template) {
                throw new \Exception(
                    'Template filter ' . $this->filterClassName . ' does not implement required interface.'
                );
            }
            $this->filterInstance = $filterInstance;
        }
        return $this->filterInstance;
    }
}
