<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class S3Service
{
    public function getPreSignedUrl($imagePath, $expiryMinutes = 120)
    {
        $expiry = Carbon::now()->addMinutes($expiryMinutes);

        return Storage::disk('s3')->temporaryUrl(
            $imagePath,
            $expiry
        );
    }
}
