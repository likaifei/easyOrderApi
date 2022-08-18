<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function client(){
        return $this->hasOne(OrderClient::class, 'orderId');
    }
    public function goods(){
        return $this->hasMany(OrderGoods::class, 'orderId');
    }
    public function saveClient($client){
        $orderClient = OrderClient::where('uid', $this->uid)->where('orderId', $this->id)->first();
        if(!$orderClient){
            $orderClient = new OrderClient();
            $orderClient->orderId = $this->id;
            $orderClient->uid = $this->uid;
        }
        $keys = ['clientName', 'clientAddr', 'clientPhone', 'note', 'id'];
        foreach($keys as $k){
            $orderClient->$k = $client[$k];
        }
        $orderClient->save();
    }

    public function saveGoods($client, $goods){
        $clientId = $client['id'];
        OrderGoods::where('uid', $this->uid)->where('orderId', $this->id)->delete();
        $list = [];
        $keys = ['name', 'group', 'price', 'note', 'number', 'id', 'money'];
        $priceBook = PriceBook::where('uid', $this->uid)->where('clientId', $clientId)->pluck('price', 'id');
        foreach($goods as $v){
            $item = [];
            $item['orderId'] = $this->id;
            $item['uid'] = $this->uid;
            foreach($keys as $k){
                $item[$k] = $v[$k];
            }
            $list[] = $item;
            if(isset($priceBook[$v['id']])){
                if($priceBook[$v['id']] != $v['price']){
                    PriceBook::where('uid', $this->uid)
                        ->where('id', $v['id'])
                        ->where('clientId', $clientId)
                        ->update(
                            ['price' => $v['price']]
                        );
                }
            }else{
                PriceBook::insert([
                    'uid' => $this->uid,
                    'clientId' => $clientId,
                    'id' => $v['id'],
                    'price' => $v['price']
                ]);
            }
        }
        OrderGoods::insert($list);
    }
}
