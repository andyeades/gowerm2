<?php
declare(strict_types=1);

namespace Firebear\ConfigurableProducts\Controller\Download;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Class DownloadCustomOption
 * @package Firebear\ConfigurableProducts\Controller\Download
 */
class DownloadCustomOption extends Action
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
     * @var File
     */
    protected $_file;

    /**
     * @var Filesystem
     */
    protected $_filesystem;
    /**
     * @var Filesystem\DirectoryList
     */
    protected $directory;

    /**
     * FileFactory $fileFactory
     * DirectoryList $directory
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param Filesystem\DirectoryList $directory
     * @param File $file
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        Filesystem\DirectoryList $directory,
        File $file,
        Filesystem $filesystem
    ) {
        $this->_downloader = $fileFactory;
        $this->directory = $directory;
        $this->_file = $file;
        $this->_filesystem = $filesystem;
        parent::__construct(
            $context
        );
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\FileSystemException
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
