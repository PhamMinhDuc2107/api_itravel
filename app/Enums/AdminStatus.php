<?php

    namespace App\Enums;

    enum AdminStatus: int
    {
        case INACTIVE = 1;
        case ACTIVE = 2;

        public function label(): string
        {
            return match ($this) {
                self::INACTIVE => 'Không hoạt động',
                self::ACTIVE => 'Hoạt động',
            };
        }
    }
