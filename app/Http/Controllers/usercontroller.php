<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class usercontroller extends Controller
{
    //
    public function store(Request $request)
{
    //validation of inputs
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email|max:255,string',
        'phone' => 'required|string',
        'address' => 'required|string',
        'password' => 'required|min:5|max:40',
        'confirm_password' => 'required|min:5|max:40|same:password',
    ]);
    
    if($validator->fails()){
        return redirect()->back()->withErrors($validator)->withInput();
    } 

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->address = $request->address;
    $user->password = Hash::make($request->password);
    $save = $user->save();
    if($save){
        return redirect()->route('login')->with('message', 'Registration successful');

    }else {
        return redirect()->back()->with('error', 'Registration failed');
    }
}

//logout
public function user_logout()
{
    Auth::guard('web')->logout();
    return redirect('/')->with('message', 'you have successful logout');
}

public function product_details($id)
{
    $data = Product::findorFail($id);
    $getCat = $data->productCategory;
    $similar = product::where('productCategory', $getCat)
    ->where('id', '!=', $getCat)
    ->latest()->paginate(4);

    return view('product_details', compact('data'));

}

public function addToCart(Request $request, $id)
{
    if(Auth::id()) {
        $user = Auth ::user();
        // dd($user)
        $product = product::findorFail($id);
        //dd($product)
        $cart = new Cart();
        $cart->name = $user->name;
        $cart->email = $user->email;
        $cart->phone = $user->phone;
        $cart->address = $user->address;
        $cart->userId = $user->id;
        $cart->productId = $product->id;
        $cart->productName = $product->productName;
        if($product->discountPrice != null){
            $cart->unitPrice = $product->discountPrice;
            $cart->totalPrice = $product->discountPrice * $request->quantity;

        } else {
            $cart->unitPrice = $product->productPrice;
            $cart->totalPrice = $product->productPrice * $request->quantity;
        }

        if($product->quantity < $request->quantity){
            return redirect()->back()->with('error', 'The quantity you entered is more than quqntity available');
        } else {
            $cart->productQuantity = $request->quantity;
            $cart->productImage = $product->productImage;
            $cart->save();
        }
        return redirect()->back()->with('message', 'product added successfully');
    } else {
        return redirect('login');

    }

}

//authentication befro acessing the cart page
public function carts()
{
    if (Auth::user()){

        $userId = Auth::user()->id;
        $carts = cart::where('userId', $userId)->get();
        return view('carts', compact('carts'));
    }else{
        return redirect('login');
    }
}

public function payonDelivery()
{
    return view('payonDelivery');
}

public function deletecarte($id)
{
    
   $data = cart::find($id);
    $data->delete();
    return redirect()->back()->with('suceess', 'cart deleted successfully');

}


}


