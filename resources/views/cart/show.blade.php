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
                                    <td>{{$item->stock->product->name}}</td>
                                    <td>{{$item->price}}</td>
                                    <td>{{$item->quantity}}</td>
                                    <td>{{$item->price * $item->quantity}}</td>
                                    <td>
                                        {!! Form::open(['route' => 'cart.remove', 'id' => 'form' . (isset($item->id)?$item->id: '')]) !!}
                                        <input name="id" value="{{$item->id}}" hidden>
                                        <select name="quantity" selectedIndex="0">
                                            @for($i = 1; $i <= $item->quantity; $i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>
                                        <button type="submit">remove</button>

                                        {!! Form::close() !!}

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
                    {!! Form::open(['route' => 'cart.buy']) !!}
                    <button type="submit" {{count($command->items) == 0?'disabled':''}}>buy</button>
                    {!! Form::close() !!}
                    {{--
                    {!! Form::open(['route' => 'want.want']) !!}
                    <button type="submit">want</button>
                    {!! Form::close() !!}
                    --}}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
