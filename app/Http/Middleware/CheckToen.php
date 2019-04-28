<?php

namespace App\Http\Middleware;

use Closure;

class CheckToen
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
        // dd($request->input());
        // echo 2234;die;
        if ($request->input('_token')!='1810') {
            return redirect()->to('http://www.baidu.com');
            return redirect('/');
        }

        return $next($request);
    }
}
