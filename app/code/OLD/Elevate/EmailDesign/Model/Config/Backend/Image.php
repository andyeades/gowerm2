<?php

namespace Elevate\EmailDesign\Model\Config\Backend;
/**
 * Class Image
 * @package Elevate\EmailDesign\Model\Config\Backend
 */
class Image extends \Magento\Config\Model\Config\Backend\Image
{
    const UPLOAD_DIR = 'elevate/emaildesign';
    /**
     * @return string
     */
    protected function _getUploadDir()
    {
        return $this->_mediaDirectory->getAbsolutePath($this->_appendScopeInfo(self::UPLOAD_DIR));
    }
    /**
     * @return bool
     */
    protected function _addWhetherScopeInfo()
    {
        return true;
    }
    /**
     * @return array|string[]
     */
    protected function _getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'png', 'gif'];
    }
    /**
     * @return $this|void
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $file = $this->getFileData();
        $deleteFlag = is_array($value) && !empty($value['delete']);
        if (empty($file)) {
            if ($this->getOldValue() &&  $deleteFlag) {
                $this->_mediaDirectory->delete(self::UPLOAD_DIR . '/' . $this->getOldValue());
            }
            return parent::beforeSave();
        }
        $fileTmpName = $file['tmp_name'];
        if ($this->getOldValue() && ($fileTmpName || $deleteFlag)) {
            $this->_mediaDirectory->delete(self::UPLOAD_DIR . '/' . $this->getOldValue());
        }
        return parent::beforeSave();
    }
}