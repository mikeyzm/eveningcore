<?php

use App\Enums\ConvertStatus;

return [
    ConvertStatus::class => [
        ConvertStatus::Pending => '等待中',
        ConvertStatus::Converting => '转换中',
        ConvertStatus::Converted => '已转换',
        ConvertStatus::Failed => '失败',
        ConvertStatus::Expired => '已过期',
    ]
];
