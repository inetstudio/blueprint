<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">

            <li class="{{ isActiveRoute(['back.pages.*']) }}">
                <a href="#"><i class="fa fa-pencil-alt"></i> <span class="nav-label">Контент </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    @include('admin.module.pages::back.includes.navigation')
                </ul>
            </li>

            <li class="{{ isActiveRoute(['back.feedback.*']) }}">
                <a href="#"><i class="fa fa-user"></i> <span class="nav-label">Данные </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    @include('admin.module.feedback::back.includes.navigation')
                </ul>
            </li>

            <li class="{{ isActiveRoute(['back.social-contest.*']) }}">
                <a href="#"><i class="fa fa-hashtag"></i> <span class="nav-label">UGC</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    @include('admin.module.social-contest.posts::back.includes.package_navigation')
                    @include('admin.module.social-contest.prizes::back.includes.package_navigation')
                    @include('admin.module.social-contest.statuses::back.includes.package_navigation')
                </ul>
            </li>

            <li class="{{ isActiveRoute(['back.classifiers.*']) }} btn-nowrap">
                <a href="#"><i class="fa fa-copy"></i> <span class="nav-label">Справочники </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li class="{{ isActiveRoute('back.classifiers.*') }} btn-nowrap">
                        <a href="#"><i class="fa fa-list-alt"></i> Классификаторы<span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level collapse">
                            <li class="{{ isActiveRoute('back.classifiers.groups.*') }} btn-nowrap">
                                <a href="{{ route('back.classifiers.groups.index') }}">Группы</a>
                            </li>
                            <li class="{{ isActiveRoute('back.classifiers.entries.*') }} btn-nowrap">
                                <a href="{{ route('back.classifiers.entries.index') }}">Значения</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            @include('admin.module.acl::back.includes.navigation')
        </ul>
    </div>
</nav>
