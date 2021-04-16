<?php

namespace Firebear\ConfigurableProducts\Controller\Download;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Action\Context;


class DownloadCustomOption extends \Magento\Framework\App\Action\Action
{
    /**
     * @var Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_downloader;

    /**
     * @var Magento\Framework\Filesystem\DirectoryList
     */
    protected $_directory;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $_file;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;

    /**
     * FileFactory $fileFactory
     * DirectoryList $directory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem\DirectoryList $directory,
        \Magento\Framework\Filesystem\Driver\File $file,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->_downloader =  $fileFactory;
        $this->directory = $directory;
        $this->_file = $file;
        $this->_filesystem = $filesystem;
        parent::__construct(
            $context
        );
    }

    /**
     * Custom options download action
     *
     */
    public function execute()
    {
        $fileName = $this->getRequest()->getParam('fileName');
        $filePath = str_replace('-', '/', $this->getRequest()->getParam('path'));
        $file = $this->directory->getPath("media") . '/' . $filePath;
        $this->_downloader->create(
            $fileName,
            $this->_file->fileGetContents($file)
        );
        $tmpDirPath = $this->_filesystem->getDirectoryWrite(DirectoryList::ROOT)->getAbsolutePath() . $fileName;
        $this->_file->deleteFile($tmpDirPath);
    }

}