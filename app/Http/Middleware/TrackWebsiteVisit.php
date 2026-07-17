<?php

namespace App\Http\Middleware;

use App\Models\WebsiteVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class TrackWebsiteVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            $request->isMethod('GET') &&
            $request->hasSession() &&
            ! $request->is('admin*') &&
            ! $request->is('api*') &&
            ! $request->is('midtrans*') &&
            ! $request->is('storage*') &&
            Schema::hasTable('website_visits')
        ) {
            WebsiteVisit::updateOrCreate(
                [
                    'session_id' => $request->session()->getId(),
                    'path' => '/' . ltrim($request->path(), '/'),
                    'visited_at' => today(),
                ],
                [
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 255),
                ]
            );
        }

        return $response;
    }
}
