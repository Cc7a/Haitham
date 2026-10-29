<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiteOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $ownerEmail = (string) config('afaq.owner_email', '');
        $currentEmail = strtolower(trim((string) $request->user()?->email));

        abort_unless($ownerEmail !== '' && hash_equals($ownerEmail, $currentEmail), 403);

        return $next($request);
    }
}