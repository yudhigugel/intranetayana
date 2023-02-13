<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item nav-profile dropdown">
        <div>
          <a class="" href="#" aria-expanded="false">
            <div class="d-flex">
              @if(app('user_login'))
              <img onerror='this.src="/image/default-avatar.png"' src="/upload/profile_photo/{{ app('user_login')->IMAGE_PHOTO }}" alt="profile">
              @else
              <img onerror='this.src="/image/default-avatar.png"' src="/image/default-avatar.png" alt="profile" class="profile-icon mr-2">
              @endif
              <div>
                <span class="sidebar-profile-name font-weight-bold" id="user-fullname">
                  @if(app('user_login'))
                    {{app('user_login')->EMPLOYEE_NAME}}
                  @else
                    Unknown
                  @endif
                </span>
                <p class="sidebar-profile-designation font-weight-bold text-muted text-extra-small" id="user-job-position">
                  @if(app('user_login'))
                    {{app('user_login')->EMPLOYEE_ID}}
                  @else
                    Unknown ID
                  @endif
                    <br/>
                     @if(app('user_login')) {{ session('job_title') }} @else Unknown Position @endif
                </p>
              </div>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
            <a class="dropdown-item" href="#">
              <i class="mdi mdi-settings text-primary"></i>
              Change Password
            </a>
            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.querySelector('#logout-form').submit();">
              <i class="mdi mdi-logout text-primary"></i>
              Logout
            </a>
          </div>
        </div>
      </li>
      @include('partials._menu')
    </ul>
  </nav>
