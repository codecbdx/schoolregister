<?php

namespace App\Livewire;

use App\Services\S3Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NavUserProfile extends Component
{
    protected $s3Service;
    public $userImage;

    public function mount(S3Service $s3Service)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->s3Service = $s3Service;
    }

    public function render()
    {
        $user = Auth::user();
        $imagePath = $user->user_image;

        $now = now();

        if (session()->has('user_profile_image_url') && session()->has('url_generation_time')) {
            $urlGenerationTime = session('url_generation_time');
            $minutesSinceLastGeneration = $now->diffInMinutes(Carbon::parse($urlGenerationTime));

            if ($minutesSinceLastGeneration < 100) {
                $this->userImage = session('user_profile_image_url');
                return view('livewire.nav-user-profile');
            }
        }

        $signedUrl = $this->s3Service->getPreSignedUrl($imagePath);
        session([
            'user_profile_image_url' => $signedUrl,
            'url_generation_time' => $now->toDateTimeString()
        ]);

        $this->userImage = session('user_profile_image_url');

        return view('livewire.nav-user-profile');
    }
}
