@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header"><h1>Buy List</h1></div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @foreach($editions as $edition)
                            {{--<div class="card" style="margin:25px 0px;">
                                <div class="card-header"><h2>{{$edition->name}}</h2></div>

                                <div class="card-body">

                                    @foreach ($edition->cards as $card)
                                        <div class="row">
                                            <div class="col-md-4 col-sm-2 col-xs-2"><p
                                                        style="float:right;">{{$card->number}}</p></div>
                                            <div class="col-md-4 col-sm-8 col-xs-2">{{$card->product->name}}</div>
                                            <div class="col-md-4 col-sm-2 col-xs-2"><p
                                                        style="float:left;">{{$card->quantity}}</p></div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>--}}
                            <div class="card" style="margin:25px 0px;">
                                <div class="card-header"><h2>{{$edition->name}}</h2></div>

                                <div class="card-body">

                                    @foreach ($edition->cards as $card)
                                        <table>
                                        <tr>
                                            <td style="width: 10%; text-align: center;">{{$card->number}}</td>
                                            <td style="width: 80%; text-align: center;">{{$card->product->name}}</td>
                                            <td style="width: 1%; text-align: center;">{{$card->quantity}}</td>

                                        </tr>
                                        </table>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
