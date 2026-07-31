<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
       
        // Exclude certain file types from logging
        $excludedExtensions = ['.css', '.js', '.jpg', '.jpeg', '.png', '.gif', '.svg', '.woff', '.woff2', '.ttf', '.ico'];
        $requestedUrl = $request->fullUrl();        
        foreach ($excludedExtensions as $extension) {
            if (str_ends_with($requestedUrl, $extension)) {
                return $next($request);
            }
           
        }
      
        if(str_contains($requestedUrl,'admin/cron/') || str_contains($requestedUrl,'https://hyloship.com/admin/login') || ($requestedUrl == 'https://hyloship.com')){
            return $next($request);
        }
        
        $startTime = microtime(true);
        $response = $next($request);
        $responseTime = microtime(true) - $startTime;
        $responseTimeMs = number_format($responseTime * 1000, 2);
        $responseCode = $response->getStatusCode();


        $user = Auth::guard('admin')->user();
        $ipAddress = $request->ip();
        $requestedData = $request->all();
        $requestedData = empty($requestedData) ? null : json_encode($requestedData);
        $geoLocation = getGeoLocation($ipAddress);
        $userAgent = $request->header('User-Agent');
        $requestMethod = $request->method();
        $city  = explode(',',$geoLocation);
        if(count($city) ==2 && (in_array(rtrim(ltrim($city[1])),array('Unknown','IN')))){
            
            ActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'company_id' => $user ? $user->company_id : 0,
            'link_requested' => $requestedUrl,
            'ip_address' => $ipAddress,
            'requested_data' => $requestedData,
            'user_agent' => $userAgent,
            'geo_location' => $geoLocation,
            'request_method' => $requestMethod,
            'response_code' => $responseCode,
            'response_time' => $responseTimeMs,
           
        ]);

        return $response;
        }else{
            die('bye');
        }
      
        
    }

    
}
