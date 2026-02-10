<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PreventDuplicateSubmissions
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('POST')) {
            $key = 'submit_' . $request->user()->id . '_' . md5($request->fullUrl() . json_encode($request->except('_token')));
            
            if (Cache::has($key)) {
                return redirect()->back()->with('notify', [[
                    'type' => 'warning',
                    'message' => __('global.duplicate_submission_prevented')
                ]]);
            }
            
            Cache::put($key, true, 10);
        }
        
        return $next($request);
    }
}
