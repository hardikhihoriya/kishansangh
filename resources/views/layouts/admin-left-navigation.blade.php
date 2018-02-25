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

            <li class="{{ (strpos(Route::current()->uri(),'sanghusers') !== false || strpos(Route::current()->uri(),'customer') !== false || strpos(Route::current()->uri(),'vendor') !== false) ? 'active treeview' : 'treeview' }}">
                <a href="{{url('admin/sanghusers')}}" style="word-break: break-word !important;white-space: initial;">
                    <i class="fa fa-diamond"></i>
                    <span>
                        {{trans('adminlabels.USER_MANAGEMENT')}}
                    </span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ strpos(Route::current()->uri(),'sanghusers') !== false ? 'active' : '' }}">
                        <a href="{{url('admin/sanghusers')}}"><i class="fa fa-circle-o"></i>{{trans('adminlabels.USERS')}}</a>
                    </li>
                    <li class="{{ strpos(Route::current()->uri(),'customer') !== false ? 'active' : '' }}">
                        <a href="{{url('admin/customer')}}"><i class="fa fa-circle-o"></i>{{trans('adminlabels.CUSTOMERS')}}</a>
                    </li>
                    <li class="{{ strpos(Route::current()->uri(),'vendor') !== false ? 'active' : '' }}">
                        <a href="{{url('admin/vendor')}}"><i class="fa fa-circle-o"></i>{{trans('adminlabels.VENDORS')}}</a>
                    </li>
                </ul>
            </li>
            <li class="{{ (strpos(Route::current()->uri(),'shop-marketing') !== false || strpos(Route::current()->uri(),'shop-type') !== false || strpos(Route::current()->uri(),'shop-package') !== false || strpos(Route::current()->uri(),'shops') !== false) ? 'active treeview' : 'treeview' }}">
                <a href="{{url('admin/shop-type')}}" style="word-break: break-word !important;white-space: initial;">
                    <i class="fa fa-diamond"></i>
                    <span>
                        {{trans('adminlabels.SHOP_MANAGEMENT')}}
                    </span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ strpos(Route::current()->uri(),'shop-type') !== false ? 'active' : '' }}">
                        <a href="{{url('admin/shop-type')}}"><i class="fa fa-circle-o"></i>{{trans('adminlabels.SHOP_TYPE')}}</a>
                    </li>
                </ul>
                <ul class="treeview-menu">
                    <li class="{{ strpos(Route::current()->uri(),'shop-marketing') !== false ? 'active' : '' }}">
                        <a href="{{url('admin/shop-marketing')}}"><i class="fa fa-circle-o"></i>{{trans('adminlabels.SHOP_MARKETING')}}</a>
                    </li>
                </ul>
                <ul class="treeview-menu">
                    <li class="{{ strpos(Route::current()->uri(),'shop-package') !== false ? 'active' : '' }}">
                        <a href="{{url('admin/shop-package')}}"><i class="fa fa-circle-o"></i>{{trans('adminlabels.SHOP_PACKAGE')}}</a>
                    </li>
                </ul>
                <ul class="treeview-menu">
                    <li class="{{ strpos(Route::current()->uri(),'shops') !== false ? 'active' : '' }}">
                        <a href="{{url('admin/shops')}}"><i class="fa fa-circle-o"></i>{{trans('adminlabels.SHOP')}}</a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
</aside>