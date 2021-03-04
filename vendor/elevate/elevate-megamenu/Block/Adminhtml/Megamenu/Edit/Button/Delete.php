<?php
namespace Elevate\Megamenu\Block\Adminhtml\Megamenu\Edit\Button;

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
    $entity_id = $this->registry->registry('megamenu')->getEntityId();
    if ($entity_id) {
      $data = [
        'label' => __('Delete Item'),
        'class' => 'delete',
        'on_click' => 'deleteConfirm(\'' . __(
            'Are you sure you want to do this?'
          ) . '\', \'' . $this->getUrl('elevate_megamenu/index/delete', ['entity_id' => $entity_id]) . '\')',
        'sort_order' => 20,
      ];
    }
    return $data;
  }
}
