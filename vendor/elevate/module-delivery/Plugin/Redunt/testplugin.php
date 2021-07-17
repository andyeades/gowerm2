<?php

namespace Elevate\Delivery\Plugin;

use Magento\Quote\Api\Data\AddressExtensionInterface;
use Magento\Quote\Api\Data\AddressExtensionFactory;
use Magento\Quote\Api\Data\AddressInterface;


class testplugin
{
    /**
     * @var AddressExtensionFactory
     */
    private $extensionFactory;

    /**
     * @param AddressExtensionFactory $extensionFactory
     */
    public function __construct(AddressExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * Loads entity extension attributes
     *
     * @param AddressInterface $entity
     * @param AddressExtensionInterface|null $extension
     * @return AddressExtensionInterface
     */
    public function afterGetExtensionAttributes(
        AddressInterface $entity,
        AddressExtensionInterface $extension = null
    ) {
        if ($extension === null) {
            $extension = $this->extensionFactory->create();
        }

        return $extension;
    }
}
