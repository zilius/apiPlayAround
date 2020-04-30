<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\App\Integrations\SuperMetrics\Domain\StatisticsFormatter;

class StatisticsFormatterTest extends TestCase
{
    private StatisticsFormatter $statisticsFormmater;

    public function setUp(): void
    {
        $this->statisticsFormmater = new StatisticsFormatter();
    }

    public function testPostsGroupingByMonth()
    {
        $posts = json_decode(file_get_contents("data/ApiResponse.json", true), true)["data"]["posts"];

        $result = $this->statisticsFormmater->groupMessagesByMonth($posts);

        $this->assertEquals(count($result), 3);
        $this->assertArrayHasKey('2019-12', $result);
        $this->assertArrayHasKey('2019-11', $result);
        $this->assertArrayHasKey('2019-10', $result);
    }
}