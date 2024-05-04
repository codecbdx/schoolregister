<ul class="navbar-nav">
    <li class="nav-item dropdown nav-profile">
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="{{ $userImage }}" alt="user">
        </a>
        <div class="dropdown-menu" aria-labelledby="profileDropdown">
            <div class="dropdown-header d-flex flex-column align-items-center">
                <div class="figure mb-3">
                    <img src="{{ $userImage }}" alt="user">
                </div>
                <div class="info text-center">
                    <p class="name font-weight-bold mb-0">{{ Str::limit(Auth::user()->name . ' ' . Auth::user()->paternal_lastname . ' ' . Auth::user()->maternal_lastname, 25) }}
                    <p class="email text-muted mb-3">{{ Str::limit(Auth::user()->email, 25)  }}</p>
                </div>
            </div>
            <div class="dropdown-body">
                <ul class="profile-nav p-0 pt-3">
                    <li class="nav-item">
                        <a href="{{ route('edit_profile', ['id' => config('app.debug') ?  Auth::user()->id : encrypt( Auth::user()->id)]) }}"
                           class="nav-link">
                            <i data-feather="edit"></i>
                            <span>{{ __('Edit Profile') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('logout') }}" class="nav-link"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i data-feather="log-out"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </li>
</ul>
