@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        <a href="{!! route('admin.addEditionGet') !!}">add edition</a>

                        <table>
                            @foreach($setTypes as $setType)
                                @foreach($editions[$setType] as $set)
                                    <tr>
                                        <td>{{$set->code}}</td>
                                        <td>{{$set->name}}</td>
                                        <td>{{$set->card_count}}</td>
                                        <?php $edition = $editionsLocal[$setType]->where('name', $set->name)->first();?>
                                        <td> {{($edition != null)? $edition->cards_count : ''}}</td>
                                        <td>{{$set->set_type}}</td>
                                        @if($edition==null)
                                            <td style="background-color: green">not</td>
                                        @endif
                                        @if($edition !=null && $edition->cards_count != $set->card_count)
                                            <td style="background-color: red">not complete</td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
