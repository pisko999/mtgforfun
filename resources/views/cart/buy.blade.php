@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Choice what you want</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <p>{{$command->created_at}}</p>
                        <table width="100%">
                            <thead>
                            <tr>
                                <th>Product name</th>
                                <th>Price p.u.</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                            </thead>

                            <?php $price = 0; ?>

                            @foreach($command->items as $item)
                                <tr>
                                    <td width="50%">{{$item->stock->product->name}}</td>
                                    <td>{{$item->price}}</td>
                                    <td>{{$item->quantity}}</td>
                                    <td>{{$item->price*$item->quantity}}</td>
                                    <td>

                                    </td>
                                </tr>
                        @endforeach
                                <tr>
                                    <td colspan="2"></td>
                                    <td>Total price:</td>
                                    <td>{{$command->amount()}}</td>

                                </tr>
                        </table>
                    </div>
                    {!! Form::open(['route' => 'cart.confirm']) !!}
                    Delivery address:
                    <ul>
                        <input type="radio" name="address" value="0" required="required" checked>
                        In shop
                    @foreach($user->addresses as $address)
                        <li>
                            <input type="radio" name="address" value="{{$address->id}}" required="required">
                            {{$address->street . ' ' . $address->number . ', ' . $address->city}}
                        </li>
                    @endforeach
                    </ul>
                    <div>
                        <label >
                            <input type="checkbox" name="billing_address_chb" id="billing_address_chb" onchange="billing_address_changed()">
                            different billing address
                        </label>
                        <ul id="billing_address_ul" style="display: none;" >
                            @foreach($user->addresses as $address)
                                <li>
                                    <input type="radio" name="billing_address" id="billing_address" value="{{$address->id}}" >
                                    {{$address->street . ' ' . $address->number . ', ' . $address->city}}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    payment:
                    <ul>
                        <li><input type="radio" name="payment" value="cash" required="required" checked> Cash</li>
                        <li><input type="radio" name="payment" value="transfer" required="required"> Transfer</li>
{{--                        <li><input type="radio" name="payment" value="crypto" required="required"> Crypto</li>--}}
                    </ul>
                    <button type="submit">buy</button>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
