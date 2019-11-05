@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add edition</div>

                    <div class="card-body">
                        @foreach($stock as $card)
                            @if($card->product->idProductMKM != null)


                            @endif
                        @endforeach
                        <a href="{!! route('admin.MKMAddEditionSelect') !!}">back</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
