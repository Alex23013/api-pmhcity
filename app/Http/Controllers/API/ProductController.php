<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use App\Models\PhotoProduct;
use Validator;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $products = Product::with('photoProducts')->where('user_id', $user->id)->get();
        $response = [
            'success' => true,
            'message' => 'Products retrieved successfully.',
            'data' => ProductResource::collection($products)
        ];
        return response()->json($response, 200);
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
            'price' => 'required',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'is_active' => 'required',
            'material_id' => 'nullable|exists:materials,id',
            'brand_id' => 'nullable|exists:brands,id',
            'color_id' => 'nullable|exists:colors,id',
            'size_ids' => 'nullable',
            'article_code' => 'nullable',
            'status_product_id' => 'nullable|exists:status_products,id',
            'photo1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo6' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $productData = $request->only([
            'name', 'description', 'user_id', 'price', 'category_id', 'subcategory_id', 'is_active', 'brand_id', 'status_product_id',
        ]);
    
        // Add material_id if it is present and not null
        if ($request->has('material_id') && !is_null($request->material_id)) {
            $productData['material_id'] = $request->material_id;
        }
        // Add article_code if it is present and not null
        if($request->has('article_code') && !is_null($request->article_code)) {
            $productData['article_code'] = $request->article_code;
        }
        //dd($request->input('size_ids'));
        
        $productData['size_ids'] = $request->input('size_ids');
        $productData['color_id'] = $request->input('color_id');    

        $product = Product::create($productData);
        $product->pmh_reference_code = 'PMH' . str_pad($product->id, 6, '0', STR_PAD_LEFT);
        $product->save();

        // Handle photo uploads
        $photos = ['photo1', 'photo2', 'photo3', 'photo4', 'photo5', 'photo6'];
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
        $response = [
            'success' => true,
            'message' => 'Product retrieved successfully.',
            'data' => new ProductResource($product)
        ];
        return response()->json($response, 200);
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
            'product_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'user_id' => 'required|exists:users,id',
            'price' => 'required',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'is_active' => 'required',
            'material_id' => 'nullable|exists:materials,id',
            'brand_id' => 'nullable|exists:brands,id',
            'status_product_id' => 'nullable|exists:status_products,id'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $product = Product::find($input['product_id']);
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
        $product->name = $input['name'];
        $product->description = $input['description'];
        $product->user_id = $input['user_id'];
        $product->price = $input['price'];
        $product->category_id = $input['category_id'];
        $product->subcategory_id = $input['subcategory_id'];
        $product->is_active = $input['is_active'];
        $product->material_id = $input['material_id']?? null; # TODO: only optional
        $product->brand_id = $input['brand_id']?? null;
        $product->status_product_id = $input['status_product_id']?? null;
        $product->size_ids = $input['size_ids']?? null;
        $product->color_id = $input['color_id'] ?? null;
        $product->updated_at = now();
        $product->save();

        $product->photoProducts()->delete();

        // Handle photo uploads
        $photos = ['photo1', 'photo2', 'photo3','photo4', 'photo5', 'photo6'];
        foreach ($photos as $photo) {
            if ($request->hasFile($photo)) {
                $imagePath = $request->file($photo)->store('product_photos', 'public');

                // Create PhotoProduct record
                PhotoProduct::create([
                    'url' => $imagePath,
                    'product_id' => $product->id
                ]);
            }
        }

        $response = [
            'success' => true,
            'message' => 'Product updated successfully.',
            'data' => new ProductResource($product)
        ];
        return response()->json($response, 200);
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
