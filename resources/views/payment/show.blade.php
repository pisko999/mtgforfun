@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header">{{$payment->id}}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div>
                            <table style="text-align: right">
                                <tr><td><p>Command ID :</p></td><td><p><a href="{!! route('command.show', ['command_id' => $payment->command->id])!!}"> {{$payment->command->id}}</a></p></td></tr>
                                <tr><td><p>Created at :</p></td><td><p>{{$payment->created_at}}</p></td></tr>
                                <tr><td><p>Type of payment :</p></td><td><p>{{$payment->type}}</p></td></tr>
                                <tr><td><p>Payment address :</p></td><td><p>{{$payment->address}}</p></td></tr>
                                <tr><td><p>Amount :</p></td><td><p>{{$payment->amount}}</p></td></tr>
                                <tr><td><p>Currency :</td></td><td><p>{{$payment->currency}}</p></td></tr>
                                <tr><td><p>Status :</p></td><td><p>{{$payment->status}}</p></td></tr>
                                <tr><td><p>Status changed :</p></td><td><p>{{$payment->updated_at}}</p></td></tr>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
