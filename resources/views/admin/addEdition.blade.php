@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add edition</div>

                    <div class="card-body">
                        <select id="edition">

                            @foreach($setTypes as $type)
                                <option disabled>{{$type}}</option>
                                @foreach($editionsToBeAdded[$type] as $edition)
                                    <option value="{{$edition->uri}}">{{$edition->name}}</option>
                                @endforeach
                            @endforeach
                        </select>

                        <button onclick="addEdition()">add</button>
                        <div id="output"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function addEdition() {
            $('#output').empty();
            if ($.ajaxSettings.headers)
                delete $.ajaxSettings.headers["X-CSRF-TOKEN"];

            $.getJSON($('#edition').val(), function (e) {
                //$('#output').append(e.code);
                var cards_count = e.card_count;
                console.log(e.card_count);
                var i = 0;

                var table = $('<table>');
                for (var j = 1; j <= cards_count; j++) {

                    if (j / 10 > 0)
                        table.append('</tr>');
                    if (j % 10 == 1)
                        table.append('<tr>');

                    table.append('<td id="td' + j + '" style="border: 1px solid black">' + j + '</td>');

                }
                $('#output').append(table);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'post',
                    url: '{{route('admin.addEditionPost')}}',
                    data: e,
                    success: function (e) {
                        $('#output').append(e + " added.");
                        $('#output').append("<br>");
                        $('#edition').find('option:selected').remove();

                    }
                });


                delete $.ajaxSettings.headers["X-CSRF-TOKEN"];

                //$('#output').append(e.search_uri);

                var url = e.search_uri;

                getCards(url, i, cards_count);

            });
        }

        function getCards(url, i, cards_count) {

            $.getJSON(url, function (e) {
                //$('#output').append(e);
                var cards = e.data;
                jQuery.each(cards, function () {
                    //console.log($(this)[0]);
                    i++;

                    addCard($(this)[0], i, cards_count);

                });
                if (e.has_more)
                    getCards(e.next_page, i, cards_count);

            });
        }

        function addCard(card, i, cards_count) {
            //console.log(card);
            //var e = {card : card};
//console.log(card);
            if (card.nonfoil)
                $.extend(card, {'isfoil' : 0});
            else
                $.extend(card, {'isfoil': 1});
            again:while (true) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'post',
                    url: '{{route('admin.addCardPost')}}',
                    data: card,
                    success: function (e) {
                        $('#output').prepend(e + " added<br>");
                        $('#output').prepend(i + "/" + cards_count + "    ");
                        var tdid = '#td' + i;
                        $(tdid).css("background-color", "green");
                    }
                });

                delete $.ajaxSettings.headers["X-CSRF-TOKEN"];
                if (card.nonfoil && card.foil && card.isfoil != 1) {
                    card.isfoil = 1;
                    continue again
                }
                break;
            }
        }

    </script>
@endsection
