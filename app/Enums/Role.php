<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'ADMIN';
    case FRIEND = 'FRIEND';
    case USER = 'USER';
    case PARTNER = 'PARTNER';
    case AFFILIATE = 'AFFILIATE';
}
