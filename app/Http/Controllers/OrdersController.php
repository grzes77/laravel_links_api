<?php

namespace App\Http\Controllers;

use App\Order;
use App\Order_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$order =  $request->get('order','');

        //return $orders= Order_item::where('order_id', $order)->with('product')->get();
        // ZROBIONE NA ZAJECIACH

         $userId = auth()->user()->id;
         $order = Order::where('user_id', $userId)
            ->where('status', 0)
            ->first();

        if($order){
            return $order->getItem();
        }


        return $order;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //return $request->order;

//        if(!($request->order)){
//            $order = Order::create([
//                'user_id' => 2,
//                'status' => 1
//            ]);
//         $orderId = $order->id;
//
//            $order_item = Order_item::create([
//                'order_id'=> $orderId,
//                'quantity' => 1,
//                'product_id' =>$request->product['id']
//            ]);
//          }
//          else{
//            $orderId = $request->order;
//
//            $order_item = Order_item::create([
//                'order_id'=> $orderId,
//                'quantity' => 1,
//                'product_id' =>$request->product['id']
//            ]);
//
//          }
//
//        return $orderId;

        //###  WERSJA NA ZAJECIACH


        $userId = auth()->user()->id;
        //return $userId;


        $order = Order::where('user_id', $userId)->where('status', 0)->first();

        if(!$order){
            $order = new Order();
            $order->user_id = $userId;
            $order->status = 0;
            $order->save();
        }

        $orderItem = Order_item::where('product_id', $request->product_id)->where('order_id', $order->id)->first();

        if($orderItem){
         $orderItem->update(['quantity' => ++$orderItem->quantity]);

        }else{

        $orderItem = new Order_item();
        $orderItem->product_id = $request->product_id;
        $orderItem->order_id = $order->id;
        $orderItem->quantity = 1;
        $orderItem->save();
        }

        return ['added'=> 1 ];


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $order = Order::where('id', $id)->first();

        //return auth()->user()->id;

        if($order->user_id==auth()->user()->id){
            $order->update(['status'=> 0]);
            return ['status' => '1'];
        }else{
            return response()->json([],401);
        }


    }

    public function changeQuantity(Request $request, $id, $add)
    {

        $userId = auth()->user()->id;
        $orderItem = Order_item::find($id);
        //return $orderItem;

        if(!$orderItem){
            return response()->json([],404);
        }

        $order = $orderItem->order;

        if($order->user_id !== $userId){
            return response()->json([],401);
        }

        if($add > 0){
            $orderItem->update([
                'quantity' => ++$orderItem->quantity]);
        }else{
            if($orderItem->quantity>1){
            $orderItem->update([
                'quantity' => --$orderItem->quantity]);
            }
            else{
                $orderItem->delete();
            }
        }
            return['status'=> 1];

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        DB::table('order_items')->where('id', $id)->delete();
//        $order_item = new Order_item();
//        $order_item->delete($id);
        //return $id;
//        $order_item->delete();
       return ['status' => '1'];
    }
}
