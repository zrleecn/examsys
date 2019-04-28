<?php

namespace app\http\middleware;

class Login
{


    /**
     *
     * 登录验证中间件
     * @param $request
     * @param \Closure $next
     * @return mixed|\think\response\Redirect
     */
    public function handle($request, \Closure $next)
    {
        if (! session('user')){

            return redirect(url('/login'));
        }

        return $next($request);
    }
}
