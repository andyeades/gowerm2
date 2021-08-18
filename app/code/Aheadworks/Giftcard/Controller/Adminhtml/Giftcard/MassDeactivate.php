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
namespace Aheadworks\Giftcard\Controller\Adminhtml\Giftcard;

use Aheadworks\Giftcard\Model\Source\Giftcard\Status;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class MassDeactivate
 *
 * @package Aheadworks\Giftcard\Controller\Adminhtml\Giftcard
 */
class MassDeactivate extends MassAbstract
{
    /**
     * {@inheritdoc}
     */
    protected function massAction($collection)
    {
        $count = 0;
        foreach ($collection->getItems() as $item) {
            try {
                $giftcardCode = $this->giftcardRepository->get($item->getId());
                $giftcardCode->setState(Status::DEACTIVATED);
                $this->giftcardRepository->save($giftcardCode);
                $count++;
            } catch (LocalizedException $exception) {
                $this->logger->critical($exception->getMessage());
            }
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 Gift Card code(s) have been deactivated', $count));
    }
}
