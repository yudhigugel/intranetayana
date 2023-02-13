<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex align-items-center justify-content-between">
      <a class="navbar-brand brand-logo" href="javascript:void(0)">
        <img src="{{ asset('logo-ayana.png') }}" alt="logo" />
      </a>
      <a class="navbar-brand brand-logo-mini" href="/"><img src="{{ asset('ayana-favicon.png') }}" alt="logo" /></a>
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <img src="{{ asset('template/images/sidebar/menu.svg') }}" alt="" class="">
      </button>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
      <ul class="navbar-nav mr-lg-2">
        <li class="nav-sea rch d-none d-lg-block">
          <div class="input-group pl-0">
            <input type="text" class="form-control ml-0" placeholder="Search Contents.." aria-label="search">
          </div>
        </li>
      </ul>
      <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item dropdown">
          {{-- <a class="nav-link  dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="#" data-toggle="dropdown">
            <img src="{{ asset('template/images/sidebar/mail.svg') }}" alt="" class="">
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
            <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>
            <!-- <a class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <img src="{{ asset('template/images/faces/face4.jpg') }}" alt="image" class="profile-pic">
              </div>
              <div class="preview-item-content flex-grow">
                <h6 class="preview-subject ellipsis font-weight-normal">David Grey
                </h6>
                <p class="font-weight-light small-text text-muted mb-0">
                  The meeting is cancelled
                </p>
              </div>
            </a>
            <a class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <img src="{{ asset('template/images/faces/face2.jpg') }}" alt="image" class="profile-pic">
              </div>
              <div class="preview-item-content flex-grow">
                <h6 class="preview-subject ellipsis font-weight-normal">Tim Cook
                </h6>
                <p class="font-weight-light small-text text-muted mb-0">
                  New product launch
                </p>
              </div>
            </a>
            <a class="dropdown-item preview-item">
              <div class="preview-thumbnail">
                <img src="{{ asset('template/images/faces/face3.jpg') }}" alt="image" class="profile-pic">
              </div>
              <div class="preview-item-content flex-grow">
                <h6 class="preview-subject ellipsis font-weight-normal"> Johnson
                </h6>
                <p class="font-weight-light small-text text-muted mb-0">
                  Upcoming board meeting
                </p>
              </div>
            </a> -->
          </div> --}}
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" onclick="return readNotif();" href="#" data-toggle="dropdown">
            <img src="{{ asset('template/images/sidebar/notification.svg') }}" alt="" class="">
            @if (isset(app('user_notification')['new']) && count(app('user_notification')['new']) > 0 )
                <span class="count-circle"></span>
            @endif

          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
            <div class="notif-container">
                <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                @if (isset(app('user_notification')['total']) && app('user_notification')['total'] > 0)


                    @if(isset(app('user_notification')['new']) && count(app('user_notification')['new']) > 0 )
                        @foreach (app('user_notification')['new'] as $notif)
                            <a class="dropdown-item preview-item bt1 new-notif" href="{{url($notif->NOTIF_LINK)}}" target="_blank">
                                <div class="preview-thumbnail">
                                    @if ($notif->NOTIF_ICON_TYPE=="info")
                                        <div class="preview-icon bg-primary">
                                            <i class="mdi mdi-information mx-0"></i>
                                        </div>
                                    @elseif ($notif->NOTIF_ICON_TYPE=="approve")
                                        <div class="preview-icon bg-success">
                                            <i class="mdi mdi-account-check mx-0"></i>
                                        </div>
                                    @elseif ($notif->NOTIF_ICON_TYPE=="reject")
                                        <div class="preview-icon bg-danger">
                                            <i class="mdi mdi-account-off mx-0"></i>
                                        </div>
                                    @endif

                                </div>
                                <div class="preview-item-content notif">
                                    <h6 class="preview-subject font-weight-normal">{{$notif->NOTIF_DESC}}</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted"> {{ time_elapsed_string($notif->NOTIF_DATE_CREATED)}}</p>
                                </div>
                            </a>
                        @endforeach
                    @endif

                    @if(isset(app('user_notification')['old']) && count(app('user_notification')['old']) > 0 )
                        @foreach (app('user_notification')['old'] as $notif)
                            <a class="dropdown-item preview-item bt1" href="{{url($notif->NOTIF_LINK)}}" target="_blank">
                                <div class="preview-thumbnail">
                                    @if ($notif->NOTIF_ICON_TYPE=="info")
                                        <div class="preview-icon bg-primary">
                                            <i class="mdi mdi-information mx-0"></i>
                                        </div>
                                    @elseif ($notif->NOTIF_ICON_TYPE=="approve")
                                        <div class="preview-icon bg-success">
                                            <i class="mdi mdi-account-check mx-0"></i>
                                        </div>
                                    @elseif ($notif->NOTIF_ICON_TYPE=="reject")
                                        <div class="preview-icon bg-danger">
                                            <i class="mdi mdi-account-off mx-0"></i>
                                        </div>
                                    @endif

                                </div>
                                <div class="preview-item-content notif">
                                    <h6 class="preview-subject font-weight-normal">{{$notif->NOTIF_DESC}}</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted"> {{ time_elapsed_string($notif->NOTIF_DATE_CREATED)}}</p>
                                </div>
                            </a>
                        @endforeach
                    @endif



                @else
                    <a class="dropdown-item preview-item">
                        <div class="preview-item-content notif">
                            <p class="font-weight-light small-text mb-0 text-muted"> Oops! No notifications for you yet.</p>
                        </div>
                    </a>

                @endif
            </div>



          </div>
        </li>
        <li class="nav-item nav-profile dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown-navbar">
            @if(app('user_login'))
            <img onerror='this.src="/image/default-avatar.png"' src="/upload/profile_photo/{{ app('user_login')->IMAGE_PHOTO }}" alt="profile" />
            @else
            <img onerror='this.src="/image/default-avatar.png"' src="/image/default-avatar.png" alt="profile" class="profile-icon mr-2">
            @endif
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown flat-dropdown" aria-labelledby="profileDropdown-navbar">
            <div class="flat-dropdown-header">
              <div class="d-flex">
                @if(app('user_login'))
                <img onerror='this.src="/image/default-avatar.png"' src="/upload/profile_photo/{{ app('user_login')->IMAGE_PHOTO }}" alt="profile" class="profile-icon mr-2">
                @else
                <img onerror='this.src="/image/default-avatar.png"' src="/image/default-avatar.png" alt="profile" class="profile-icon mr-2">
                @endif
                <div>
                  <span class="profile-name font-weight-bold">@if(app('user_login')) {{ app('user_login')->EMPLOYEE_NAME }} @else Unknown @endif</span>
                  <p class="profile-designation"> @if(app('user_login')) {{ app('user_login')->EMPLOYEE_ID }} @else Unknown @endif</p>
                  <p class="profile-designation"> @if(app('user_login')) {{ session('job_title') }} @else Unknown @endif</p>
                </div>
              </div>
            </div>
            <div class="profile-dropdown-body">
              <ul class="list-profile-items">
                <li class="profile-item">
                  <a href="{{ url('user/change-profile') }}" class="profile-dropdown-link">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-account-outline text-primary"></i>
                        <div>
                          <h5 class="item-title">Profile</h5>
                          <p class="item-detail">Change profile</p>
                        </div>
                      </div>
                  </a>
                </li>
                <li class="profile-item">
                  <a href="{{ url('user/change-password') }}" class="profile-dropdown-link">
                    <div class="d-flex align-items-center">
                      <i class="mdi mdi-account-key text-dark"></i>
                      <div>
                        <h5 class="item-title">Account</h5>
                        <p class="item-detail">Change Password</p>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="profile-item">
                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                  </form>
                  <a id="btnLogout" href="#" class="profile-dropdown-link" onclick="event.preventDefault(); document.querySelector('#logout-form').submit();">
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
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <img src="{{ asset('template/images/sidebar/menu.svg') }}" alt="" class="">
      </button>
    </div>
  </nav>
