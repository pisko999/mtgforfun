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
                        {!! Form::open(['id' => 'form'.$boosterBox->id]) !!}
                        <input type="text" name="id" value="{{$boosterBox->id}}" hidden>

                        <table width="100%">
                            <thead>
                            <td colspan="3">
                                <h3>{{$boosterBox->product->name}}</h3>
                            </td>
                            </thead>
                            <tr>
                                <td colspan="3">

                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">

                                    <img style="margin: 15px" src="{{url('/') .
                                                    "/storage/" .
                                                    ($boosterBox->product->image != null?$boosterBox->product->image->path:"")
                                                }}" width="200px">
                                </td>
                                <td>
                                    Price : {{$boosterBox->product->price != null?$boosterBox->product->price->m:""}}

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Price : <input type="text" name="price" id="price" value="75"></label>

                                </td>
                                <td>
                                    <label>Photo : <input type="file" name="photo"></label>
                                </td>
                                <td>
                                    <label>State :
                                        <select name="state">
                                            <option value="MT">MT</option>
                                            <option value="NM">NM</option>
                                            <option value="EX">EX</option>
                                            <option value="GD">GD</option>
                                            <option value="LP">LP</option>
                                            <option value="PL">PL</option>
                                            <option value="PO">PO</option>
                                        </select>
                                    </label>
                                </td>
                            </tr>
                            <p id="ddd"></p>
                            <tr>
                                <td colspan="3" align="center">
                                    <label>QUANTITY : <input type="text" name="quantity" id="quantity" autofocus></label>
                                    <script>
                                        $().ready(function() {
                                                $("#quantity").keydown(function (event) {
                                                        if (event.which == 13) {
                                                            $('#form{{$boosterBox->id}}').submit();

                                                        }
                                                    }
                                                );
                                                $("#quantity").focus();
                                            }
                                        )
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    @for($i = 1;$i <=20; $i++)

                                        <button type="button" style="width: 50px; margin: 6px;" onclick="$('#quantity').val({{$i}});$('#form{{$boosterBox->id}}').submit();">{{$i}}</button>
                                        @if($i == 10)
                                            <br />
                                        @endif
                                    @endfor
                                </td>
                            </tr>

                        </table>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
