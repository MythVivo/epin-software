<?php 
namespace App\Http\Middleware;

use Closure;

class RequestGuard
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       var_dump($request->all());
        exit();
        /* $input = $request->all();

        if (isset($input['mod'])) {
            list($input['int'], $input['text']) = explode('-', $input['mod']);
            unset($input['mod']);
            // Input modification
            $request->replace($input);
        } */

        return $next($request);
    }

}