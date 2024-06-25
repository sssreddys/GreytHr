<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CheckAuthAndHandleSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Define an array of guards to check
            $guards = ['emp', 'it', 'hr', 'com', 'finance', 'admins'];

            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {
                    $user = Auth::guard($guard)->user();

                    switch ($guard) {
                        case 'emp':
                            $id = $user->emp_id;
                            break;
                        case 'it':
                            $id = $user->it_emp_id;
                            break;
                        case 'hr':
                            $id = $user->hr_emp_id;
                            break;
                        case 'com':
                            $id = $user->company_id;
                            break;
                        case 'finance':
                            $id = $user->fi_emp_id;
                            break;
                        case 'admins':
                            $id = $user->admin_emp_id;
                            break;
                        default:
                            $id = null;
                            break;
                    }

                    // Invalidate previous sessions for the user
                    DB::table('sessions')
                        ->where('user_id', $id)
                        ->delete();

                    Session::put($guard . '_id', $id);
                    Log::info("$guard ID set: $id");
                    Session::put('user_type', $guard);

                    // Get GeoIP data
                    $geoIpData = geoip()->getLocation(geoip()->getClientIP());
                    $userAgent = request()->header('User-Agent');

                    // Store data in session table
                    DB::table('sessions')->updateOrInsert(
                        ['id' => session()->getId()],
                        [
                            'user_id' => $id,
                            'ip_address' => $geoIpData['ip'],
                            'user_agent' => $userAgent,
                            'iso_code' => $geoIpData['iso_code'],
                            'country' => $geoIpData['country'],
                            'city' => $geoIpData['city'],
                            'state' => $geoIpData['state'],
                            'state_name' => $geoIpData['state_name'],
                            'postal_code' => $geoIpData['postal_code'],
                            'latitude' => $geoIpData['lat'],
                            'longitude' => $geoIpData['lon'],
                            'timezone' => $geoIpData['timezone'],
                            'continent' => $geoIpData['continent'],
                            'currency' => $geoIpData['currency'],
                            'payload' => '', // Example: You can store session payload if needed
                            'last_activity' => now()->timestamp,
                            'created_at' => now(), // Set the created_at timestamp
                            'updated_at' => now(), // Set the updated_at timestamp
                        ]
                    );

                    // If user is authenticated, stop checking other guards
                    break;
                }
            }

            if (!Auth::check()) {
                session(['user_type' => 'guest']);
                Log::info('Session has timed out');
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('An error occurred while processing the session:', ['error' => $e->getMessage()]);

            // Return a user-friendly error message
            return response()->view('errors.500', [], 500); // Assuming you have a custom error view for 500 errors
        }

        return $next($request);
    }
}



// <?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Session;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\DB;
// use Jenssegers\Agent\Agent;
// use Carbon\Carbon;

// class CheckAuthAndHandleSession
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//      */
//     public function handle(Request $request, Closure $next): Response
//     {
//         // Define an array of guards to check
//         $guards = ['emp', 'it', 'hr', 'com', 'finance', 'admins'];

//         foreach ($guards as $guard) {
//             if (Auth::guard($guard)->check()) {
//                 $user = Auth::guard($guard)->user();

//                 $id = $this->getUserId($guard, $user);

//                 Session::put($guard . '_id', $id);
//                 Log::info("$guard ID set: $id");
//                 Session::put('user_type', $guard);

//                 // Get the client's real IP address
//                 $clientIp = $request->getClientIp();

//                 // Get GeoIP data
//                 $geoIpData = $this->getGeoIpData($clientIp);
//                 $userAgent = $request->header('User-Agent');

//                 // Get device information using jenssegers/agent
//                 $agent = new Agent();
//                 $agent->setUserAgent($userAgent);

//                 $device = $agent->device();
//                 $platform = $agent->platform();
//                 $browser = $agent->browser();
//                 $isDesktop = $agent->isDesktop();
//                 $isMobile = $agent->isMobile();

//                 // Store data in session table
//                 DB::table('sessions')->updateOrInsert(
//                     ['id' => session()->getId()],
//                     [
//                         'user_id' => $id,
//                         'ip_address' => $geoIpData['ip'],
//                         'user_agent' => $userAgent,
//                         'device' => $device,
//                         'platform' => $platform,
//                         'browser' => $browser,
//                         'is_desktop' => $isDesktop,
//                         'is_mobile' => $isMobile,
//                         'iso_code' => $geoIpData['iso_code'],
//                         'country' => $geoIpData['country'],
//                         'city' => $geoIpData['city'],
//                         'state' => $geoIpData['state'],
//                         'state_name' => $geoIpData['state_name'],
//                         'postal_code' => $geoIpData['postal_code'],
//                         'latitude' => $geoIpData['lat'],
//                         'longitude' => $geoIpData['lon'],
//                         'timezone' => $geoIpData['timezone'],
//                         'continent' => $geoIpData['continent'],
//                         'currency' => $geoIpData['currency'],
//                         'payload' => '', // Example: You can store session payload if needed
//                         'last_activity' => Carbon::now()->timestamp,
//                         'created_at' => Carbon::now(), // Set the created_at timestamp
//                         'updated_at' => Carbon::now(), // Set the updated_at timestamp
//                     ]
//                 );

//                 // If user is authenticated, stop checking other guards
//                 break;
//             }
//         }

//         if (!Auth::check()) {
//             session(['user_type' => 'guest']);
//             Log::info('Session has timed out');
//         }

//         return $next($request);
//     }

//     /**
//      * Get the user ID based on guard.
//      *
//      * @param string $guard
//      * @param \Illuminate\Contracts\Auth\Authenticatable $user
//      * @return mixed
//      */
//     private function getUserId($guard, $user)
//     {
//         switch ($guard) {
//             case 'emp':
//                 return $user->emp_id;
//             case 'it':
//                 return $user->it_emp_id;
//             case 'hr':
//                 return $user->hr_emp_id;
//             case 'com':
//                 return $user->company_id;
//             case 'finance':
//                 return $user->fi_emp_id;
//             case 'admins':
//                 return $user->admin_emp_id;
//             default:
//                 return null;
//         }
//     }

//     /**
//      * Get GeoIP data for the given IP address.
//      *
//      * @param string $ip
//      * @return array
//      */
//     private function getGeoIpData($ip)
//     {
//         // Use GeoIP to get location data
//         $geoIp = geoip()->getLocation($ip);

//         return [
//             'ip' => $geoIp->ip,
//             'iso_code' => $geoIp->iso_code,
//             'country' => $geoIp->country,
//             'city' => $geoIp->city,
//             'state' => $geoIp->state,
//             'state_name' => $geoIp->state_name,
//             'postal_code' => $geoIp->postal_code,
//             'lat' => $geoIp->lat,
//             'lon' => $geoIp->lon,
//             'timezone' => $geoIp->timezone,
//             'continent' => $geoIp->continent,
//             'currency' => $geoIp->currency,
//         ];
//     }
// }
