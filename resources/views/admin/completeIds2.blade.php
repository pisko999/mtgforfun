@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add edition</div>

                    <div class="card-body">


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
            var files = jQuery.parseJSON('<?php echo addslashes(json_encode($files)); ?>');
            console.log(files);
            i = 0;
            j = files.length;
            while (i < j) {
                addEdition(files, i, j)
            }
/*            jQuery.each(files, function () {
                s = $(this).toArray().join('');
                //console.log(s);
                addEdition(s);
                $('#output2').append(i + "/" + j + " " + s + " added.");
            })
  */      }

        function addEdition(files , i, j) {

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

                var d = {'soubor': files[i]};

            $.ajax({
                type: 'post',
                url: '{{route('admin.completeIdsFromCsvPost2')}}',
                data: d,
                success: function (e) {
                    $('#output').append(file + " added.");
                    i++;
                    addEdition(files, i, j);

                }
            });
        }

    </script>
@endsection
