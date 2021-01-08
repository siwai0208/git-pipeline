@extends('layouts.app')

@section('content')
<div class="container-fluid">
   <div class="">
       <div class="mx-auto" style="max-width:1200px">
           <h1 class="text-center font-weight-bold" style="color:#555555;  font-size:1.2em; padding:24px 0px;">
           {{ Auth::user()->name }}さん、ご注文ありがとうございました</h1>

           <div class="text-center">
               <p> この度はFood Delivery APPでのご購入ありがとうございます。<br>
                    お客様が購入した商品は<br><br>

                    @foreach ($mycarts as $item)
                            ・{{ $item->item->name }}｜{{ number_format($item->item->price) }}バーツ
                            <br>
                            @endforeach

                            <p style="font-size:1.2em; font-weight:bold;"> 合計金額:{{number_format($sum ?? '')}}バーツ</p>

                            となります。<br>

                            <br> 下記の決済画面より決済を完了させてください。<br>

                            <div class="kessai">
                                <button type="submit" class="btn btn-danger btn-lg text-center buy-btn">決済画面<br>未実装!!
                            </div>
                                <h5><a href="{{ url('/') }}">TOPページ</a>にもどる</h5>
                            </p>
            </div>
       </div>
   </div>
</div>
@endsection