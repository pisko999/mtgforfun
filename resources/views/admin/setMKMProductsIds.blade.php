@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add edition</div>

                    <div class="card-body">
                        <select id="edition">
                            @foreach($editions as $edition)
                                <option value="{{$edition->idExpansionMKM}}"
                                        data-id="{{$edition->id}}">{{$edition->name}}</option>
                            @endforeach
                        </select>

                        <button onclick="addEdition()">add</button>
                        <button onclick="addAllEditions()">add all editions</button>
                        <div id="output2"></div>
                        <div id="output"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function addAllEditions() {
            var editions = jQuery.parseJSON('<?php echo addslashes(json_encode($editions)); ?>');
            console.log(editions);
            jQuery.each(editions, function () {
                addEdition($(this)[0]);
                $('#output2').append($(this)[0].name + " added.");

            })
        }

        function addEdition(edition) {

//        function addEdition() {
            $('#output').empty();
            if ($.ajaxSettings.headers)
                delete $.ajaxSettings.headers["X-CSRF-TOKEN"];
            //var url = "https://api.cardmarket.com/ws/v2.0/output.json/expansions/" + $('#edition').val() + "/singles";
            //console.log(url);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (edition != null)
                var d = {'idEdition': edition.idExpansionMKM};
            else
                var d = {'idEdition': $('#edition').val()};
            console.log("h");

            $.ajax({
                type: 'get',
                url: '{{route('admin.getMKMSingles')}}',
                data: d,
                success: function (e) {
                    console.log("h");
                    e = jQuery.parseJSON(e);
                    var mkmSingles = e.single;
                    console.log(mkmSingles);

                    //$('#output').append(e.code);
                    var cards_count = mkmSingles.length;
                    console.log(cards_count);
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

                    $('#output').append(e.expansion.enName + " added.");
                    $('#output').append("<br>");

                    //$('#output').append(e.search_uri);

                    var url = e.search_uri;

                    jQuery.each(mkmSingles, function () {
                        i++;
                        if (edition != null)
                            addCard($(this)[0], i, edition.id, cards_count);
                        else
                            addCard($(this)[0], i, $('#edition').find('option:selected').data('id'), cards_count);

                    });


                    $('#edition').find('option:selected').remove();
                }
            });

            delete $.ajaxSettings.headers["X-CSRF-TOKEN"];

        }

        function addCard(card, i, edition_id, cards_count) {
            var model = {
                'name': card.enName,
                'edition_id': edition_id,
                'idProduct': card.idProduct
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{route('admin.setMKMProductId')}}',
                data: model,
                success: function (e) {
                    $('#output').prepend(e + " added<br>");
                    $('#output').prepend(i + "/" + cards_count + "    ");
                    var tdid = '#td' + i;
                    $(tdid).css("background-color", "green");
                }
            });

            delete $.ajaxSettings.headers["X-CSRF-TOKEN"];

        }

    </script>
@endsection
