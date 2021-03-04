<?php
namespace Elevate\CartAssignments\Model\Overrides\Cart;

class ImageProvider extends \Magento\Checkout\Model\Cart\ImageProvider
{
    /**
     * {@inheritdoc}
     */
    public function getImages($cartId)
    {
        $itemData = [];

        /** @see code/Magento/Catalog/Helper/Product.php */
        $items = $this->itemRepository->getList($cartId);
        /** @var \Magento\Quote\Model\Quote\Item $cartItem */
        foreach ($items as $cartItem) {
            $allData = $this->customerDataItem->getItemData($cartItem);

            $bespoke_image = $cartItem->getBespokeImage();
            if (!empty($bespoke_image)) {


                $itemData[$cartItem->getItemId()]['src'] = $bespoke_image;
                $itemData[$cartItem->getItemId()]['alt'] = '';
                $itemData[$cartItem->getItemId()]['width'] = '75';
                $itemData[$cartItem->getItemId()]['height'] = '75';

            }
            else{
                $itemData[$cartItem->getItemId()] = $allData['product_image'];

            }

        }
        return $itemData;
    }
}