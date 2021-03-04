<?php
namespace Elevate\ProductIcons\Block\Adminhtml\Producticons\Edit\Button;

/**
 * Class Delete
 */
class Delete extends Generic
{
  /**
   * @return array
   */
  public function getButtonData()
  {
    $data = [];
    $entity_id = $this->registry->registry('producticons')->getIconId();
    if ($entity_id) {
      $data = [
        'label' => __('Delete Item'),
        'class' => 'delete',
        'on_click' => 'deleteConfirm(\'' . __(
            'Are you sure you want to do this?'
          ) . '\', \'' . $this->getUrl('producticons/index/delete', ['icon_id' => $entity_id]) . '\')',
        'sort_order' => 20,
      ];
    }
    return $data;
  }
}
