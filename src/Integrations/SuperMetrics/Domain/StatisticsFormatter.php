<?php

declare(strict_types=1);

namespace Src\App\Integrations\SuperMetrics\Domain;

use DateTime;

class StatisticsFormatter
{

    public function groupMessagesByMonth(array $posts): array
    {
        $postsByMonth = [];

        //assign each post to each month
        foreach ($posts as $post) {

            $time = DateTime::createFromFormat('Y-m-d\TH:i:s+', $post['created_time'])->format('Y-m');
            $postsByMonth[$time][] = $post;
        }

        return $postsByMonth;
    }
}