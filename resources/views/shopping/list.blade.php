<?php

$states = array(
    "MT" => "Mint",
    "NM" => "Near Mint",
    "EX" => "Excellent",
    "GD" => "Good",
    "LP" => "Lightly played",
    "PL" => "Played",
    "PO" => "Poor"
);
?>

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">

                        @if(isset($showingCards))
                            @include('partial.searchForm',[
                                                'search' => $search,
                                                'selected' => $selected])

                        @endif
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <ul>
                            {!! $links !!}
                            @foreach($products as $item)
                                @include('partial.listItem',[
                                    'item' => $item,
                                    'states' => $states,
                                    'langs' => $lang])

                            @endforeach
                            {!! $links !!}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

