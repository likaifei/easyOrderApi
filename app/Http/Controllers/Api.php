<?php

namespace App\Http\Controllers;

use App\Client;
use App\Goods;
use App\Order;
use App\PriceBook;
use App\PrintModel;
use Illuminate\Http\Request;

class Api extends Controller
{
    public function setClient(){
        $uid = request('uid');
        $id = request('id');
        $keys = ['clientName', 'clientAddr', 'clientPhone', 'note'];
        if($id){
            $client = Client::where('id', $id)->where('uid', $uid)->first();
            if(!$client) return $this->error('无效的客户');
        }else{
            $client = new Client();
            $client->uid = $uid;
        }
        foreach($keys as $k){
            $client->$k = request($k, '');
        }
        $client->save();
        return $this->success('', '保存成功');
    }
    public function getClients(){
        return $this->success(Client::where('uid', request('uid'))->get());
    }

    public function setGoods(){
        $uid = request('uid');
        $id = request('id');
        $keys = ['name', 'group', 'price', 'note'];
        if($id){
            $goods = Goods::where('id', $id)->where('uid', $uid)->first();
            if(!$goods) return $this->error('无效的商品');
        }else{
            $goods = new Goods();
            $goods->uid = $uid;
        }
        foreach($keys as $k){
            $goods->$k = request($k, '');
        }
        $goods->save();
        return $this->success('', '保存成功');
    }
    public function getGoods(){
        return $this->success(Goods::where('uid', request('uid'))->get());
    }

    public function setOrderStatus(){
        $uid = request('uid');
        $id = request('id');
        $status = request('status', '');
        $order = Order::where('id', $id)->where('uid', $uid)->first();
        if(!$order)
            return $this->error('无效的订单');
        $order->status = $status;
        $order->save();
        return $this->success('', '保存成功');
    }

    public function setOrder(){
        $uid = request('uid');
        $id = request('id');
        $client = request('client');
        $goods = request('goods');
        $status = request('status', '');
        $orderNumber = request('orderNumber', '');
        if($id){
            # update
            $order = Order::where('id', $id)->where('uid', $uid)->first();
            if(!$order)
                return $this->error('无效的订单');
            $order->orderNumber = $orderNumber;
            $order->status = $status;
        }else{
            # insert
            $order = new Order();
            $order->uid = $uid;
            $order->orderNumber = $orderNumber;
            $order->status = '未发货';
        }
        $order->save();
        $order->saveClient($client);
        $order->saveGoods($client, $goods);
        return $this->success('', '保存成功');
    }

    public function getPriceBook(){
        $clientId = request('id');
        $uid = request('uid');
        $result = PriceBook::where('uid', $uid)->where('clientId', $clientId)->pluck('price', 'id');
        return $this->success($result);
    }

    public function getOrders(){
        $uid = request('uid');
        $search = request('search');
        $result = Order::where('uid', $uid)->with(['client', 'goods'])
        ->when($search, function($query, $search){
            return $query->where('orderNumber', 'like', "%{$search}%");
        })
        ->orderByDesc('status')
        ->orderByDesc('id')
        ->paginate(20);
        return $this->success($result);
    }

    public function getModel(){
        $model = request('model', '');
        if(!$model) return $this->error('无效的模板码');
        $result = PrintModel::where('model', $model)->first();
        if(!$result) return $this->error('无效的模板码');
        return $this->success($result->fn);
    }

}
