<?php

namespace App\Enums;

enum MembershipType: int
{
    case FULL = 1;
    case ASSOCIATED = 2;
    case AFFILIATE = 3;
    case STUDENT = 4;
    case FELLOW = 5;
}
