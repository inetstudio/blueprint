<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">

            <li class="{{ isActiveRoute(['back.pages.*']) }}">
                <a href="#"><i class="fa fa-pencil-alt"></i> <span class="nav-label">Контент </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    @include('admin.module.pages::back.includes.navigation')
                </ul>
            </li>

            <li class="{{ areActiveRoutes(['back.classifiers.*']) }}">
                <a href="#"><i class="fa fa-copy"></i> <span class="nav-label">Справочники </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    @include('admin.module.classifiers::back.includes.navigation')
                </ul>
            </li>

            <li class="{{ areActiveRoutes(['back.feedback.*']) }}">
                <a href="#"><i class="fa fa-user"></i> <span class="nav-label">Данные </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    @include('admin.module.feedback::back.includes.navigation')
                </ul>
            </li>

            @include('admin.module.checks-contest::back.includes.navigation')

            @include('admin.module.acl::back.includes.navigation')
        </ul>
    </div>
</nav>
