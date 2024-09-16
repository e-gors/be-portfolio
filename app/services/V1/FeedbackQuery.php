<?php

namespace App\Services\V1;

use App\Services\ApiFilter;

class FeedbackQuery extends ApiFilter
{
    protected $allowedParams = [
        'guestName' => ['eq'],
        'project' => ['eq'],
        'message' => ['eq'],
        'status' => ['eq', 'ne'],
        'rating' => ['eq', 'lt', 'lte', 'gt', 'gte'],
    ];

    protected $columnMap = [
        'guestName' => "guest_name",
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!='
    ];
}
