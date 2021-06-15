<?php

namespace App\Http\Controllers;

use App\Models\ItemsOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store_order(Request $request){
        $data = [];
        if($request->input('phone_number') === null || empty($request->input('phone_number'))){
            $data['success'] = 0;
            $data['msg'] = 'The phone number is Missing';
        } else if($request->input('location') === null || empty($request->input('location'))){
            $data['success'] = 0;
            $data['msg'] = 'Location should be filled';
        } else if ($request->input('items') === null || empty($request->input('items')) || !is_array($request->input('items'))){
            $data['success'] = 0;
            $data['msg'] = 'There are no items to Order';
        } else {
            $order = new Order;
            $order->user_id = $request->input('user_id') ? $request->input('user_id') : null;
            $order->total_price = $request->input('total_price');
            $order->phone_number = $request->input('phone_number');
            $order->location = $request->input('location');
            if($order->save()){
                $to_save_data = [];
                foreach ($request->input('items') as $value){
                    $to_save_data[] = [
                        'order_id' => $order->id,
                        'item_id' => $value['id'],
                        'quantity' => $value['qty'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                ItemsOrder::insert($to_save_data);
                $data['success'] = 1;
                $data['msg'] = 'order saved successfully';
            } else {
                $data['success'] = 0;
                $data['msg'] = 'Something went wrong';
            }
        }
        return json_encode($data);
    }

    public function get_orders(Request $request){
        $data = [];
        $user_id = $request->input('user_id');
        if($user_id == null){
            $data['success'] = 0;
            $data['msg'] = 'There is no user to show the data';
        } else {
            $orders = DB::table('orders')->where('user_id' , $user_id)->get(['id' , 'total_price' , 'phone_number' , 'location' , 'created_at']);
            //return json_encode(dd($orders));
            if($orders) {
                //get items for each order
                $orders = $orders->map(function ($order) {
                    $order->items = DB::table('items_orders')
                    ->join('items' , 'items.id','=','items_orders.item_id')
                    ->where('items_orders.order_id' , $order->id)->get(['items.title' , 'items.price' , 'items_orders.quantity']);
                    return $order;
                });
                $data['success'] = 1;
                $data['data'] = $orders;
            } else {
                $data['success'] = 0;
                $data['msg'] = 'you do not have previous orders';
            }
        }
        return json_encode($data);
    }
}
