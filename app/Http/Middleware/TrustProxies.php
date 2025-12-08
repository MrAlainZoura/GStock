<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * Les proxies de confiance.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*'; // Tous les proxies (Hostinger inclus)

    /**
     * Les en-têtes utilisés pour détecter l’IP.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR;
}