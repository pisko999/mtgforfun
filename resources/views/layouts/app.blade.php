<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MtgForFun') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/billing_address.js') }}"></script>
    <script src="{{ asset('js/search.js') }}" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/my.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'MtgForFun') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        <li class="nav-item">
                            @if (Route::has('register'))
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{!! route('cart.show') !!}">cart</a>
                                <a class="dropdown-item" href="{!! route('command.index') !!}">commands</a>
                                <a class="dropdown-item" href="{!! route('user.profileGet') !!}">profile</a>
                                {{--                                <a class="dropdown-item" href="{!! route('want.index') !!}">want</a>--}}
                                @if(Auth::user()->role >=4)
                                    <a class="dropdown-item" href="{!! route('admin.index') !!}">administration</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div align="center" class="row">
            <div class="col-12 col-sm-2">
                <ul class="navbar-nav ">
                    <li class="nav-item">
                        <div>
                            {{Form::open(['method' => 'GET', 'route'=> 'shopping.search', 'name' => 'searchForm', 'id' => 'searchForm'])}}
                            <div >
                                <input type="text" id="searchedText" name="searchedText" autocomplete="off">
                                <script>
                                    $().ready(function () {
                                            $("#quantity").keydown(function (event) {
                                                    if (event.which == 13) {

                                                    }
                                                }
                                            );
                                            $("#searchedText").focus();
                                        }
                                    )
                                </script>
                                <button type="submit">Search</button>
                            </div>
                            <div name="autoUl" id="autoUl"
                                 style="border: 1px solid;z-index: 999;position: absolute; background-color: white">

                            </div>
                            {{Form::close()}}
                        </div>
                    </li>
                    @foreach($navbarItems as $navbarItem)
                        <li class="nav-item">
                            <a href="{!! route('shopping.category', ['category'=>$navbarItem->getCategory()])  !!}">
                                <div>
                                    {{$navbarItem->getText()}}
                                </div>
                            </a>
                        </li>
                    @endforeach
                    <hr/>
                    <li class="nav-item">
                        <a href="{!! route('admin.getBuyList') !!}">Get BuyList</a>
                    </li>
                </ul>

            </div>

            <div class="col-12 col-sm-8">
                @yield('content')
            </div>

            @if(!Auth::guest() && Auth::user()->role >= 4)
                <div class="col-12 col-sm-2">
                    <ul class="navbar-nav ">
                        <li class="nav-item">
                            <a href="{!! route('admin.addCardSelect') !!}">Add card</a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('admin.addBoosterSelect') !!}">Add booster</a>
                        </li>
                        <li class="nav-item">
                        <a href="{!! route('admin.addBoosterBoxSelect') !!}">Add booster box</a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('admin.addNewProductGet') !!}">add new product</a>
                        </li>
                        <hr/>
                        <li class="nav-item">
                            <a href="{!! route('admin.editCardsSelect') !!}">Edit Cards</a>
                        </li>
                        <hr/>
                        <li class="nav-item">
                            <a href="{!! route('admin.importFromCsvGet') !!}">Import from Csv File</a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('admin.deleteByCsvGet') !!}">Delete from by Csv File</a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('admin.importStock') !!}">Import stock</a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('admin.exportStock') !!}">Export stock</a>
                        </li>
                        <hr/>
                        <li class="nav-item">
                            <a href="{!! route('admin.getEditionList') !!}">getEditionList</a>
                        </li>
                        <hr/>
                        <li class="nav-item">
                            <a href="{!! route('admin.EditionCheckGet') !!}">check edition</a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('admin.EditionRemoveGet') !!}">remove edition</a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('admin.editionsStatistic') !!}">editions statistic</a>
                        </li>
                        <hr/>
                        <li class="nav-item">
                            <a href="{!! route('admin.commands') !!}">Commands</a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('admin.MKMAddEditionSelect') !!}">add mkm stock</a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('admin.connect') !!}">connect</a>

                        </li>
                        <li class="nav-item">

                        </li>
                        <li class="nav-item">

                        </li>
                    </ul>
                </div>
            @endif

        </div>
    </main>
</div>
</body>
</html>
