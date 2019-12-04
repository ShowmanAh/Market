<?php

namespace App\Http\Controllers\Client;

use App\Category;
use App\Client;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(){

    }
    public function create(Client $client){
        //relation between categories one to many each category has many products
        // get categories with have products
        $categories = Category::with('products')->get();
        //$orders = $client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders.create', compact('client', 'categories', 'orders'));

    }
    public function store(Request $request,Client $client){
        $request->validate([
            'products' => 'required|array',

        ]);
           $this->attach_order($request,$client);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.orders.index');

    }
    public function edit(Client $client, Order $order){
        $categories = Category::with('products')->get();
        //$orders = $client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders.edit', compact('categories','client','order','orders'));

    }
    public function update(Request $request,Client $client,Order $order)
    {
        $request->validate([
            'products' => 'required|array',

        ]);
        $this->detach_order($order);
        //dd('done');
        $this->attach_order($request,$client);
        session()->flash('success', __('site.edited_successfully'));
        return redirect()->route('dashboard.orders.index');

          //dd($request->all());
    }
    private function attach_order($request,$client){
        //order add by client
        $order = $client->orders()->create([]);
        $order->products()->attach($request->products);
        $total_price = 0;
        //$i looping array 3 => 0,1,2,3
        foreach ($request->products as $id=>$quantity){

            //find id and get it
            $product = Product::FindOrFail($id);
            $total_price += $product->sale_price * $quantity['quantity'];
            $product->update([
                'stock' => $product->stock - $quantity['quantity']

            ]);

        }//end foreach
        $order->update([
            'total_price' => $total_price

        ]);
    }
    //delete old order and put new order
    private function detach_order($order){
        foreach ($order->products as $product){
            $product->update([
                'stock' => $product->stock + $product->pivot->quantity

            ]);


        }
        $order->delete();
    }
    public function destroy(Client $client,Order $order){

    }
}
