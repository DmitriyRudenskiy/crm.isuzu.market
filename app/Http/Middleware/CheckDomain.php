<?php

namespace App\Http\Middleware;

use App\Repositories\SitesRepository;
use Closure;

class CheckDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $repository = new SitesRepository(app());
        $domainId = $repository->get($request->getHost());

        putenv('APP_DOMAIN_ID=' . $domainId);

        return $next($request);
    }
}
