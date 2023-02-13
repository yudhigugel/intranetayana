<nav class="bottom-navbar">
    <div class="container-fluid pl-2 pr-2">
        <ul class="nav page-navigation d-flex justify-content-start">
            @foreach (app("side_menu") as $menu)
            {{-- <li class="nav-item">
            <a class="nav-link" href="index.html">
              <img src="../../images/sidebar/home.svg" alt="" class="nav-icon-title">
              <span class="menu-title">{{ $menu['menu_name'] }}</span>
            </a>
            </li> --}}

            <li class="nav-item mega-menu">
                <a href="#" class="nav-link">
                    <i class="nav-icon-title {{ $menu['icon'] }}"></i>
                    <span class="menu-title">{{ $menu['menu_name'] }}</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="submenu">
                    <div class="col-group-wrapper row">
                        <div class="col-group col-md-5">
                            <div class="row">
                                <div class="col-md-6">
                                <p class="category-heading">{{ $menu['menu_name'] }}</p>
                                    <ul>
                                        @foreach ($menu['child'] as $menu_child)
                                        <li class="nav-item"><a class="nav-link" href="{{$menu_child['route']}}">{{$menu_child['menu_name']}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            @endforeach


        </ul>
    </div>
</nav>