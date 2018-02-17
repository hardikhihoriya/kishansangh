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
            <li class="{{ (Request::is('admin/sanghusers')) ? 'active treeview' : 'treeview' }}">
                <a href="{{url('admin/sanghusers')}}">
                    <i class="fa fa-user"></i>
                    <span>
                        {{trans('adminlabels.USER_MANAGEMENT')}}
                    </span>
                </a>    
            </li>

        </ul>
    </section>
</aside>