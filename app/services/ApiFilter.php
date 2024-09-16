<?php

namespace App\Services;

use Illuminate\Http\Request;

class ApiFilter
{
    protected $allowedParams = [];

    protected $columnMap = [];

    protected $operatorMap = [];

    public function transform(Request $request)
    {
        $eloQuery  = [];

        foreach ($this->allowedParams as $parm => $operators) {
            $query = $request->query($parm);

            if (!isset($query)) {
                continue;
            }

            $column = $this->columnMap[$parm] ?? $parm;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    // Special handling for boolean-like filtering
                    if ($column === 'email_verified_at') {
                        if ($query[$operator] == 'true') {
                            $eloQuery[] = [$column, '!=', null];
                        } elseif ($query[$operator] == 'false') {
                            $eloQuery[] = [$column, '=', null];
                        }
                    } else {
                        $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                    }
                }
            }
        }

        return $eloQuery;
    }
}
