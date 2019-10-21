@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">{{$product->product->name}}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div>
                            <?php
                            \Debugbar::info($product);
                            $p = $product->product;
                            $st = $p->stock->all();

                            $s = null;
                            $i = 0;
                            do{
                            if (isset($st[$i]))
                                $s = $st[$i];
                            $image_path =
                                isset($s->image) && $s->image != null ?
                                    $s->image->path :
                                    ($p->image != null ?
                                        $p->image->path :
                                        "");
                            $quantity =
                                isset($s->quantity) ?
                                    $s->quantity :
                                    0;
                            $price =
                                isset($s->price) ?
                                    $s->price :
                                    $p->price->MT;
                            $foil =
                                $p->foil ?
                                    'foil' :
                                    '';

                            //  var_dump($s);
                            ?>
                            <div style="border: 1px solid; margin: 2px">
                                <table width="100%" style="text-align: center">
                                    <tr>
                                        <td rowspan="3" class="col-md-1">
                                            <img src="{{url('/') .
                                                    "/storage/" .
                                                    $image_path
                                                }}" width="300">

                                        </td>
                                        <td colspan="1" class="col-md-4" style="text-align: left">
                                            {{$p->name . ((isset($p->lang) && ($p->lang != 'en'))? (' - ' . $p->lang ): '')}}
                                        </td>
                                        <td class="col-md-3">
                                            quantity: {{ $quantity}}
                                        </td>
                                        <td class="col-md-3">
                                        {!! Form::open(['route' => 'cart.add', 'id' => 'form' . (isset($s->id)?$s->id: '')]) !!}
                                        <!--<form id="form{{isset($s->id)?$s->id:''}}" method="post" action="{!! route('cart.add')  !!}">-->
                                            <input type="text" name="price" value="{{$price}}" hidden>
                                            <input type="text" name="stock_id"
                                                   value="{{isset($s->id)?$s->id:''}}" hidden>
                                            <ul style="display: inline">
                                                <li style="display: inline">
                                                    <select name="quantity" selectedIndex="0">
                                                        @for($j = 1; $j <= $quantity; $j++)
                                                            <option value="{{$j}}">{{$j}}</option>
                                                        @endfor
                                                    </select>
                                                </li>
                                                <li style="display: inline">
                                                    <?php $str = '$("form' . (isset($s->id) ? $s->id : '') . '").submit();'?>
                                                    <button {{$quantity < 1 ? 'disabled': ''}} onclick="{{$str}}">
                                                        buy
                                                    </button>
                                                </li>
                                            </ul>
                                            <!--</form>-->
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{$product->edition != null?$product->edition->name:''}}</td>
                                        <td>{{$foil}}</td>
                                        <td>
                                            @if(count($prints)>0)
                                                <select>
                                                    @foreach($prints as $print)
                                                        <option value="{{$print->id}}">{{$print->name}}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>{{$product->rarity !=null?$rarities[$product->rarity]:''}}</td>
                                        <td class="col-md-2">
                                            price: {{ $price}}
                                        </td>

                                    </tr>
                                </table>
                            </div>

                            <?php
                            $i++;
                            }while(isset($st[$i]))
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
