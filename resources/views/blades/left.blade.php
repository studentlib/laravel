@extends("blades.index")
@section("backstage_left")
    <div class="backstage_left">
        <div class="left_main">
            <ul >
                <li style="color: #855D95;border-bottom: 1px solid #D8E3E9;width: 132px;height:30px;padding-left: 12px;font-size: 16px; line-height:25px;"><b>{{Session::get("topmenu_name")}}</b></li>
                @foreach(Session::all()['left_menu'] as $k=>$menu)
                    <li><a href="{{$menu->redirect_url}}">{{$menu->menu_name or $menu->menu_li}}</a></li>
                @endforeach
            </ul>
        </div>
        <a id="openclose" class="openclose"  href="javascript:;"></a>
    </div>
@endsection