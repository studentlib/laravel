@extends("blades.index")

@section("backstage_left")
    <div class="backstage_left" align="center">
        <div class="left_main">
            <ul>
                @foreach(Session::all()['left_menu'] as $k=>$menu)
                    <li><b><a href="{{$menu['redirect_url']}}">{{$menu['menu_name'] or $menu['menu_li']}}</a></b></li>
                @endforeach
            </ul>
        </div>
        <a class="left_botton" href=""></a>
    </div>
@show