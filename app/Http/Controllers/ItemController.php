<?php

namespace App\Http\Controllers;

use App\Item;
use App\Offer;
use App\Quantity;
use Illuminate\Http\Request;

use App\Http\Requests;

class ItemController extends Controller
{
    public function index()
    {
        if(auth()->user())
        {
                $items=Item::all();


                return view('product')->with('items',$items);

        }
        else
            return view('welcome');
    }
    public function offers()
    {
        if(auth()->user())
        {
                $offers=Offer::orderby('validity','desc')->get();


                return view('offers')->with('offers',$offers);

        }
        else
            return view('welcome');
    }
    public function addOffer(Request $request){
        $item_tag=$request['Item'];
        $quantity=intval($request['quantity']);
        $price=floatval($request['price']);
        $offer=new Offer();
        $item=Item::where('tag',$item_tag)->first();
        $item_id=$item->id;
        $offer->item_id=$item_id;
        $offer->quantity=$quantity;
        $offer->validity=$request['validity'];
        $offer->price=$price;
        $offer->save();
        return redirect('/offers');
    }
    public function insertItem(Request $request)
    {
        if(!auth()->user()){
            return redirect('/');
        }
        $file = $request['image'];
        $name=$file->getClientOriginalName();
        $item=Item::create([
            'name' => $request['item_name'],
            'tag' => $request['item_tag'],
            'price' => $request['item_price'],
            'image'=>$name,
        ]);
//        $tag=$request['item_tag'];
//        $name=$request['item_name'];
        $file->move(public_path().'/images/', $name);
        $quantity=new Quantity();
        $quantity->item_quantity=$request['quantity'];
        $item->quantity()->save($quantity);
        //$item->quantity()->save($quantity);
        return back();
    }
    public function updateQuantity($name,$item_quantity)
    {
        if(!auth()->user()){
            return redirect('/');
        }

        $item=Item::where('name',$name)->first();
        dd($item);
        $quantity=new Quantity();
        $quantity->item_quantity=$item_quantity;
        $item->quantity()->save($quantity);
        return back();
    }
    public function deleteItem(Item $item)
    {
        if(!auth()->user()){
            return redirect('/');
        }

        $item->delete();
        return back();
    }
    public function deleteOffer(Offer $offer)
    {
        if(!auth()->user()){
            return redirect('/');
        }

        $offer->delete();
        return back();
    }
}
