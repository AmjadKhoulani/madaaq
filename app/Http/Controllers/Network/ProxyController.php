<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Router;
use App\Models\MikroTikServer;
use Illuminate\Support\Facades\Http;

class ProxyController extends Controller
{
    public function webfig(Request $request, Router $router, $path = null)
    {
        // 1. Verify Access & MikroTik
        if ($router->deviceModel && stripos($router->deviceModel->manufacturer, 'MikroTik') === false) {
             abort(404, 'Device is not a MikroTik router.');
        }

        return $this->doProxy($request, $router->ip, $router->id, null, $path);
    }

    /**
     * Remote proxy via a MikroTik server (using SOCKS)
     */
    public function remoteWebfig(Request $request, MikroTikServer $server, $ip, $path = null)
    {
        // 1. Ensure SOCKS is enabled and firewall is open (optional, handled in service)
        try {
            $service = new \App\Services\MikroTikService($server);
            $service->enableSocks();

            // 1.5 Auto-Bypass Hotspot for this device
            // This is critical if the target is behind a Hotspot
            $mac = $service->getMacFromIp($ip);
            if ($mac) {
                // Log attempt
                \Log::info("Auto-Bypassing Hotspot for Remote Access: IP=$ip MAC=$mac");
                $service->addHotspotBypass($mac, "Auto-Bypass for Remote Access ($ip)");
            }
        } catch (\Exception $e) {
            \Log::error("SOCKS/Bypass Enable Error: " . $e->getMessage());
        }

        $socksProxy = "socks5h://{$server->ip}:1080";
        
        return $this->doProxy($request, $ip, "remote-{$server->id}-" . str_replace('.', '-', $ip), $socksProxy, $path);
    }

    protected function doProxy(Request $request, $targetIp, $contextId, $proxy = null, $path = null)
    {
        // Build Target URL
        $path = $path ? ltrim($path, '/') : '';
        $targetUrl = "http://{$targetIp}/{$path}";
        
        \Log::channel('single')->info("[PROXY START] Target: {$targetIp} | Path: {$path} | Proxy: " . ($proxy ?? 'NONE'));

        // Append query string if exists
        $queryString = $request->getQueryString();
        if ($queryString) {
            $targetUrl .= '?' . $queryString;
        }

        try {
            // 3. Forward Request
            $method = $request->method();
            $headers = $request->header();
            
            // Fix headers for proxy
            // IMPORTANT: MikroTik checks Referer and Origin for security (CSRF protection)
            $headers['Host'] = $targetIp;
            $headers['Origin'] = "http://{$targetIp}";
            $headers['Referer'] = "http://{$targetIp}/";
            
            unset($headers['content-length']); // Let Guzzle handle this
            
            // Create the Http client with options
            $clientOptions = [
                'allow_redirects' => false,
                'http_errors' => false,
                'timeout' => 15,
            ];

            if ($proxy) {
                $clientOptions['proxy'] = $proxy;
            }

            // Create the Http client
            $client = Http::withoutVerifying()
                ->withOptions($clientOptions)
                ->withHeaders($headers);

            // Send Request
            if (in_array(strtoupper($method), ['GET', 'HEAD', 'OPTIONS'])) {
                $response = $client->send($method, $targetUrl);
            } else {
                $response = $client->send($method, $targetUrl, [
                    'body' => $request->getContent()
                ]);
            }

            // 4. Process Response
            $content = $response->body();
            $statusCode = $response->status();
            $contentType = $response->header('Content-Type');

            // 5. Rewrite Content
            // We need to handle HTML, JS, JSON and CSS
            $isHtml = str_contains(strtolower($contentType), 'text/html');
            $isJs = str_contains(strtolower($contentType), 'javascript') || str_contains(strtolower($contentType), 'application/x-javascript');
            $isJson = str_contains(strtolower($contentType), 'json');
            $isCss = str_contains(strtolower($contentType), 'text/css');

            if ($isHtml || $isJs || $isJson || $isCss) {
                // Determine proxy base route
                if (is_numeric($contextId)) {
                    $proxyBase = route('routers.webfig', ['router' => $contextId]);
                } else {
                    // For remote access, extract server id and ip from contextId "remote-{serverId}-{ip}"
                    preg_match('/remote-(\d+)-(.*)/', $contextId, $matches);
                    $proxyBase = route('network.discovery.webfig', [
                        'server' => $matches[1], 
                        'ip' => str_replace('-', '.', $matches[2])
                    ]);
                }
                
                // Remove trailing slash
                $proxyBase = rtrim($proxyBase, '/');

                // 5a. Specific Fix for MikroTik Webfig redirect and Meta Refresh
                // It often uses explicit strings like "/webfig" or "webfig/"
                $content = str_replace('"/webfig"', '"' . $proxyBase . '/webfig"', $content);
                $content = str_replace("'/webfig'", "'" . $proxyBase . "/webfig'", $content);
                
                // Fix for assignment to root '/'
                $content = str_replace('window.location="/"', 'window.location="' . $proxyBase . '/"', $content);
                $content = str_replace("window.location='/'", "window.location='" . $proxyBase . "/'", $content);

                // Meta Refresh Rewriting
                // <meta http-equiv="refresh" content="0; url=/">
                $patternMeta = '/(<meta[^>]+http-equiv=["\']refresh["\'][^>]+content=["\']\d+;\s*url=)(\/)([^"\']*["\'])/i';
                $content = preg_replace_callback($patternMeta, function($matches) use ($proxyBase) {
                     return $matches[1] . $proxyBase . '/' . $matches[3];
                }, $content);
                
                // Fix for possible assignment to root or webfig
                $content = preg_replace('/(window\.|document\.)?location(\.href)?\s*=\s*(["\'])\/webfig\/?(["\'])/', '$1location$2=$3' . $proxyBase . '/webfig/$4', $content);
                
                // 5b. HTML Attribute Rewriting
                if ($isHtml) {
                    // Inject <base> tag to force all relative links to go through proxy
                    $baseTag = '<base href="' . $proxyBase . '/">';
                    if (str_contains($content, '<head>')) {
                        $content = str_replace('<head>', '<head>' . $baseTag, $content);
                    } else {
                        $content = $baseTag . $content;
                    }

                    $pattern = '/(src|href|action|background)\s*=\s*(["\'])\/([^"\']*)\2/i';
                    $content = preg_replace_callback($pattern, function($matches) use ($proxyBase) {
                        return $matches[1] . '=' . $matches[2] . $proxyBase . '/' . $matches[3] . $matches[2];
                    }, $content);
                }
                
                // 5c. JSON Redirect Rewriting
                if ($isJson) {
                     $content = preg_replace_callback('/"(\/[^"]+)"/', function($matches) use ($proxyBase) {
                        $path = $matches[1];
                        if ($path === '/webfig' || $path === '/' || str_starts_with($path, '/webfig/')) {
                             if ($path === '/webfig' || $path === '/webfig/') {
                                 return '"' . $proxyBase . '/webfig/"';
                             }
                             if ($path === '/') return '"' . $proxyBase . '/webfig/"';
                             
                             return '"' . $proxyBase . $path . '"';
                        }
                        return $matches[0];
                    }, $content);
                }

                // 5d. CSS URL Rewriting
                if ($isCss) {
                    $content = preg_replace_callback('/url\((["\']?)\/([^)]+)\1\)/i', function($matches) use ($proxyBase) {
                        return 'url(' . $matches[1] . $proxyBase . '/' . $matches[2] . $matches[1] . ')';
                    }, $content);
                }
            }

            // 6. Build Laravel Response
            $laravelResponse = response($content, $statusCode);

            // Set context in Cache linked to User ID
            try {
                $userId = auth()->id();
                $cacheKey = 'webfig_context_' . ($userId ?? 'guest');
                
                if ($userId) {
                    \Illuminate\Support\Facades\Cache::put($cacheKey, $contextId, now()->addHours(4));
                }
            } catch (\Exception $e) {
                \Log::error("Webfig Proxy Cache Error: " . $e->getMessage());
            }

            // 7. Forward Response Headers
            foreach ($response->headers() as $name => $values) {
                // Skip headers that should be regenerated by our server
                if (in_array(strtolower($name), ['transfer-encoding', 'content-encoding', 'content-length', 'connection', 'host'])) {
                    continue;
                }

                foreach ($values as $value) {
                    // Rewrite Location header for redirects
                    if (strtolower($name) === 'location') {
                        if (str_starts_with($value, '/')) {
                            $value = rtrim($proxyBase, '/') . $value;
                        }
                    }
                    
                    // Rewrite Set-Cookie Path
                    if (strtolower($name) === 'set-cookie') {
                         $value = preg_replace('/path=\/[^;]*/i', 'path=/', $value);
                         // Optional: Add SameSite=None and Secure if needed for iframe (but usually accessed directly)
                    }
                    
                    $laravelResponse->header($name, $value);
                }
            }

            return $laravelResponse;

        } catch (\Exception $e) {
            \Log::error("Proxy Fatal Error: " . $e->getMessage());
            return response("Proxy Error: Could not connect to device at {$targetIp}. Details: " . $e->getMessage(), 502);
        }
    }

    /**
     * Handle requests that escaped the proxy (e.g. /webfig/...)
     * and redirect them back to the correct router proxy url.
     */
    public function handleTrap(Request $request, $path = null)
    {
        $userId = auth()->id();
        $sessionId = session()->getId();
        
        file_put_contents(storage_path('logs/proxy_debug.txt'), date("Y-m-d H:i:s") . " [TRAP HIT] Path: $path | User: " . ($userId ?? 'NULL') . " | Session: $sessionId\n", FILE_APPEND);

        // Use Cache + Auth ID for 100% reliability keying to the user
        $routerId = null;
        if ($userId) {
            $routerId = \Illuminate\Support\Facades\Cache::get('webfig_context_' . $userId);
            file_put_contents(storage_path('logs/proxy_debug.txt'), " [TRAP CACHE] Key: webfig_context_$userId | Result: " . ($routerId ?? 'MISS') . "\n", FILE_APPEND);
            
            // Allow fallback to session if cache misses (legacy/guest?)
            if (!$routerId) {
                $routerId = session('current_router_context');
            }
        } else {
             $routerId = session('current_router_context');
             file_put_contents(storage_path('logs/proxy_debug.txt'), " [TRAP NO AUTH] Fallback to Session. Result: " . ($routerId ?? 'MISS') . "\n", FILE_APPEND);
        }

        if (!$routerId) {
            file_put_contents(storage_path('logs/proxy_debug.txt'), " [TRAP FAILED] Redirecting to index.\n", FILE_APPEND);
            return redirect()->route('routers.index')->with('error', 'فقدنا الاتصال بجلسة الجهاز. يرجى المحاولة مرة أخرى.');
        }

        if (is_numeric($routerId)) {
            return redirect()->route('routers.webfig', ['router' => $routerId, 'path' => $path]);
        } else {
             preg_match('/remote-(\d+)-(.*)/', $routerId, $matches);
             return redirect()->route('network.discovery.webfig', [
                'server' => $matches[1], 
                'ip' => str_replace('-', '.', $matches[2]),
                'path' => $path
            ]);
        }
    }
    /**
     * Handle Mobile App Webfig Token
     * Authenticates the user and redirects to the appropriate Webfig proxy.
     */
    public function handleMobileToken($token)
    {
        $data = \Illuminate\Support\Facades\Cache::get('webfig_token_' . $token);
        
        if (!$data) {
            return response('❌ Invalid or expired token. Please try again from the app.', 403);
        }
        
        // Log in the user for this session (WebView)
        \Illuminate\Support\Facades\Auth::loginUsingId($data['user_id']);
        
        $server = MikroTikServer::find($data['server_id']);
        
        if (!$server) {
            return response('Server not found.', 404);
        }

        // Redirect to the remote webfig proxy
        // This will now work because we have an active session
        // We use the server's wireguard IP for stability if available, else standard IP
        $targetIp = $server->wireguard_ip ?? $server->ip;
        
        // Sanitize IP for route parameter (replace dots with dashes if needed by route regex, 
        // but network.discovery.webfig usually accepts dots if regex permits, checking route definition...)
        // Route definition: Route::any('/network/discovery/webfig/{server}/{ip}/{path?}', ...)
        // There is no regex constraint on {ip} in the definition I saw earlier, except implicit segment.
        // But usually dots are fine in segments unless at the end.
        
        return redirect()->route('network.discovery.webfig', [
            'server' => $server->id,
            'ip' => $targetIp,
        ]);
    }
}
