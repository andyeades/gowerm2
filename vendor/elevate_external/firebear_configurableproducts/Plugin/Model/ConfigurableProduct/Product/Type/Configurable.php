<?php

namespace Firebear\ConfigurableProducts\Plugin\Model\ConfigurableProduct\Product\Type;

use Firebear\ConfigurableProducts\Helper\Data as IcpHelper;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

class Configurable extends Collection
{
    /**
     * @var IcpHelper
     */
    public $icpHelper;

    /**
     * Configurable constructor.
     * @param IcpHelper $icpHelper
     */
    public function __construct(IcpHelper $icpHelper)
    {
        $this->icpHelper = $icpHelper;
    }

    public function aroundAddFilterByRequiredOptions(Collection $subject, callable $proceed)
    {
        $isBundleResourceModel = $subject instanceof \Magento\Bundle\Model\ResourceModel\Selection\Collection;
        $useCustomOptions = $this->icpHelper->getGeneralConfig('general/use_custom_options_for_variations');
        if ($useCustomOptions || $isBundleResourceModel) {
            return $subject;
        } else {
            return $proceed();
        }
    }
}
