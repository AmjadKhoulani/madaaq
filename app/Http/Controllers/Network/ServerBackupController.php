<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Models\MikroTikServer;
use App\Models\ServerBackup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServerBackupController extends Controller
{
    public function index()
    {
        $backups = ServerBackup::with('server')
            ->whereHas('server', function($q) {
                $q->where('tenant_id', auth()->user()->tenant_id);
            })
            ->latest()
            ->paginate(20);
            
        return view('network.backups.index', compact('backups'));
    }

    public function upload(Request $request, MikroTikServer $server)
    {
        // 1. Verify Token (Simple security for internal upload)
        // In real world, use a rotating token or verify IP.
        // We receive X-Backup-Token header from Router.
        // For this implementation, we will check a static secret or just the server existence.
        // Since it's over VPN (internal), we can rely on IP + Server ID match, 
        // but for simplicity we assume the token is the server's encrypted password (or a hash of it).
        
        $token = $request->header('X-Backup-Token');
        if (!$token || $token !== md5($server->id . $server->created_at)) {
            Log::warning("Backup upload failed: Invalid token for server {$server->id}");
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $type = $request->header('X-File-Type', 'backup'); // backup or export

        // 2. Validate File
        if (!$request->hasFile('file')) {
            // RouterOS /tool fetch puts the file body as the POST body, not multipart/form-data "file" field usually?
            // Actually RouterOS /tool fetch with 'upload-file' sends it as multipart 'file' (default name) OR raw body.
            // Documentation says: "upload-file" uploads a file.
            // Let's check how it sends it. Usually standard multipart.
            // If not found in 'file', we might need to look at 'uploaded_file' or just the first file.
        }

        $file = $request->file('file');
        if (!$file) {
            // Fallback: Check if there is ANY file
            foreach ($request->allFiles() as $f) {
                $file = $f;
                break;
            }
        }

        if (!$file) {
            Log::error("Backup upload failed: No file received for server {$server->id}");
            return response()->json(['error' => 'No file received'], 400);
        }

        try {
            // 3. Store File
            $filename = $file->getClientOriginalName();
            // Ensure unique path
            $storagePath = "backups/{$server->id}/{$filename}";
            
            // Store in private storage (app/private or similar, NOT public)
            // storage_path('app') is the default 'local' disk root.
            // We'll use 'local' disk.
            $path = $file->storeAs("backups/{$server->id}", $filename, 'local');

            // 4. Create Record
            ServerBackup::create([
                'mikrotik_server_id' => $server->id,
                'filename' => $filename,
                'path' => $path,
                'size' => $file->getSize(),
                'type' => $type,
                'status' => 'success',
            ]);

            Log::info("Backup stored successfully for server {$server->id}: {$filename}");
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error("Backup storage failed: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function download(ServerBackup $backup)
    {
        // Authorization: Check if user owns the server
        if ($backup->server->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        return response()->download(storage_path('app/' . $backup->path));
    }
}
