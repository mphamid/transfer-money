<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NormalizeNumberMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, ...$params): Response
    {
        foreach ($params as $param) {
            if (!$request->has($param)) {
                continue;
            }
            $value = $this->convert($request->get($param));
            $request->merge([$param => $value]);
        }
        return $next($request);
    }

    /**
     * @param string $string
     * @return array|string
     */
    private function convert(string $string): array|string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $english = range(0, 9);

        return str_replace($arabic, $english, str_replace($persian, $english, $string));
    }
}
