@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                        <a href="{!! route('admin.addCardSelect') !!}">Add card</a>
                        <br/>
                        <a href="{!! route('admin.addBoosterSelect') !!}">Add booster</a>
                        <br/>
                        <a href="{!! route('admin.addBoosterBoxSelect') !!}">Add booster box</a>
                        <br/>
                        <a href="{!! route('admin.editCardsSelect') !!}">Edit Cards</a>
                        <br/>
                        <a href="{!! route('admin.importFromCsvGet') !!}">Import from Csv File</a>
                        <br/>
                        <a href="{!! route('admin.deleteByCsvGet') !!}">Delete from by Csv File</a>
                        <br/>
                        <a href="{!! route('admin.importStock') !!}">Import stock</a>
                        <br/>
                        <a href="{!! route('admin.exportStock') !!}">Export stock</a>
                        <br/>
                        <a href="{!! route('admin.deleteByCsvGet') !!}">Delete from by Csv File</a>
                        <br/>
                        <a href="{!! route('admin.getEditionList') !!}">getEditionList</a>
                        <br/>
                        <a href="{!! route('admin.getBuyList') !!}">Get BuyList</a>
                        <br/>
                        <a href="{!! route('admin.connect') !!}">connect to mkm</a>
                        <br/>
                        <a href="{!! route('admin.setEditionIds') !!}">set editions ids from mkm</a>
                        <br/>
                        <a href="{!! route('admin.setProductsIds') !!}">set propducts ids from mkm</a>
                        <br/>
                        <a href="{!! route('admin.addNewProductGet') !!}">add new product</a>
                        <br/>
                        <a href="{!! route('admin.commands') !!}">Commands</a>
                        <br/>
                        <a href="{!! route('admin.completeIdsFromCsvGet') !!}">complete Ids From Csv</a>
                        <br/>

                        <a href="{!! route('admin.blbost') !!}">base_prise modify</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
