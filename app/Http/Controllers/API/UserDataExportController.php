<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\PrepareUserDataExportJob;
use App\Models\UserDataExportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDataExportController extends Controller
{
    // public function request(Request $request)
    // {
    //     try {
    //         $user = Auth::user();

    //         // Prevent multiple active requests
    //         $existing = UserDataExportRequest::where('user_id', $user->id)
    //             ->whereIn('status', ['pending', 'processing'])
    //             ->first();

    //         if ($existing) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'A data export request is already in progress.'
    //             ], 409);
    //         }

    //         $export = UserDataExportRequest::create([
    //             'user_id'      => $user->id,
    //             'status'       => 'pending',
    //             'requested_at' => now(),
    //         ]);

    //         // Run job synchronously
    //         PrepareUserDataExportJob::dispatchSync($export->id);

    //         // Refresh model after job completion
    //         $export->refresh();

    //         if ($export->file_path) {

    //             // --- Storage file (internal backup) ---
    //             $storageFile = storage_path('app/public/' . str_replace('storage/', '', $export->file_path));

    //             // --- Public file (for browser access) ---
    //             $publicFile = public_path('storage/' . str_replace('storage/', '', $export->file_path));

    //             // Ensure public directory exists
    //             if (!file_exists(dirname($publicFile))) {
    //                 mkdir(dirname($publicFile), 0755, true);
    //             }

    //             // Copy file to public if not exists
    //             if (file_exists($storageFile)) {
    //                 copy($storageFile, $publicFile);
    //             }
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Your data export request has been completed.',
    //             'data'    => [
    //                 'status'    => $export->status,
    //                 // 'file_path' => $export->file_path, // storage/app/public relative path
    //                 'file_path'  => $export->file_path ? url($export->file_path) : null, // public accessible
    //             ]
    //         ]);
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to process export request',
    //             'error'   => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function request(Request $request)
    {
        try {
            $user = Auth::user();
    
            // Prevent multiple active requests
            $existing = UserDataExportRequest::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'processing'])
                ->first();
    
    
            $export = UserDataExportRequest::create([
                'user_id'      => $user->id,
                'status'       => 'pending',
                'requested_at' => now(),
            ]);
    
            // Run synchronously
            PrepareUserDataExportJob::dispatchSync($export->id);
    
            $export->refresh();
    
            return response()->json([
                'success' => true,
                'message' => 'Your data export request has been completed.',
                'data' => [
                    'status'    => $export->status,
                    'file_path' => $export->file_path
                        ? url($export->file_path)
                        : null,
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process export request',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function getExports()
    {
        try {
            $user = Auth::user();

            $exports = UserDataExportRequest::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($export) {
                    return [
                        'id'            => $export->id,
                        'status'        => $export->status,
                        'requested_at'  => $export->requested_at,
                        'completed_at'  => $export->completed_at,
                        // 'file_path'     => $export->file_path, // stored path
                        'file_path'      => $export->file_path
                            ? url($export->file_path)
                            : null, // full openable URL
                    ];
                });

            return response()->json([
                'success' => true,
                'data'    => $exports,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch export data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
