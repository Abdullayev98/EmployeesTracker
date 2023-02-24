<?php

namespace App\Traits;


use JetBrains\PhpStorm\ArrayShape;

class Paginator
{
    public static function advansedPaginate($pagination): array
    {
        return [
            'current_page' => $pagination->currentPage(),
            'from' => $pagination->firstItem(),
            'last_page' => $pagination->lastPage(),
            'per_page' => $pagination->perPage(),
            'to' => $pagination->lastItem(),
            'total' => $pagination->total(),
            'data' => $pagination->items(),
        ];
    }
}
