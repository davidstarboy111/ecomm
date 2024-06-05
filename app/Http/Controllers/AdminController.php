<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //
    public function admin_dashboard()
    {
        return view('admin.index');
    }

    public function category()

    {
        $categories = category::orderBy('created_at', 'DESC')->paginate(5);//go to categorie table and get eg All,Get,First,Paginate, to be able to loop through the table
        return view('admin.category', compact('categories'));
    }

    public function add_category(Request $request)
    {
          //validation of inputs
    $validator = $request->validate([
       'category' => 'required|unique:categories,category',
    ],[
        'category.unique' => 'This category already exist.',
    ]);
   

    Category::create($validator);
    
    return redirect()->back()->with('suceess', 'category added successfully');
}
  //delete id
public function deleteCategory($id)
{
    
   $data = category::find($id);
    $data->delete();
    return redirect()->back()->with('suceess', 'category deleted successfully');

}

 //delete id
 public function deleteproduct($id)
 {
     
    $data = Product::find($id);
     $data->delete();
     return redirect()->back()->with('suceess', 'products deleted successfully');
 
 }

public function createProduct()
{
    return view('admin/createProduct');
}

public function addProduct(Request $request)
    {
        $request->validate([
            'productName' => 'required|max:255',
            'productCategory' => 'required|max:255',
            'productImage' => 'nullable|file|max:10000',
            'productDescription' => 'required',
            'manufacturerName' => 'required|max:255',
            'status' => 'required',
            'productPrice' => 'required',
            'discountPrice' => 'nullable',
            'quantity' => 'nullable',
            'warranty' => 'nullable|max:255',
        ]);

        $product = new Product();
        $product->productName = $request->productName;
        $product->productCategory = $request->productCategory;
        $product->productDescription = $request->productDescription;
        $product->manufacturerName = $request->manufacturerName;
        $product->status = $request->status;
        $product->productPrice = $request->productPrice;
        $product->discountPrice = $request->discountPrice;
        $product->quantity = $request->quantity;
        $product->warranty = $request->warranty;
        $product->featuredProduct = $request->featuredProduct;

        if ($request->hasFile('productImage')) {
            $image = $request->file('productImage');
            $productImage = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('productFolder'), $productImage);
            $product->productImage = $productImage;  // Correctly assign the image name
        }

        $product->save();

        return redirect()->back()->with('message', 'Product added successfully');
    }

    public function products(){
        return view('admin.products');
    }

    public function editProduct($productId){
        $product = Product::findorFail($productId);
       // $productCategory = $product->productCategory;
        return view('admin.editProduct', compact('product'));
    }

    public function productUpdate(Request $request, $id){
        {
            $request->validate([
                'productName' => 'required|max:255',
                'productCategory' => 'required|max:255',
                'productImage' => 'nullable|file|max:10000',
                'productDescription' => 'required',
                'manufacturerName' => 'required|max:255',
                'status' => 'required',
                'productPrice' => 'required',
                'discountPrice' => 'nullable',
                'quantity' => 'nullable',
                'warranty' => 'nullable|max:255',
            ]);
    
            $product = Product::find($id);
            $product->productName = $request->productName;
            $product->productCategory = $request->productCategory;
            $product->productDescription = $request->productDescription;
            $product->manufacturerName = $request->manufacturerName;
            $product->status = $request->status;
            $product->productPrice = $request->productPrice;
            $product->discountPrice = $request->discountPrice;
            $product->quantity = $request->quantity;
            $product->warranty = $request->warranty;
            $product->featuredProduct = $request->featuredProduct;
    
            if ($request->hasFile('productImage')) {
                $image = $request->file('productImage');
                $productImage = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('productFolder'), $productImage);
                $product->productImage = $productImage;  // Correctly assign the image name
            }
    
            $product->save();
    
            return redirect()->route('products')->with('message', 'Product updated successfully');
        }
    
    }
}
