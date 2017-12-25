<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('css/admin/dist/img/avatar5.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><a href="{{ url('/home') }}">{{ucfirst(Auth::user()->name)}}</a></p>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header"><center>=================================</center></li>            
        </ul>
    </section>
</aside>