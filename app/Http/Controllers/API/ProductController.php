<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use App\Models\PhotoProduct;
use Validator;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
   
class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        
        $products = Product::with('photoProducts')->get();
    
        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required',
            'user_id' => 'required|exists:users,id',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'is_active' => 'required',
            'photo1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $product = Product::create($request->only([
            'name', 'description', 'user_id', 'price', 'category_id', 'subcategory_id', 'is_active'
        ]));

        // Handle photo uploads
        $photos = ['photo1', 'photo2', 'photo3'];
        foreach ($photos as $photo) {
            if ($request->hasFile($photo)) {
                $imagePath = $request->file($photo)->store('product_photos', 'public'); // Store in storage/app/public/product_photos

                // Create PhotoProduct record
                PhotoProduct::create([
                    'url' => $imagePath,
                    'product_id' => $product->id
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => new ProductResource($product)
        ], 201);
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $product = Product::find($id);
  
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
   
        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required',
            'user_id' => 'required|exists:users,id',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'is_active' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $product->name = $input['name'];
        $product->description = $input['description'];
        $product->user_id = $input['user_id'];
        $product->price = $input['price'];
        $product->category_id = $input['category_id'];
        $product->subcategory_id = $input['subcategory_id'];
        $product->is_active = $input['is_active'];
        $product->save();

        // Handle photo uploads
        $photos = ['photo1', 'photo2', 'photo3'];
        foreach ($photos as $photo) {
            if ($request->hasFile($photo)) {
                $imagePath = $request->file($photo)->store('product_photos', 'public'); // Store in storage/app/public/product_photos

                // Create PhotoProduct record
                PhotoProduct::create([
                    'url' => $imagePath,
                    'product_id' => $product->id
                ]);
            }
        }
   
        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
   
        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
