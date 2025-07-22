<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use App\Models\Store;
use App\Models\PhotoProduct;
use Validator;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductMarketplaceResource;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends BaseController
{
    public function listTopProducts()
    {
        // Get 3 lowest price products from category 1
        $category1Products = Product::with(['photoProducts', 'brand', 'material', 'status_product', 'category', 'subcategory'])
            ->where('is_active', true)
            ->where('category_id', 1)
            ->orderBy('price', 'asc')
            ->take(3)
            ->get();

        // Get 3 lowest price products from category 2
        $category2Products = Product::with(['photoProducts', 'brand', 'material', 'status_product', 'category', 'subcategory'])
            ->where('is_active', true)
            ->where('category_id', 2)
            ->orderBy('price', 'asc')
            ->take(3)
            ->get();


        return response()->json([
            'success' => true,
            'message' => 'Top products retrieved successfully.',
            'data' => ProductMarketplaceResource::collection($category1Products->merge($category2Products))
        ], 200);
    }

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
            'composition' => 'nullable',
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
            'photo1' => 'nullable',
            'photo2' => 'nullable',
            'photo3' => 'nullable',
            'photo4' => 'nullable',
            'photo5' => 'nullable',
            'photo6' => 'nullable',
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

        if($request->has('composition') && !is_null($request->composition)) {
            $productData['composition'] = $request->composition;
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
            'composition' => 'nullable',
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
        $product->composition = $input['composition']?? null;
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

        // Handle photo uploads
        $photos = ['photo1', 'photo2', 'photo3','photo4', 'photo5', 'photo6'];
        $requestPhotoUrls = array_filter($request->only($photos));
        
        // Convert all request URLs to relative paths
        $normalizedRequestUrls = array_map(function($url) {
            if (str_contains($url, '/storage/')) {
                return substr($url, strpos($url, '/storage/') + strlen('/storage/'));
            }
            return $url;
        }, $requestPhotoUrls);

        // Delete photos that are not in the request
        foreach ($product->photoProducts as $dbPhoto) {
            if (!in_array($dbPhoto->url, $normalizedRequestUrls)) {
                $dbPhoto->delete();
            }
        }
        
        // Add new photos from the request
        foreach ($photos as $photo) {
            if ($request->has($photo)) {
                if ($request->hasFile($photo)) {
                    $imagePath = $request->file($photo)->store('product_photos', 'public');
                    // Create PhotoProduct record
                    PhotoProduct::create([
                        'url' => $imagePath,
                        'product_id' => $product->id
                    ]);
                }
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

    public function uploadProductsCSV(Request $request)
    {
        // Validate file input
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|exists:stores,id',
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $store = Store::find($request->store_id);
        if (is_null($store)) {
            return response()->json(['error' => 'Store not found'], 404);
        }
        $user = $store->user;
        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');

        if (!$handle) {
            return response()->json(['error' => 'Could not open file'], 500);
        }
        $productsUploaded = 0;
        // Skip the header row if present
        $firstRowSkipped = false;

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            // Skip the header row
            if (!$firstRowSkipped) {
                $firstRowSkipped = true;
                continue;
            }

            // Ensure the row has the correct number of columns
            if (count($data) > 18) {
                return response()->json(['error' => 'Invalid row: '.count($data)], 400);
            }

            // Extract fields
            [
                $name,
                $description,
                $composition,
                $price,
                $subcategory_id,
                $is_active,
                $status_product_id,
                $material_id,              
                $brand_id,
                $size_ids,
                $color_id,
                $article_code,
                $photo1,
                $photo2,
                $photo3,
                $photo4,
                $photo5,
                $photo6,
            ] = $data;

            $size_ids = explode(',', $size_ids); // Convert to array
            $category_id = Subcategory::find($subcategory_id)->category_id;
            // Store the product
            $product = Product::create([
                'name' => $name,
                'user_id' => $user->id,
                'description' => $description,
                'composition'=> $composition,
                'material_id' => $material_id,
                'price' => floatval($price),
                'category_id' => intval($category_id),
                'subcategory_id' => intval($subcategory_id),
                'is_active' => $is_active,
                'brand_id' => intval($brand_id),
                'status_product_id' => intval($status_product_id),
                'size_ids' => implode(',', $size_ids), // Store as a string
                'color_id' => intval($color_id),
                'article_code' => $article_code,
            ]);
            $product->pmh_reference_code = 'PMH' . str_pad($product->id, 6, '0', STR_PAD_LEFT);
            $product->save();
            $photos = ['photo1', 'photo2', 'photo3', 'photo4', 'photo5', 'photo6'];
            foreach ($photos as $photo) {
                $photo = ${$photo}; // Use variable variables to access the photo variables
                if (!empty($photo)) {
    
                    if (str_contains($photo, 'https')) { // Check if the value contains "https"
                        // Create PhotoProduct record with the URL
                        PhotoProduct::create([
                            'url' => $photo,
                            'product_id' => $product->id
                        ]);
                    } 
                    // upload bacth don't support files
                }
            }
            $productsUploaded++;
        }

        fclose($handle);

        return response()->json(['message' => $productsUploaded.' Products uploaded successfully'], 200);
    }

}
