@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @include('partial.editionsList',[
                    'sortedEditionsTypes' => $sortedEditionsTypes,
                    'editions' => $editions,
                    'route' => 'shopping.singles'])

            </div>
        </div>
    </div>
@endsection
