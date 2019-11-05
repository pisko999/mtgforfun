@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add edition</div>

                    <div class="card-body">
                        <table>
                            @foreach($editions as $key => $val)
                                @if(is_array($val))
                                    <tr>
                                        <td>
                                            <h2>{{$key}}</h2>
                                        </td>
                                    </tr>
                                    @foreach($val as $key => $value)
                                        <tr>
                                            <td>
                                                <a href="{!! route('admin.MKMAddEdition', ['id' => $key])  !!}">
                                                    <div>
                                                        {{$value}}
                                                    </div>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
