@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{$edition_name}}</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        {!! $links !!}
                        {!! Form::open(['url' => url($cards->url($cards->currentPage())), 'id' => 'form','method' => 'post']) !!}

                        <table width="100%">
                            <thead>
                            <td>
                                Number
                            </td>
                            <td>
                                Name
                            </td>
                            <td>
                                <input type="checkbox" name="chbPrice" id="chbPrice">Price
                            </td>
                            <td>
                                <input type="checkbox" name="chbState" id="chbState">State
                            </td>
                            <td>
                                <input type="checkbox" name="chbQuantity" id="chbQuantity">Quantity
                            </td>
                            </thead>

                            <tr>
                                <td colspan="5">
                                    <button type="submit">Save</button>
                                </td>
                            </tr>
                            <?php $i = 0;?>
                            @foreach($cards as $card)

                                <?php
                                $stock = null;
                                $stocks = null;
                                $j = 0;
                                if ($card->product != null && count($card->product->stock) != 0)
                                    $stocks = $card->product->stock;
                                do{
                                $stock = isset($stocks[$j]) ? $stocks[$j] : null;

                                $background = '';

                                if ($card->rarity == 'M')
                                    $background = 'red';
                                elseif ($card->rarity == 'R')
                                    $background = 'gold';
                                elseif ($card->rarity == 'U')
                                    $background = 'lightgrey';
                                $price = $stock != null && $stock->quantity > 0 ? $stock->price : 4;// $card->product->price->MT;
                                //$price = $price < 4 ? 4 : $price;
                                //$price = 4;
                                ?>
                                <tr>
                                    <input type="text" name="stock{{$i}}" id="stock{{$i}}"
                                           value="{{$stock != null ? $stock->id : ''}}" hidden>
                                    <input type="text" name="id{{$i}}" id="id{{$i}}" value="{{$card->id}}" hidden>
                                    <input type="text" name="origQuantity{{$i}}" id="origQuantity{{$i}}"
                                           value="{{$stock != null?$stock->quantity:0}}" hidden>
                                    <input type="text" name="origState{{$i}}" id="origState{{$i}}"
                                           value="{{$stock != null && $stock->quantity > 0 ? $stock->state:'MT'}}"
                                           hidden>
                                    <input type="text" name="origPrice{{$i}}" id="origPrice{{$i}}" value="{{$price}}"
                                           hidden>
                                    <td>
                                        {{$card->number}}
                                    </td>
                                    <td>
                                        <label style="background: {{$background}}">{{$card->product->name}}</label>
                                    </td>
                                    <td>
                                        <input type="text" name="price{{$i}}" id="price{{$i}}"
                                               value="{{$price}}">
                                    </td>
                                    <td>
                                        <select name="state{{$i}}" id="state{{$i}}">
                                            <option value="MT">MT</option>
                                            <option value="NM">NM</option>
                                            <option value="EX">EX</option>
                                            <option value="GD">GD</option>
                                            <option value="LP">LP</option>
                                            <option value="PL">PL</option>
                                            <option value="PO">PO</option>
                                        </select>
                                        @if(isset($stock))
                                            <script>
                                                $().ready(function () {
                                                    $("#state{{$i}}").val('{{$stock->quantity != 0 ? $stock->state: 'MT'}}');
                                                });
                                            </script>
                                        @endif                                    </td>
                                    <td>
                                        <input type="text" name="quantity{{$i}}" id="quantity{{$i}}"
                                               value="{{$stock != null?$stock->quantity:0}}" autofocus>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                                $j++;
                                }while(isset($stocks[$j]));
                                ?>
                            @endforeach

                            <tr>
                                <td colspan="5">
                                    <button type="submit">Save</button>
                                </td>
                            </tr>

                        </table>
                        {!! Form::close() !!}

                        <script>
                            var i = {{$i}};

                            $(document).ready(function()
                            {
                                $('#chbPrice').change(function()
                                {
                                    for(var j = 0; j <= i; j++) {
                                        if (this.checked) {
                                            $('#price' + j).prop( "disabled", true );

                                        } else
                                            $('#price' + j).prop( "disabled", false );
                                    }
                                });
                                $('#chbState').change(function()
                                {
                                    for(var j = 0; j <= i; j++) {
                                        if (this.checked) {
                                            $('#state' + j).prop( "disabled", true );

                                        } else
                                            $('#state' + j).prop( "disabled", false );
                                    }
                                });
                                $('#chbQuantity').change(function()
                                {
                                    for(var j = 0; j <= i; j++) {
                                        if (this.checked) {
                                            $('#quantity' + j).prop( "disabled", true );

                                        } else
                                            $('#quantity' + j).prop( "disabled", false );
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
