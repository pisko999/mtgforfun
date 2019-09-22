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
                                <p style='color: green;'>{!! $message !!}</p>
                            @endforeach
                        @endif
                        {{--@if(isset($messageInStock))
                            @foreach($messageInStock as $message)
                                <p style='color: blue;'>{!! $message !!}</p>
                            @endforeach
                        @endif
                        --}}
                        @if(isset($errorNoAdd))
                            @foreach($errorNoAdd as $message)
                                <p style='color: red;'>{!! $message !!}</p>
                            @endforeach
                        @endif
                        @if(isset($errorNoProduct))
                            @foreach($errorNoProduct as $message)
                                <p style='color: darkgray;'>{!! $message !!}</p>
                            @endforeach
                        @endif
@if(isset($price))
    <p> total price: {{$price}}</p>
    @endif
                        {!! Form::open(['method' => 'Post','route' => 'admin.importFromCsvPost', 'id' => 'form', 'enctype' => 'multipart/form-data']) !!}
                        <input type="file" id="importedFile" name="importedFile"/>
                        <button type="submit"> Import</button>
                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
