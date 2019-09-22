@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-2"></div>
            <div >
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <?php
                        ?>

                        {!! Form::open(['url' => url($cards->url($cards->currentPage())), 'id' => 'form'.$card->id, 'enctype'=>'multipart/form-data']) !!}

                        <table width="100%">
                            <thead>
                            <tr>
                                <h1>{{$card->product->name}}</h1>
                            </tr>
                            </thead>
                            <tr>

                                <td>
                                    <input type="text" name="id" value="{{$card->id}}" hidden>

                                    <img style="margin: 15px" src="{{url('/') .
                                                    "/storage/" .
                                                    $card->product->image->path
                                                }}" width="200px">
                                </td>
                                <td>
                                    @if($card->product->idProductMKM == null)
                                        <span>!! Not on MKM !!</span> <br/>
                                    @endif
                                    <label>Price : <input type="text" name="price" id="price" value="{{$card->product->price->MT}}"></label><br/>
                                    <label>Photo : <input type="file" name="photo"></label><br/>
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
                                    </label><br/>
{{--                                    <label>Foil ? <input type="checkbox" name="foil" {{$card->foil ?  "checked" : ""}}> </label><br/>--}}
                                    <label>QUANTITY : <input type="text" name="quantity" id="quantity"
                                                             autofocus></label><br/>
                                    <script>
                                        $().ready(function () {
                                                $("#quantity").keydown(function (event) {
                                                        if (event.which == 13) {
                                                            $('#form{{$card->id}}').submit();

                                                        }
                                                    }
                                                );
                                                $("#quantity").focus();
                                            }
                                        )
                                    </script>
                                </td>
                            </tr>
                            <p id="ddd"></p>
                            <tr>
                                <td colspan="3" align="center">

                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    @for($i = 1;$i <=20; $i++)

                                        <button type="submit" style="width: 50px; margin: 6px;"
                                                onclick="{$('#quantity').val({{$i}});$('#form{{$card->id}}').submit();}">{{$i}}</button>
                                        @if($i == 10)
                                            <br/>
                                        @endif
                                    @endfor
                                </td>
                            </tr>

                        </table>
                        {!! Form::close() !!}
                            {!! $links !!}

                            <form method="get">
                                <label>Card number : <input type="text" name="page"></label>
                                <button type="submit">go</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
