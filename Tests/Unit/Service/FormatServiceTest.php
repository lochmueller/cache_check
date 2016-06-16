<?php
/**
 * FormatServiceTest
 */

namespace HDNET\CacheCheck\Tests\Unit\Service;

use HDNET\CacheCheck\Service\FormatService;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * FormatServiceTest
 */
class FormatServiceTest extends UnitTestCase
{

    /**
     * @test
     */
    public function testFormatSeconds()
    {
        $service = new FormatService();
        $seconds = 60;

        $this->assertSame('1 minute', $service->formatSeconds($seconds));
    }

    /**
     * @test
     */
    public function testWrongFormatSeconds()
    {
        $service = new FormatService();
        $seconds = 'String Check';

        $this->assertSame('NaN', $service->formatSeconds($seconds));
    }
}
