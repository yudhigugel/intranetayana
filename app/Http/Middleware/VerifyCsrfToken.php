<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'logout',
        'zoho-api/formRequestEntertainment',
        'finance/*',
        'webservice/talenta/*',
        'webservice/zapier/*',
        'webservice/bi/*',
        'sap/inventory/*',
        'report/ajax/*',
        'notifications/*',
        'finance/purchase-requisition/*',
        'finance/cash-advance/*',
        'vcc-parsing/*',
    ];
}
