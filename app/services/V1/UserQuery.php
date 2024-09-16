<?php

namespace App\Services\V1;

use App\Services\ApiFilter;

class UserQuery extends ApiFilter
{
    protected $allowedParams = [
        'name' => ['eq'],
        'role' => ['eq'],
        'userType' => ['eq'],
        'status' => ['eq', 'ne'],
        'bannedUntil' => ['eq', 'lt', 'lte', 'gt', 'gte'],
        'isVerified' => ['eq']
    ];

    protected $columnMap = [
        'userType' => "user_type",
        'bannedUntil' => 'banned_until',
        'isVerified' => "email_verified_at"
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
