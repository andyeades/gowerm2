<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\Themeoptions\Preprocessor\Adapter\Scss;

use Magento\Framework\App\Filesystem\DirectoryList;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\State;
use Magento\Framework\Phrase;
use Magento\Framework\View\Asset\File;
use Magento\Framework\View\Asset\Source;
use Magento\Framework\Css\PreProcessor\Config;
use Magento\Framework\View\Asset\ContentProcessorInterface;
use ScssPhp\ScssPhp\Compiler;
use Magento\Framework\View\Asset\ContentProcessorException;


/**
 * Class Processor
 */
class Processor implements ContentProcessorInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    /**
     * @var Source
     */
    private $assetSource;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var \ScssPhp\ScssPhp\Compiler
     */
    protected $compiler;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $ioFile;

    /**
     * Constructor
     *
     * @param Source $assetSource
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\View\Asset\Source $assetSource,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Css\PreProcessor\Config $config,
        \Magento\Framework\Filesystem\Io\File $ioFile,
        \ScssPhp\ScssPhp\Compiler $compiler
    )
    {
        $this->logger = $logger;
        $this->appState = $appState;
        $this->assetSource = $assetSource;
        $this->directoryList = $directoryList;
        $this->config = $config;
        $this->ioFile = $ioFile;
        $this->compiler = $compiler;
    }

    /**
     * Process file content
     *
     * @param File $asset
     * @return string
     */
    public function processContent(File $asset)
    {
        $path = $asset->getPath();
        try {
            /** @var \ScssPhp\ScssPhp\Compiler $compiler */
            $compiler = new Compiler();
            if ($this->appState->getMode() !== State::MODE_DEVELOPER) {
                $compiler->setFormatter(\ScssPhp\ScssPhp\Formatter\Compressed::class);
            }
            $compiler->setImportPaths([
                                          $this->directoryList->getPath(DirectoryList::VAR_DIR)
                                          . '/' . $this->config->getMaterializationRelativePath()
                                          . '/' . $this->ioFile->dirname($path)]);
            $content = $this->assetSource->getContent($asset);
            if (trim($content) === '') {
                return '';
            }
            gc_disable();
            $content = $compiler->compile($content);
            gc_enable();
            if (trim($content) === '') {
                $this->logger->warning('Parsed scss file is empty: ' . $path);
                return '';
            }
            return $content;
        } catch (\Exception $e) {
            throw new ContentProcessorException(new Phrase($e->getMessage()));
        }
    }
}
