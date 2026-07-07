<?php

namespace App\Jobs;

use App\Models\User;
use ZipArchive;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\UserDataExportRequest;

class PrepareUserDataExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $exportRequestId) {}

    public function handle(): void
    {
        $export = UserDataExportRequest::findOrFail($this->exportRequestId);

        $export->update([
            'status' => 'processing'
        ]);

        try {

            $user = User::with([
                'interests',
                'lists.items',
                'lists.category',
                'profile',
                'consent',
            ])->findOrFail($export->user_id);
            $timestamp = now()->timestamp;
            // Public folder
            $exportDir = public_path("exports/user_{$user->id}_{$timestamp}");

            if (!is_dir($exportDir)) {
                mkdir($exportDir, 0775, true);
            }

            $csvPath = "{$exportDir}/user_data_export.csv";

            $this->generateUserDataCsv($user, $csvPath);

            $export->update([
                'status'       => 'completed',
                'file_path'    => "exports/user_{$user->id}_{$timestamp}/user_data_export.csv",
                'completed_at' => now(),
                'expires_at'   => now()->addDays(7),
            ]);

        } catch (\Throwable $e) {

            $export->update([
                'status' => 'failed',
                'error'  => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function generateUserDataCsv(User $user, string $filePath): void
    {
        $handle = fopen($filePath, 'w');

        if ($handle === false) {
            throw new \RuntimeException("Unable to create CSV file.");
        }

       fputcsv($handle, [
            'full_name',
            'email',
            'phone',
            'city',
            'country',
            'interests',
            'lists',
            // 'created_at',
        ]);
        
        $interests = $user->interests
            ->pluck('name')
            ->implode(', ');
        
        $lists = $user->lists
            ->pluck('title')
            ->implode(', ');
        
        fputcsv($handle, [
            $user->full_name,
            $user->email,
            "'" . $user->phone,
            $user->profile?->city,
            $user->country,
            $interests,
            $lists,
            // $user->created_at?->format('Y-m-d H:i:s'),
        ]);

        fclose($handle);
    }
}