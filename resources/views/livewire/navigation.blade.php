<div>
    <ul class="nav">
        @foreach ($userPermissions as $userPermission)
            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'home' == $userPermission->route_name)
                <li class="nav-item {{ (Route::currentRouteName() == 'home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="link-icon" data-feather="home"></i>
                        <span class="link-title">{{ __('Home') }}</span>
                    </a>
                </li>
            @endif
        @endforeach
        @php
            $showManagementCategory = false;
            $requiredRoutes = ['zip_codes', 'users', 'courses', 'customers', 'students'];
            foreach ($userPermissions as $userPermission) {
                if ($userPermission->user_type_id == intval($user->user_type_id) && in_array($userPermission->route_name, $requiredRoutes)) {
                    $showManagementCategory = true;
                    break;
                }
            }
        @endphp
        @if ($showManagementCategory)
            <li class="nav-item nav-category">{{ __('Management') }}</li>
        @endif
        @foreach ($userPermissions as $userPermission)
            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'users' == $userPermission->route_name)
                <li class="nav-item {{ (str_contains(Route::currentRouteName(), 'user')) ? 'active' : '' }}">
                    <a href="{{ route('users') }}" class="nav-link">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">{{ __('Users') }}</span>
                    </a>
                </li>
            @endif
            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'students' == $userPermission->route_name)
                <li class="nav-item {{ (str_contains(Route::currentRouteName(), 'student')) ? 'active' : '' }}">
                    <a href="{{ route('students') }}" class="nav-link">
                        <i class="link-icon mdi mdi-human-greeting mt-0" style="height: 24px; font-size: 17px;"></i>
                        <span class="link-title">{{ __('Students') }}</span>
                    </a>
                </li>
            @endif
            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'groups' == $userPermission->route_name)
                <li class="nav-item {{ (str_contains(Route::currentRouteName(), 'group')) ? 'active' : '' }}">
                    <a href="{{ route('groups') }}" class="nav-link">
                        <i class="link-icon mdi mdi-clipboard-account mt-0" style="height: 24px; font-size: 17px;"></i>
                        <span class="link-title">{{ __('Groups') }}</span>
                    </a>
                </li>
            @endif
            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'courses' == $userPermission->route_name)
                <li class="nav-item {{ (str_contains(Route::currentRouteName(), 'course')) ? 'active' : '' }}">
                    <a href="{{ route('courses') }}" class="nav-link">
                        <i class="link-icon" data-feather="book-open"></i>
                        <span class="link-title">{{ __('Courses') }}</span>
                    </a>
                </li>
            @endif
            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'zip_codes' == $userPermission->route_name)
                <li class="nav-item {{ (str_contains(Route::currentRouteName(), 'zip_codes')) ? 'active' : '' }}">
                    <a href="{{ route('zip_codes') }}" class="nav-link">
                        <i class="link-icon" data-feather="map-pin"></i>
                        <span class="link-title">{{ __('Zip Codes') }}</span>
                    </a>
                </li>
            @endif
            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'customers' == $userPermission->route_name)
                <li class="nav-item {{ (str_contains(Route::currentRouteName(), 'customer')) ? 'active' : '' }}">
                    <a href="{{ route('customers') }}" class="nav-link">
                        <i class="link-icon mdi mdi-school mt-0" style="height: 24px; font-size: 17px;"></i>
                        <span class="link-title">{{ __('Schools') }}</span>
                    </a>
                </li>
            @endif
        @endforeach
        @php
            $showConfigurationCategory = false;
            $requiredRoutes = ['general_configuration', 'permissions', 'means_interaction', 'concepts'];

            foreach ($userPermissions as $userPermission) {
                if ($userPermission->user_type_id == intval($user->user_type_id) && in_array($userPermission->route_name, $requiredRoutes)) {
                    $showConfigurationCategory = true;
                    break;
                }
            }
        @endphp
        @if ($showConfigurationCategory)
            <li class="nav-item nav-category">{{ __('Configuration') }}</li>
        @endif
        @foreach ($userPermissions as $userPermission)
            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'general_configuration' == $userPermission->route_name)
                <li class="nav-item {{ (str_contains(Route::currentRouteName(), 'general_configuration')) ? 'active' : '' }}">
                    <a href="{{ route('general_configuration') }}" class="nav-link">
                        <i class="link-icon mdi mdi-tune mt-0" style="height: 24px; font-size: 17px;"></i>
                        <span class="link-title">{{ __('System') }}</span>
                    </a>
                </li>
            @endif
            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'permissions' == $userPermission->route_name)
                <li class="nav-item {{ (str_contains(Route::currentRouteName(), 'permissions')) ? 'active' : '' }}">
                    <a href="{{ route('permissions') }}" class="nav-link">
                        <i class="link-icon mdi mdi-folder-lock mt-0" style="height: 24px; font-size: 17px;"></i>
                        <span class="link-title">{{ __('User Permissions') }}</span>
                    </a>
                </li>
            @endif
            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'means_interaction' == $userPermission->route_name)
                <li class="nav-item {{ (str_contains(Route::currentRouteName(), 'means_interaction')) ? 'active' : '' }}">
                    <a href="{{ route('means_interaction') }}" class="nav-link">
                        <i class="link-icon mdi mdi-share-variant mt-0" style="height: 24px; font-size: 17px;"></i>
                        <span class="link-title">{{ __('Means Interaction') }}</span>
                    </a>
                </li>
            @endif
            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'concepts' == $userPermission->route_name)
                <li class="nav-item {{ (str_contains(Route::currentRouteName(), 'concepts')) ? 'active' : '' }}">
                    <a href="{{ route('concepts') }}" class="nav-link">
                        <i class="link-icon mdi mdi-counter mt-0" style="height: 24px; font-size: 17px;"></i>
                        <span class="link-title">{{ __('Payment Concepts') }}</span>
                    </a>
                </li>
            @endif
        @endforeach
        @php
            $showEducationCategory = false;
            $requiredRoutes = ['areas', 'high_school', 'careers', 'universities'];

            foreach ($userPermissions as $userPermission) {
                if ($userPermission->user_type_id == intval($user->user_type_id) && in_array($userPermission->route_name, $requiredRoutes)) {
                    $showEducationCategory = true;
                    break;
                }
            }
        @endphp
        @if ($showEducationCategory)
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#education" role="button" aria-expanded="false"
                   aria-controls="education">
                    <i class="link-icon mdi mdi-bank mt-0" style="height: 24px; font-size: 17px;"></i>
                    <span class="link-title">{{ __('Education') }}</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="education">
                    <ul class="nav sub-menu">
                        @foreach ($userPermissions as $userPermission)
                            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'areas' == $userPermission->route_name)
                                <li class="nav-item">
                                    <a href="{{ route('areas') }}" class="nav-link">
                                        {{ __('Areas') }}
                                    </a>
                                </li>
                            @endif
                            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'high_school' == $userPermission->route_name)
                                <li class="nav-item">
                                    <a href="{{ route('high_school') }}" class="nav-link">
                                        {{ __('High School') }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                        @foreach ($userPermissions as $userPermission)
                            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'careers' == $userPermission->route_name)
                                <li class="nav-item">
                                    <a href="{{ route('careers') }}" class="nav-link">
                                        {{ __('Careers') }}
                                    </a>
                                </li>
                            @endif
                            @if ($userPermission->user_type_id == intval($user->user_type_id) && 'universities' == $userPermission->route_name)
                                <li class="nav-item">
                                    <a href="{{ route('universities') }}" class="nav-link">
                                        {{ __('Universities') }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </li>
        @endif
    </ul>
</div>
