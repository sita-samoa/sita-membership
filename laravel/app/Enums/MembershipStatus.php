<?php

namespace App\Enums;

enum MembershipStatus: int
{
  case DRAFT = 1;
  case SUBMITTED = 2;
  case ENDORSED = 3;
  case ACCEPTED = 4;
  case LAPSED = 5;
  case EXPIRED = 6;
  case BANNED = 7;
}
