<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\User;
use App\Services\JwtService;
use App\Services\UploadDataService;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class PageGuard
{
    protected $jwtService;
    protected $uploadDataService;

    public function __construct(JwtService $jwtService, UploadDataService $uploadDataService)
    {
        $this->jwtService = $jwtService;
        $this->uploadDataService = $uploadDataService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = isset($_COOKIE['jwt_token']) ? $_COOKIE['jwt_token'] : null;

        if ($request->is('login')) {
            if ($token) {
                return Redirect::to('dashboard');
            }
        } else {
            if (!$token) {
                Cookie::queue(Cookie::forget('jwt_token'));
                return Redirect::to('login');
            }
            else {
                try {
                    $payload = $this->jwtService->decode($token);
                    if (time() > $payload['expired']) {
                        Cookie::queue(Cookie::forget('jwt_token'));
                        return Redirect::to('login');
                    }

                    $user = User::find($payload['id']);
                    if (!$user) {
                        Cookie::queue(Cookie::forget('jwt_token'));
                        return Redirect::to('login');
                    }

                    $dataTab = $this->uploadDataService->getTab();

                    $request->merge(['user' => $user, 'dataTab' => $dataTab['data']]);
                }
                catch (\Exception $e) {
                    Cookie::queue(Cookie::forget('jwt_token'));
                    // return Redirect::to('login');
                    abort(500);
                }
            }
        }

        return $next($request);
    }
}
