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

                        {{Form::open(['method'=>'POST', 'route'=>'admin.editionsStatistic', 'name'=>'editionsStatistic', 'id'=>'editionsStatistic'])}}
                        {{ Form::select('edition', $editions) }}
                        <input type="submit" value="Go">
                        {{Form::close()}}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
