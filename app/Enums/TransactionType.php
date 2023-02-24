<?php

namespace App\Enums;

enum TransactionType: int
{
    case Enter = 1;
    case Exit = 2;
    case StartBreak = 3;
    case EndBreak = 4;
}
