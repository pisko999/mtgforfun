@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if(isset($messages))
                            @foreach($messages as $message)
                                <p style='color: green;'>{!! $message->name !!} Deleted</p>
                            @endforeach
                        @endif
                        @if(isset($messageInStock) && (count($messageInStock) !=0))
                            {!! Form::open(['method' => 'Post','route' => 'admin.deleteByCsv2Post', 'id' => 'form']) !!}
                            <hr/>
                            <h2>cards with differents in stock</h2>
                            @foreach($messageInStock as $message)
                                <hr/>
                                <label>{{$message->name}} Pcs:{{ $message->quantity}} F:{{$message->foil}}
                                    St:{{$message->condition}} Kc:{{$message->purchasePrice}}</label>
                                <br/>
                                @foreach($message->product->stock as $stock)
                                    @if($stock->foil != $message->foil)
                                            @continue
                                    @endif
                                    <label><input type="radio" name="f{{$stock->product->id}}" value="{{$stock->id}}"
                                                  required />{{$message->name}}
                                        Pcs:{{ $stock->quantity}} F:{{$stock->foil}}
                                        St:{{$stock->state}} Kc:{{$stock->price}}</label>
                                    <br/>
                                @endforeach
                                {{--                                <p style='color: blue;'>{!! $message->name !!}</p>--}}
                            @endforeach
                            <button type="submit">Save</button>
                            {!! Form::close() !!}

                        @endif
                        @if(isset($errorNotRemoved))
                            <hr/>
                            @foreach($errorNotRemoved as $message)
                                <p style='color: red;'>{!! $message->name !!}</p>
                            @endforeach
                        @endif
                        @if(isset($errorNoProduct))
                            @foreach($errorNoProduct as $message)
                                <p style='color: darkgray;'>{!! $message->name !!}</p>
                            @endforeach
                        @endif

                        {!! Form::open(['method' => 'Post','route' => 'admin.deleteByCsvPost', 'id' => 'form', 'enctype' => 'multipart/form-data']) !!}
                        <input type="file" id="importedFile" name="importedFile"/>
                        <button type="submit"> Import</button>
                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
