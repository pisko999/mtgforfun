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

                            <div class="card" style="margin:25px 0px;">
                                <div class="card-header"><h2>{{$edition->name}}</h2></div>

                                <div class="card-body">
                                    <?php $total = 0; ?>
                                    <table>

                                        @foreach ($buyList as $card)
                                            <?php
                                            $price = $card->product->base_price * .65;
                                            if ($price > 20)
                                                $price = (floor($price / 10) * 10) - 1;
                                            elseif ($price > 15)
                                                $price = 15;
                                            elseif ($price > 10)
                                                $price = 9;
                                            elseif ($price > 5)
                                                $price = 5;
                                            elseif ($price < 5)
                                                $price = 1;

                                            $total += $price * $card->quantity;

                                            $background = '';

                                            if ($card->rarity == 'M')
                                                $background = 'red';
                                            elseif ($card->rarity == 'R')
                                                $background = 'gold';
                                            elseif ($card->rarity == 'U')
                                                $background = 'grey';
                                            ?>
                                            <tr style=" background: {{$background}}">
                                                <td style="width: 10%; text-align: center;">{{$card->number}}</td>
                                                <td style="width: 75%; text-align: center">{{$card->product->name}}</td>
                                                <td style="width: 1%; text-align: center;">{{$card->quantity}}</td>
                                                <td style="width: 5%; text-align: center;">{{$price}}</td>

                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2"></td>
                                            <td>Total</td>
                                            <td>{{$total}}</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
