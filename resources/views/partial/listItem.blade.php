<?php
//return var_dump(get_class($product));

if (get_class($item) == "App\Models\Product") {
    $product = $item;
    if ($item->card != null)
        $item = $item->card;
    elseif ($item->booster != null)
        $item = $item->booster;
    elseif ($item->boosterBox != null)
        $item = $item->boosterBox;
    elseif ($item->collect != null)
        $item = $item->collect;
    elseif ($item->play != null)
        $item = $item->play;
} else//if ($product instanceof \App\Models\Booster || $product instanceof \App\Models\Card || $product instanceof \App\Models\BoosterBox || $product instanceof \App\Models\Collect || $product instanceof \App\Models\Play)

    $product = $item->product;
//var_dump(($product));

$stock = $product->stock->all();

$stockItem = null;
$i = 0;

do{

if (isset($stock[$i]) && $stock[$i]->quantity == 0)
    $i++;

if (isset($stock[$i]))
    $stockItem = $stock[$i];

$image_path =
    isset($stockItem->image) && $stockItem->image != null ?
        $stockItem->image->path :
        ($product->image != null ?
            $product->image->path :
            "");

$quantity =
    isset($stockItem->quantity) ?
        $stockItem->quantity :
        0;

$price =
    (isset($stockItem->price) && ($stockItem->quantity > 0)) ?
        $stockItem->price :
        $product->price->MT;

$foil =
    isset($item->foil) && $item->foil == 1 ?
        'foil' :
        '';

$state = isset($stockItem->state) ? $states[$stockItem->state] : "";
?>

<div style="border: 1px solid; margin: 2px">
    <table width="100%" style="text-align: center">
        <tr>
            <td rowspan="4" class="col-md-1">
                <img src="{{url('/') .
                                                    "/storage/" .
                                                    $image_path
                                                }}" width="100px">

            </td>
            <td colspan="1" class="col-md-4" style="text-align: left">
                <a href="{!! route('shopping.show', ['itemId'=>$product->id])  !!}">
                    {{$product->name . ((isset($product->lang) && ($product->lang != 'en'))? (' - ' . $product->lang ): '')}}
                </a>
            </td>
            <td class="col-md-3">
                quantity: {{ $quantity}}
            </td>
            <td class="col-md-3">
            {!! Form::open(['route' => 'cart.add', 'id' => 'form' . (isset($stockItem->id)?$stockItem->id: '')]) !!}
            <!--<form id="form{{isset($stockItem->id)?$stockItem->id:''}}" method="post" action="{!! route('cart.add')  !!}">-->
                <input type="text" name="price" value="{{$price}}" hidden>
                <input type="text" name="stock_id"
                       value="{{isset($stockItem->id)?$stockItem->id:''}}" hidden>
                <ul style="display: inline">
                    <li style="display: inline">
                        <select name="quantity" selectedIndex="0">
                            @for($j = 1; $j <= $quantity; $j++)
                                <option value="{{$j}}">{{$j}}</option>
                            @endfor
                        </select>
                    </li>
                    <li style="display: inline">
                        <?php $str = '$("form' . (isset($stockItem->id) ? $stockItem->id : '') . '").submit();'?>
                        <button {{$quantity < 1 ? 'disabled': ''}} onclick="{{$str}}">
                            Add to cart
                        </button>
                    </li>
                </ul>
                <!--</form>-->
                {!! Form::close() !!}
            </td>
        </tr>
        <tr>
            <td>{{$item->edition != null?$item->edition->name:''}}</td>
            <td>{{$foil}} {{$state}}</td>
            <td>
                @if($item instanceof \App\Models\Card)
                    @foreach($item->colors as $color)
                        {{$color->color}}
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td>{{$item->rarity !=null?$rarities[$item->rarity]:''}}</td>
            <td class="col-md-2">
                <b>price: {{ $price}}</b>
            </td>
            <td>
                @if($product->idProductMKM == null)
                    not on MKM
                @endif
            </td>

        </tr>
        @if(!Auth::guest() && Auth::user()->role >= 4)
            <tr>
                <td colspan="3">

                    {{Form::open(['method'=>'POST', 'route'=>'admin.addCardSinglePost', 'name'=>'addCardForm', 'id'=>'addCardForm'])}}
                    <input type="text" name="id" value="{{$product->id}}" hidden>
                    <label for="quantity">Quantity :</label>
                    <input type="text" id="quantity" name="quantity"
                           style="width:100px"
                           required>
                    <label for="state">State :</label>
                    <select name="state" id="state">
                        @foreach($states as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                    <label for="price">Price :</label>
                    <input type="text" name="price" id="price"
                           value="{{$price}}"
                           style="width:100px"
                           required>
                    <input type="submit" value="Add">
                    {{Form::close()}}
                    @if($quantity > 0)
                        {{Form::open(['method'=>'POST', 'route'=>'admin.removeCardSinglePost', 'name'=>'removeCardForm', 'id'=>'removeCardForm'])}}
                        <input type="text" name="id" value="{{$stockItem->id}}" hidden>
                        <label for="quantity">Quantity :</label>
                        <input type="text" id="quantity" name="quantity"
                               style="width:100px"
                               required>
                        <input type="submit" value="Remove">
                        {{Form::close()}}
                    @endif
                </td>
            </tr>
        @endif
    </table>
</div>

<?php
$i++;
}while(isset($stock[$i]))
?>
