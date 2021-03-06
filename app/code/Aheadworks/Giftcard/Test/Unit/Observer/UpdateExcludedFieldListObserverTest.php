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
namespace Aheadworks\Giftcard\Test\Unit\Observer;

use Aheadworks\Giftcard\Observer\UpdateExcludedFieldListObserver;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event;
use Magento\Catalog\Block\Adminhtml\Product\Edit\Action\Attribute\Tab\Attributes;

/**
 * Class UpdateExcludedFieldListObserverTest
 * Test for \Aheadworks\Giftcard\Observer\UpdateExcludedFieldListObserver
 *
 * @package Aheadworks\Giftcard\Test\Unit\Observer
 */
class UpdateExcludedFieldListObserverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var UpdateExcludedFieldListObserver
     */
    private $object;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->object = $objectManager->getObject(
            UpdateExcludedFieldListObserver::class,
            []
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $blockMock = $this->getMockBuilder(Attributes::class)
            ->setMethods(['getFormExcludedFieldList', 'setFormExcludedFieldList'])
            ->disableOriginalConstructor()
            ->getMock();
        $blockMock->expects($this->once())
            ->method('getFormExcludedFieldList')
            ->willReturn([]);
        $eventMock = $this->getMockBuilder(Event::class)
            ->setMethods(['getObject'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventMock->expects($this->once())
            ->method('getObject')
            ->willReturn($blockMock);
        $observerMock = $this->getMockBuilder(Observer::class)
            ->setMethods(['getEvent'])
            ->disableOriginalConstructor()
            ->getMock();
        $observerMock->expects($this->once())
            ->method('getEvent')
            ->willReturn($eventMock);

        $blockMock->expects($this->once())
            ->method('setFormExcludedFieldList')
            ->willReturnSelf();

        $this->object->execute($observerMock);
    }
}
