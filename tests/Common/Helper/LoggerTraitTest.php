<?php

namespace App\Tests\Common\Helper;

use App\Common\Helper\LoggerTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class LoggerTraitTest extends TestCase
{
    use LoggerTrait;

    /**
     * @var MockObject|LoggerInterface
     */
    private $logger;

    protected function setUp()
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->setLogger($this->logger);
    }

    public function testConstruct()
    {
        self::assertInstanceOf(LoggerInterface::class, $this->logger);
    }

    public function testEmergency()
    {
        $this->logger
            ->expects(self::once())
            ->method('emergency');

        $this->logEmergency('emergency_message');
    }

    public function testLogAlert()
    {
        $this->logger
            ->expects(self::once())
            ->method('alert');

        $this->logAlert('alert_message');
    }

    public function testLogCritical()
    {
        $this->logger
            ->expects(self::once())
            ->method('critical');

        $this->logCritical('critical_message');
    }

    public function testLogError()
    {
        $this->logger
            ->expects(self::once())
            ->method('error');

        $this->logError('error_message');
    }

    public function testLogWarning()
    {
        $this->logger
            ->expects(self::once())
            ->method('warning');

        $this->logWarning('warning_message');
    }

    public function testLogNotice()
    {
        $this->logger
            ->expects(self::once())
            ->method('notice');

        $this->logNotice('notice_message');
    }

    public function testLogInfo()
    {
        $this->logger
            ->expects(self::once())
            ->method('info');

        $this->logInfo('info_message');
    }

    public function testLogDebug()
    {
        $this->logger
            ->expects(self::once())
            ->method('debug');

        $this->logDebug('debug_message');
    }
}
