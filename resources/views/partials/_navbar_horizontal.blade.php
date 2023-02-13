<nav class="navbar top-navbar col-lg-12 col-12 p-0">
    <div class="container-fluid">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="/">
            <img src="https://primamedix.net/wp-content/uploads/2020/06/logo-color.png" alt="logo"/>
        </a>
        <a class="navbar-brand brand-logo-mini" href="/">
            <img src="{{ asset('logoBiznet.png') }}" alt="logo"/>
            {{-- Intranet PMN --}}
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search..." aria-label="search">
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex justify-content-center align-items-center font-weight-medium" id="languageDropdown" href="#" data-toggle="dropdown">
              English
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="languageDropdown">
              <a class="dropdown-item preview-item">
              English
              </a>
              <a class="dropdown-item preview-item">
                Arabic
                </a>
            </div>
          </li>
         
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
                <img src="{{ asset('template/images/sidebar/notification.svg') }}" alt="" class="nav-icon-title">
              <span class="count-circle"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
            </div>
          </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown-navbar">
              <img src="{{app('user_login')->url_photo}}" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown flat-dropdown" aria-labelledby="profileDropdown-navbar">
              <div class="flat-dropdown-header">
                <div class="d-flex">
                    <img src="{{app('user_login')->url_photo}}" alt="profile" class="profile-icon mr-2">
                    <div>
                      <span class="profile-name font-weight-bold">{{app('user_login')->EMPNAME}}</span>
                      <p class="profile-designation">{{app('user_login')->JOBPOSITION_DESC}}</p>
                    </div>
                </div>
              </div>
              <div class="profile-dropdown-body">
                <ul class="list-profile-items">
                  <li class="profile-item">
                    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                      </form>
                      <a href="#" class="profile-dropdown-link" onclick="event.preventDefault(); document.querySelector('#logout-form').submit();">
                        <div class="d-flex align-items-center">
                          <i class="mdi mdi-power text-dark"></i>
                          <div>
                            <h5 class="item-title mt-0">Logout</h5>
                          </div>
                        </div>
                      </a>
                    </li>
                </ul>
              </div>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
          <img src="../../images/sidebar/menu.svg" alt="" class="">
        </button>
      </div>
    </div>
  </nav>

  @include('partials._navbar_horizontal_menu')