@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add edition {{$edition->name}}</div>

                    <div class="card-body">
                        <table>
                            <tr>
                                <?php $foil = "n"; $ids['n'] = array(); ?>
                                @foreach($allCards as $cards)
                                    <td>
                                        <table>
                                            <?php $col = 1; ?>
                                            @foreach($cards as $card)
                                                @if($col == 1)
                                                    <tr>
                                                        @endif
                                                        <?php
                                                        array_push($ids[$foil], $card->id);
                                                        $bg = 'white';
                                                        if ($card->product->idProductMKM != null)
                                                            $bg = "blue";
                                                        foreach ($card->product->stock as $s)
                                                            if ($s->idArticleMKM != null)
                                                                $bg = "yellow";
                                                        ?>
                                                        <td id="{{'td' . $card->id}}"
                                                            style="border: 1px solid black; background-color: {{$bg}}">{{$card->id}}</td>
                                                        <?php $col++; ?>
                                                        @if($col > 10)
                                                    </tr>
                                                    <?php $col = 1; ?>
                                                @endif
                                            @endforeach
                                        </table>
                                    </td>
                                    <?php if ($foil == "n") {
                                        $foil = "f";
                                        $ids['f'] = array();
                                    }?>
                                @endforeach
                            </tr>
                        </table>
                        @if($edition->idExpansionMKM != null)
                            <a href="{{route('admin.MKMCheckProductIds',['id' => $edition->id])}}">
                                <button>check product IDs</button>
                            </a>
                            <button onclick="checkArticles()">Check cards on MKM</button>
                        @endif
                        <a href="{!! route('admin.MKMAddEditionSelect') !!}">
                            <button>back</button>
                        </a>

                        <script>
                            var nonfoils = {{addslashes(json_encode($ids['n']))}};
                            var foils = {{json_encode($ids['f'])}};

                            function checkArticles() {

                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });

                                jQuery.each(nonfoils, function (index, value) {
                                    checkId(value);
                                });
                                jQuery.each(foils, function (index, value) {
                                    checkId(value);
                                });

                            }

                            function checkId(id) {

                                var d = {'id': id};

                                $.ajax({
                                    type: 'get',
                                    url: '{{route('admin.checkCardOnMKMApi')}}',
                                    data: d,
                                    success: function (e) {

                                        var tdid = '#td' + id;
                                        var color;
                                        if (e == 1)
                                            color = "green";
                                        else if (e == -1)
                                            color = "red";
                                        else if (e == -2)
                                            color = "violet";
                                        $(tdid).css("background-color", color);
                                    }
                                });
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
