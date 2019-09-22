<?php
//sortedEditionsTypes
//editions
//route
//
?>
@foreach($sortedEditionsTypes as $type)
    <div class="card">
        <div class="card-header">{{$type}}</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <ul class="row">
                @foreach($editions[$type] as $edition)
                    <div class="col-md-4">

                        <a href="{!! route($route, ['edition_id'=>$edition->getId()])  !!}">
                            <div>
                                {{$edition->name}}
                            </div>
                        </a>
                    </div>

                @endforeach
            </ul>
        </div>
    </div>
@endforeach
