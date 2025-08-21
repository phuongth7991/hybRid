<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserEnum extends Enum
{
    public const STT_ACTIVE   = 1;
    public const STT_INACTIVE = 2;

    public static function getStatus(): array
    {
        return [
            ""                 => __('Chọn trạng thái'),
            self::STT_ACTIVE   => __('Hoạt động'),
            self::STT_INACTIVE => __('Không hoạt động'),
        ];
    }

}
