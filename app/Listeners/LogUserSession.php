<?php

namespace App\Listeners;

use App\Models\UserSession;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class LogUserSession
{
    protected $request;

    /**
     * Create the event listener.
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request ?: request();
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $userAgent = $this->request->userAgent() ?? '';

        // Get real IP address (handle proxy/cloudflare)
        $ipAddress = $this->getRealIpAddress();

        // Parse user agent to get device info
        $deviceInfo = $this->parseUserAgent($userAgent);

        // Create session record
        UserSession::create([
            'user_id' => $user->id,
            'ip_address' => $ipAddress,
            'brand' => $deviceInfo['brand'],
            'model' => $deviceInfo['model'],
            'device_id' => md5($userAgent . $ipAddress),
            'os' => $deviceInfo['os'],
            'browser' => $deviceInfo['browser'],
            'language' => $this->request->getPreferredLanguage(['en', 'ar']) ?? 'en',
            'user_agent' => $userAgent,
            'last_activity' => now(),
        ]);
    }

    /**
     * Get real IP address considering proxies and load balancers
     */
    private function getRealIpAddress()
    {
        // Check for CloudFlare
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        // Check for shared internet/proxy
        elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }
        // Check for IP passed from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Can contain multiple IPs, get the first one
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ips[0]);
        }
        // Check for remote address
        elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = request()->ip();
        }

        // If we get a local IP, try to get public IP from external service
        if (
            in_array($ip, ['127.0.0.1', '::1', 'localhost']) ||
            !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
        ) {
            try {
                // Try multiple IP detection services
                $services = [
                    'https://api.ipify.org',
                    'https://ipinfo.io/ip',
                    'https://icanhazip.com',
                    'http://checkip.amazonaws.com'
                ];

                foreach ($services as $service) {
                    try {
                        $context = stream_context_create([
                            'http' => [
                                'timeout' => 3,
                                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                            ]
                        ]);

                        $publicIp = trim(file_get_contents($service, false, $context));
                        if ($publicIp && filter_var($publicIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
                            return $publicIp;
                        }
                    } catch (\Exception $e) {
                        continue; // Try next service
                    }
                }
            } catch (\Exception $e) {
                // Fallback if all APIs fail
            }
        }

        // Validate and return IP
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }

        return '127.0.0.1'; // Ultimate fallback
    }

    /**
     * Parse user agent string to extract device information
     */
    protected function parseUserAgent($userAgent)
    {
        $brand = 'Unknown';
        $model = 'Unknown';
        $os = 'Unknown';
        $browser = 'Unknown';

        // Detect OS with version
        if (preg_match('/Windows NT (\d+\.\d+)/i', $userAgent, $matches)) {
            $version = $matches[1];
            $versions = [
                '10.0' => 'Windows 10/11',
                '6.3' => 'Windows 8.1',
                '6.2' => 'Windows 8',
                '6.1' => 'Windows 7',
            ];
            $os = $versions[$version] ?? 'Windows';
            $model = 'PC';
            $brand = 'PC';
        } elseif (preg_match('/Mac OS X ([\d_]+)/i', $userAgent, $matches)) {
            $version = str_replace('_', '.', $matches[1]);
            $os = 'Mac OS ' . $version;
            $model = 'Mac';
            $brand = 'Apple';
        } elseif (preg_match('/iPhone/i', $userAgent)) {
            $os = 'iOS';
            $brand = 'Apple';
            // Try to detect iPhone model
            if (preg_match('/iPhone(\d+)[,_](\d+)/i', $userAgent, $matches)) {
                $model = 'iPhone ' . $matches[1];
            } else {
                $model = 'iPhone';
            }
            // Get iOS version
            if (preg_match('/OS ([\d_]+)/i', $userAgent, $matches)) {
                $os .= ' ' . str_replace('_', '.', $matches[1]);
            }
        } elseif (preg_match('/iPad/i', $userAgent)) {
            $os = 'iOS';
            $brand = 'Apple';
            $model = 'iPad';
            if (preg_match('/OS ([\d_]+)/i', $userAgent, $matches)) {
                $os .= ' ' . str_replace('_', '.', $matches[1]);
            }
        } elseif (preg_match('/Android ([\d.]+)/i', $userAgent, $matches)) {
            $os = 'Android ' . $matches[1];

            // Detect Android brand and model
            if (preg_match('/Samsung|SM-/i', $userAgent)) {
                $brand = 'Samsung';
                if (preg_match('/SM-([A-Z0-9]+)/i', $userAgent, $matches)) {
                    $model = 'Galaxy ' . $matches[1];
                } else {
                    $model = 'Samsung Device';
                }
            } elseif (preg_match('/Huawei|HUAWEI/i', $userAgent)) {
                $brand = 'Huawei';
                $model = 'Huawei Device';
            } elseif (preg_match('/Xiaomi|MI|Redmi/i', $userAgent)) {
                $brand = 'Xiaomi';
                if (preg_match('/Redmi/i', $userAgent)) {
                    $model = 'Redmi';
                } else {
                    $model = 'Xiaomi Device';
                }
            } elseif (preg_match('/OPPO/i', $userAgent)) {
                $brand = 'OPPO';
                $model = 'OPPO Device';
            } elseif (preg_match('/vivo/i', $userAgent)) {
                $brand = 'Vivo';
                $model = 'Vivo Device';
            } else {
                $brand = 'Android';
                $model = 'Android Device';
            }
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $os = 'Linux';
            $model = 'PC';
            $brand = 'PC';
        }

        // Detect Browser with version (order matters!)
        if (preg_match('/Edg\/([\d.]+)/i', $userAgent, $matches)) {
            $browser = 'Edge ' . $matches[1];
        } elseif (preg_match('/Chrome\/([\d.]+)/i', $userAgent, $matches) && !preg_match('/Edg/i', $userAgent)) {
            $browser = 'Chrome ' . $matches[1];
        } elseif (preg_match('/Safari\/([\d.]+)/i', $userAgent, $matches) && !preg_match('/Chrome|Edg/i', $userAgent)) {
            if (preg_match('/Version\/([\d.]+)/i', $userAgent, $versionMatches)) {
                $browser = 'Safari ' . $versionMatches[1];
            } else {
                $browser = 'Safari ' . $matches[1];
            }
        } elseif (preg_match('/Firefox\/([\d.]+)/i', $userAgent, $matches)) {
            $browser = 'Firefox ' . $matches[1];
        } elseif (preg_match('/MSIE ([\d.]+)|Trident.*rv:([\d.]+)/i', $userAgent, $matches)) {
            $version = $matches[1] ?? $matches[2] ?? '';
            $browser = 'IE ' . $version;
        } elseif (preg_match('/Opera|OPR\/([\d.]+)/i', $userAgent, $matches)) {
            $version = $matches[1] ?? '';
            $browser = 'Opera ' . $version;
        }

        return [
            'brand' => $brand,
            'model' => $model,
            'os' => $os,
            'browser' => $browser,
        ];
    }
}
