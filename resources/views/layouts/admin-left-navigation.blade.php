<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('css/admin/dist/img/avatar5.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><a href="{{ url('/home') }}">{{ucfirst(Auth::user()->first_name)}}</a></p>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ (Request::is('home')) ? 'active treeview' : 'treeview' }}">
                <a href="{{url('home')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>
                        {{trans('adminlabels.DASHBOARD')}}
                    </span>
                </a>    
            </li>

            <li class="{{ (Request::is('admin/sanghusers') || (Request::is('admin/sanghusers/new')) || Request::is('admin/customer') || Request::is('admin/customer/new') || Request::is('admin/vendor') || Request::is('admin/vendor/new')) ? 'active treeview' : 'treeview' }}">
                <a href="{{url('admin/sanghusers')}}" style="word-break: break-word !important;white-space: initial;">
                    <i class="fa fa-diamond"></i>
                    <span>
                        {{trans('adminlabels.USER_MANAGEMENT')}}
                    </span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('admin/sanghusers') || (Request::is('admin/sanghusers/new')) ? 'active' : '' }}">
                        <a href="{{url('admin/sanghusers')}}"><i class="fa fa-circle-o"></i>{{trans('adminlabels.USERS')}}</a>
                    </li>
                    <li class="{{ Request::is('admin/customer') || Request::is('admin/customer') ? 'active' : '' }}">
                        <a href="{{url('admin/customer')}}"><i class="fa fa-circle-o"></i>{{trans('adminlabels.CUSTOMERS')}}</a>
                    </li>
                    <li class="{{ Request::is('admin/vendor') || Request::is('admin/vendor/new') ? 'active' : '' }}">
                        <a href="{{url('admin/vendor')}}"><i class="fa fa-circle-o"></i>{{trans('adminlabels.VENDORS')}}</a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
</aside>