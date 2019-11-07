
    @extends(isset($printable) && $printable == true?'layouts.printable':'layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">


                    <div class="card-body">
                        <table width="100%">
                            <tr>
                                <td style="border: black 1px solid">@include('partial.address')</td>
                                <td style="border: black 1px solid">Facture : {{$command->id}}</td>
                            </tr>
                            <tr>
                                <td style="border: black 1px solid">@include('partial.payment',['payment' => $command->payment])</td>
                                <td style="border: black 1px solid">@include('partial.address',['address' => $command->billing_address, 'user' => $command->client])</td>
                            </tr>
                            <tr>
                                <td><br/><br/></td>
                            </tr>
                            <tr style="border: black 1px solid">
                                <td colspan="2">@include('partial.items',['items' => $command->items])</td>
                            </tr>
                            @if(!Auth::guest() && Auth::user()->role >= 4)
                                @if(!isset($printable))
                                    <tr>
                                        <td>
                                            <a href="{!! route('command.changeState',['command_id' => $command->id, 'state_id' => 6]) !!}">
                                                <button>payed</button>
                                            </a>
                                            <a href="{!! route('command.changeState',['command_id' => $command->id, 'state_id' => 9]) !!}">
                                                <button>delivered</button>
                                            </a>
                                            <a href="{!! route('command.changeState',['command_id' => $command->id, 'state_id' => 10]) !!}">
                                                <button>DECK</button>
                                            </a>
                                            <a href="{!! route('command.showPrintable',['command_id' => $command->id]) !!}">
                                                <button>Printable</button>
                                        </td>
                                    </tr>
                                    </a>
                                @endif
                            @endif

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

