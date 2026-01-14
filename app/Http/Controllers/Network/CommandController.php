<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\MikroTikServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use RouterOS\Client;
use RouterOS\Query;

class CommandController extends Controller
{
    public function index()
    {
        $servers = MikroTikServer::all();
        return view('network.commands.index', compact('servers'));
    }

    public function execute(Request $request)
    {
        $request->validate([
            'server_id' => 'required|exists:mikrotik_servers,id',
            'command' => 'required|string',
        ]);

        $server = MikroTikServer::findOrFail($request->server_id);
        $commandStr = $request->command;

        try {
            // Decrypt password
            $password = Crypt::decryptString($server->password_encrypted);
            
            // Configure Client
            $client = new Client([
                'host' => $server->ip,
                'user' => $server->username,
                'pass' => $password,
                'port' => (int) ($server->api_port ?? 8728), // Default API port
                'timeout' => 5,
            ]);

            // Execute Command
            // Commands in RouterOS API are array based, passing string directly usually works 
            // but for safety/correctness with this library:
            // The library supports ->query('/cmd')->read()
            
            // Note: RouterOS API commands usually start with /, e.g. /system/identity/print
            // If user typed "ping 8.8.8.8", we might need to handle it.
            // But often users know specific syntax.
            // Let's assume standard API syntax or attempt to wrap it.
            
            // However, 'ping' is a special ongoing command. In API it needs specific handling or limited count.
            // For simple "print" commands, it's fine.
            
            // Simple approach: Use query()
            $query = new Query($commandStr);
            $response = $client->query($query)->read();
            
            // Execute command
            $query = new Query($commandStr);
            $response = $client->query($query)->read();
            
            // Log the raw response for debugging
            \Log::info('Terminal Command Response', [
                'command' => $commandStr,
                'response_count' => count($response),
                'response' => $response
            ]);
            
            // Format output
            $output = $this->formatResponse($response);

        } catch (\Exception $e) {
            \Log::error('Terminal Command Failed', [
                'command' => $commandStr,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Connection Failed: ' . $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
            'server' => $server->name,
            'command' => $commandStr,
            'output' => $output ?: 'No output returned',
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    private function formatResponse($response)
    {
        if (empty($response)) {
            return "✓ Command executed successfully (No output returned)";
        }

        // If it's a simple message array
        if (isset($response['after'])) {
            unset($response['after']); // Cleanup metadata
        }

        $output = "";
        $count = count($response);
        
        foreach ($response as $index => $item) {
            $output .= "╔═══ Item " . ($index + 1) . " / {$count} ═══╗\n";
            
            if (is_array($item)) {
                foreach ($item as $key => $value) {
                    // Format boolean values
                    if ($value === 'true' || $value === true) $value = 'yes';
                    if ($value === 'false' || $value === false) $value = 'no';
                    
                    // Format the output line
                    $output .= sprintf("║ %-20s: %s\n", $key, $value);
                }
            } else {
                $output .= "║ " . $item . "\n";
            }
            
            $output .= "╚" . str_repeat("═", 50) . "╝\n\n";
        }

        return $output;
    }
}
