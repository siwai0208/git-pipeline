<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    protected $fillable = [
        'user_id','item_id'
    ];

    public function showCart()
    {
        $user_id = Auth::id();
        $data['mycarts']= $this->where('user_id', $user_id)->get();
        $data['count']=0;
        $data['sum']=0;

        foreach($data['mycarts'] as $mycart){
            $data['count']++;
            $data['sum'] += $mycart->item->price;
        }
        return $data;
    }

    public function item()
    {
        return $this->belongsTo('\App\Models\Item');
    }

    public function addCart($item_id)
    {
        $user_id = Auth::id(); 

        $cart_add_info=Cart::Create(['item_id' => $item_id,'user_id' => $user_id]);
 
        $message = 'カートに追加しました';

        return $message;
    }

    public function deleteCart($item_id)
    {
       $user_id = Auth::id(); 
       $delete = $this->where('user_id', $user_id)->where('item_id',$item_id)->delete();
       
       if($delete > 0){
           $message = 'カートから一つの商品を削除しました';
       }else{
           $message = '削除に失敗しました';
       }
       return $message;
    }

    public function checkoutCart()
    {
        $user_id = Auth::id(); 
        $checkout['mycarts']=$this->where('user_id', $user_id)->get();
        $checkout['sum']=0;
        $this->where('user_id', $user_id)->delete();

        foreach($checkout['mycarts'] as $mycart){
            $checkout['sum'] += $mycart->item->price;
        }
        
        return $checkout;     
    }
}