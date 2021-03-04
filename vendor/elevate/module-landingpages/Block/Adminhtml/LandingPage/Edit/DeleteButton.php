<?php
namespace Elevate\LandingPages\Block\Adminhtml\LandingPage\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('Delete Contact'),
            'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to delete this contact ?') . '\', \'' . $this->getDeleteUrl() . '\')',
            'class' => 'delete',
            'sort_order' => 20
        ];
    }

    public function getDeleteUrl()
    {
        $urlInterface = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
        $url = $urlInterface->getCurrentUrl();

        $parts = explode('/', parse_url($url, PHP_URL_PATH));

        $id = '';

        return $this->getUrl('*/*/delete', ['id' => $id]);
    }
}
