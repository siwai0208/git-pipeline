@component('mail::message')

{{ $user }}様<br>
この度はFood Delivery APPでのご購入ありがとうございます。<br>

お客様が購入した商品は<br>

@foreach ($checkout as $item)
・{{ $item->item->name }}｜{{ $item->item->price }}バーツ
<br>
@endforeach

となります。<br>

下記の決済画面より決済を完了させてください。<br>

@component('mail::button', ['url' => ''])
決済画面へ
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent