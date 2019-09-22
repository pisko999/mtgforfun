/**
 * Created by PhpStorm.
 * User: spina
 * Date: 07/04/2019
 * Time: 23:30
 */
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$card->name}}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <a href="{!! route('admin.addCardSelect') !!}">1</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
