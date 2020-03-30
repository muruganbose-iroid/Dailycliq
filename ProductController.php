<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Manufacturer;
use App\TaxClass;
use App\WeightClass;
use App\LengthClass;

use App\TmpProduct;
use App\TmpProductImage;
use App\TmpProductAdditionalInfo;
use App\TmpProductFaq;
use App\TmpProductAttribute;

use App\Product;

//Murugan Start 
use App\store99;
//Murugan End 



use App\ProductImage;
use App\ProductAdditionalInfo;
use App\ProductFaq;
use App\ProductAttribute;

use Illuminate\Http\Request;
use Validator;
use DB;

class ProductController extends Controller
{
    public function showProducts(Request $request)
    {
        $search = $request->search;
        $query = Product::query();
        if ($search) {
            $query->where(function ($sub) use ($search) {
                $sub->orWhere('name', 'like', "%" . $search . "%");
            });
        }
        $query->where('approved','1');
        $query->orderBy('created_at', 'desc');
        $products = $query->paginate(10);
        $data = [
            'products' => $products,
            'search' => $search,
        ];
        return view('admin.products.list', $data);
    }
    public function deleteProduct(Request $request){
       
        $product= Product::find($request->id);
        if($product){
            $product->approved = '0';
            try {
                $product->save();
                return redirect()->route('showProducts');
                return response()->json(['status' => true, 'message' => 'Product Removed successfully']);
            } catch (\Exception $e) {
                print_r($e->getMessage());
                return response()->json(['status' => false, 'errors' => ['coulld_not_save' => 'Product details could not update successfully']]);
            }
        }       
    }
//Murugan Start 

    public function store99products(Request $request)
        {
            
            $products = DB::table('Products')
            ->join('Store99s', 'Store99s.product_id', '=', 'Products.id')
            ->join('Product_images', 'Product_images.product_id', '=', 'Store99s.product_id')
            ->join('Vendors', 'Vendors.id', '=', 'Products.vendor_id')
            //->where('Store99s.active', '=', '1')
            ->where('Store99s.active', '=', 1)
            ->select('Products.name', 'Vendors.company_name','Products.id', 'Products.dcin', 'Product_images.image', 'Products.price', 'Products.mrp')
            
            ->get();

            //print_r($products);exit;
          return view('admin.products.99store_list', compact('products'));

        //return view('admin.products.99store_list', $data);
        }

    public function store99(Request $request){
       
            $product= Product::find($request->id);
            $vendor_id  = $product['vendor_id'];
            //echo $vendor_id;exit;
            $store99 = new store99;
            $store99->product_id = $request->id;
            $store99->vendor_id = $vendor_id;    

        
        if($product){
            $product->store_99 = '1';
            try {
                $store99->save();
                $product->save();
                return redirect()->route('showProducts');
                return response()->json(['status' => true, 'message' => 'Product Moved to Store99  successfully']);
            } catch (\Exception $e) {
                print_r($e->getMessage());
                return response()->json(['status' => false, 'errors' => ['coulld_not_save' => 'Product details could not update successfully']]);
            }
        }       
    }

    public function unstore99($id){
        DB::table('Store99s')
        ->where('product_id',$id)
        ->update(['active'=>0]);
        //$store99= Store99::find($id);
        $product= Product::find($id);
        //echo $store99;exit;
        if($product){
           // $store99->active = '0';
            $product->store_99 = '0';
            try {
               
                $product->save();
                return redirect()->route('store99products');
                return response()->json(['status' => true, 'message' => 'Moved to Product List   successfully']);
            } catch (\Exception $e) {
                print_r($e->getMessage());
                return response()->json(['status' => false, 'errors' => ['coulld_not_save' => 'Product details could not update successfully']]);
            }
        }       
    }



//Murugan End

    

    public function showNewProduct(Request $request)
    {
        $manufacturers = Manufacturer::get();
        $brands = Brand::get();
        $length_classes = LengthClass::get();
        $weight_classes = WeightClass::get();
        $tax_classes = TaxClass::get();        
        $categories = Category::get();

        $data = [
            'manufacturers' => $manufacturers,
            'brands' => $brands,
            'length_classes' => $length_classes,
            'tax_classes' => $tax_classes,
            'weight_classes' => $weight_classes,
            'categories' => $categories,
        ];
        return view('admin.products.new', $data);
    }

    public function newProduct(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'category' => 'required',
            'manufacturer' => 'required',
            'brand' => 'required',
            'upc' => 'required',
            'ean' => 'required',
            'product_id' => 'required',
            'isbn' => 'required',
            'mpn' => 'required',
            'tax' => 'required',
            'tax_category' => 'required',
            'weight' => 'required',
            'weight_category' => 'required',
            'length' => 'required',
            'width' => 'required',
            'height' => 'required',
            'dimensions_unit' => 'required',
        ];
        $messages = [
            'name.required' => 'Name is required',
            'description.required' => 'Description is required',
            'category.required' => 'Category is required',
            'manufacturer.required' => 'Manufacturer is required',
            'brand.required' => 'Brand is required',
            'upc.required' => 'UPC is required',
            'ean.required' => 'EAN is required',
            'product_id.required' => 'Product ID is required',
            'isbn.required' => 'ISBN is required',
            'mpn.required' => 'MPN is required',
            'tax.required' => 'TAX is required',
            'tax_category.required' => 'TAX category is required',
            'weight.required' => 'Weight is required',
            'weight_category.required' => 'Weight category is required',
            'length.required' => 'Length is required',
            'width.required' => 'Width is required',
            'height.required' => 'Height is required',
            'dimensions_unit.required' => 'Dimensions unit is required',
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);

        if (!$validator->passes()) {
            $messages = $validator->messages();
            $errors = [];
            foreach ($rules as $key => $value) {
                $err = $messages->first($key);
                if ($err) {
                    $errors[$key] = $err;
                }
            }
            return response()->json(['status' => 0, 'errors' => $errors]);
        }

        $product = new Product();
        $product->product_id = $request->product_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->category_id = $request->category;
        $product->manufacturer_id = $request->manufacturer;
        $product->brand_id = $request->brand;
        $product->upc = $request->upc;
        $product->ean = $request->ean;
        // $product->dsin = $request->dsin;
        $product->isbn = $request->isbn;
        $product->mpn = $request->mpn;
        $product->tax = $request->tax;
        $product->tax_class_id = $request->tax_category;
        $product->weight = $request->weight;
        $product->weight_class_id = $request->weight_category;
        $product->length = $request->length;
        $product->width = $request->width;
        $product->height = $request->height;
        $product->length_class_id = $request->dimensions_unit;

        try {
            $product->save();
            return response()->json(['status' => true, 'message' => 'Product added successfully']);
        } catch (\Exception $e) {
            print_r($e->getMessage());
            return response()->json(['status' => false, 'errors' => ['coulld_not_save' => 'Product details could not save successfully']]);
        }

    }

    public function editProduct($id = null, Request $request)
    {
        $manufacturers = Manufacturer::get();
        $brands = Brand::get();
        $length_classes = LengthClass::get();
        $tax_classes = TaxClass::get();
        $weight_classes = WeightClass::get();
        $categories = Category::get();
        $product = Product::where('id', $id)->first();
        if (!$product) {
            return redirect()->route('showProducts');
        }
        $data = [
            'manufacturers' => $manufacturers,
            'brands' => $brands,
            'length_classes' => $length_classes,
            'tax_classes' => $tax_classes,
            'weight_classes' => $weight_classes,
            'categories' => $categories,
            'product' => $product,
        ];
        return view('admin.products.edit', $data);
    }

    public function updateProduct($id = null, Request $request)
    {
        $product = Product::where('id', $id)->first();
        if (!$product) {
            return response()->json(['status' => false, 'errors' => ['coulld_not_save' => 'Product details could not update successfully']]);
        }
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'category' => 'required',
            'manufacturer' => 'required',
            'brand' => 'required',
            'upc' => 'required',
            'ean' => 'required',
            'product_id' => 'required',
            'isbn' => 'required',
            'mpn' => 'required',
            'tax' => 'required',
            'tax_category' => 'required',
            'weight' => 'required',
            'weight_category' => 'required',
            'length' => 'required',
            'width' => 'required',
            'height' => 'required',
            'dimensions_unit' => 'required',
        ];
        $messages = [
            'name.required' => 'Name is required',
            'description.required' => 'Description is required',
            'category.required' => 'Category is required',
            'manufacturer.required' => 'Manufacturer is required',
            'brand.required' => 'Brand is required',
            'upc.required' => 'UPC is required',
            'ean.required' => 'EAN is required',
            'product_id.required' => 'Product ID is required',
            'isbn.required' => 'ISBN is required',
            'mpn.required' => 'MPN is required',
            'tax.required' => 'TAX is required',
            'tax_category.required' => 'TAX category is required',
            'weight.required' => 'Weight is required',
            'weight_category.required' => 'Weight category is required',
            'length.required' => 'Length is required',
            'width.required' => 'Width is required',
            'height.required' => 'Height is required',
            'dimensions_unit.required' => 'Dimensions unit is required',
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);

        if (!$validator->passes()) {
            $messages = $validator->messages();
            $errors = [];
            foreach ($rules as $key => $value) {
                $err = $messages->first($key);
                if ($err) {
                    $errors[$key] = $err;
                }
            }
            return response()->json(['status' => 0, 'errors' => $errors]);
        }

        $product->product_id = $request->product_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->category_id = $request->category;
        $product->manufacturer_id = $request->manufacturer;
        $product->brand_id = $request->brand;
        $product->upc = $request->upc;
        $product->ean = $request->ean;
        // $product->dsin = $request->dsin;
        $product->isbn = $request->isbn;
        $product->mpn = $request->mpn;
        $product->tax = $request->tax;
        $product->tax_class_id = $request->tax_category;
        $product->weight = $request->weight;
        $product->weight_class_id = $request->weight_category;
        $product->length = $request->length;
        $product->width = $request->width;
        $product->height = $request->height;
        $product->length_class_id = $request->dimensions_unit;

        try {
            $product->save();
            return response()->json(['status' => true, 'message' => 'Product updated successfully']);
        } catch (\Exception $e) {
            print_r($e->getMessage());
            return response()->json(['status' => false, 'errors' => ['coulld_not_save' => 'Product details could not update successfully']]);
        }
    }

    public function showProductImages($id = null, Request $request)
    {
        $product = Product::where('id', $id)->first();
        $images = ProductImage::where('product_id', $id)->get();
        if (!$product) {
            return redirect()->route('showProducts');
        }
        $data = [
            'product' => $product,
            'images' => $images,
        ];
        return view('admin.products.images', $data);
    }

    public function makeFeaturedImage($id = null, Request $request)
    {
        $product_image = ProductImage::where('id', $id)->first();
        if ($product_image) {
            ProductImage::where('product_id', $product_image->product_id)->update(['default' => 0]);
            ProductImage::where('id', $product_image->id)->update(['default' => 1]);
            return redirect()->route('showProductImages', ['id' => $product_image->product_id]);
        }
        return redirect()->route('showProducts');
    }

    public function deleteFeaturedImage($id = null, Request $request)
    {
        $product_image = ProductImage::where('id', $id)->first();
        if($product_image){
            unlink(public_path()."/".$product_image->image);
            $product_image->delete();            
        }
        return redirect()->route('showProductImages', ['id' => $id]);
    }

    public function addProductImage($id = null, Request $request)
    {
        $product = Product::where('id', $id)->first();
        if (!$product) {
            return response()->json(['status' => false, 'errors' => ['coulld_not_save' => 'Product details could not update successfully']]);
        }
        try {
            $product_image = new ProductImage();
            $count = ProductImage::where('product_id', $product->id)->count();
            if (!$count) {
                $product_image->default = 1;
            }
            $product_image->product_id = $id;
            if (request()->image) {
                $imageName = time() . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('images/products/'), $imageName);
                $product_image->image = 'images/products/' . $imageName;
            }
            $product_image->save();
            return response()->json(['status' => true, 'message' => 'Product image added successfully']);
        } catch (\Exception $e) {
            // print_r($e->getMessage());
            return response()->json(['status' => false, 'errors' => ['coulld_not_save' => 'Could not add product image']]);
        }
    }


    //pending products list
    public function showAdminProductList(Request $request){
        $search = $request->search;
        $query = TmpProduct::query();
        $query->with('images');
        if ($search) {
            $query->where(function ($sub) use ($search) {
                $sub->orWhere('name', 'like', "%" . $search . "%");
            });
        }
        $query->where('completed_status', '1');
        $query->orderBy('created_at', 'desc');
        $products = $query->paginate(10);

        // $products = TmpProduct::with('images')->where('vendor_id', Auth::user()->id)->get();
        // print_r($products->toArray());exit;
        $data = [
            'products' => $products,
            'search' => $search
        ];
        return view('admin.products.pendingproductlist', $data);
    }

    public function showPendingProductAdmin(TmpProduct $product, Request $request){
        $attributes = [];
        if ($product->attributes->count() > 0) {
            foreach ($product->attributes as $rec) {
                $attributes[$rec->attribute] = $rec->value;
            }
        }
        $category = Category::find($product->category_id);
        if (!$category) {
            return redirect()->route('showCatelog');
        }

        // $brands = Brand::get();
        $data = [
            // 'brands' => $brands,
            'category' => $category,
            'properties' => $category->properties,
            'product' => $product,
            'attributes' => $attributes,
            'step' => 1
        ];
        return view('admin.products.new', $data);
    }

    public function savePendingProductAdmin(TmpProduct $product, Request $request){
        $product->name = isset($request->name) ? $request->name : null;
        $product->dcin = isset($request->dcin) ? $request->dcin : null;
        $product->ean = isset($request->ean) ? $request->ean : null;
        $product->upc = isset($request->upc) ? $request->upc : null;
        $product->brand_id = isset($request->brand) ? $request->brand : null;
        $product->other_brand = isset($request->brand_other) ? $request->brand_other : null;
        $product->category_id = isset($request->category) ? $request->category : null;
        $product->pathayapura_listing = isset($request->pathayapura_listing) ? 1 : 0;

        if (isset($product->step) && $product->step < 1) {
            $product->step = 1;
        } else if (!$product->step) {
            $product->step = 1;
        }

        try {
            DB::beginTransaction();
            $product->save();
            if ($product->id) {
                $attr_array = [];
                if (isset($request->attr) && count($request->attr) > 0) {
                    foreach ($request->attr as $key => $value) {
                        if($value){
                            $attr_array[] = [
                                'attribute' => $key,
                                'value' => $value,
                                'temp_product_id' => $product->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }
                if (count($attr_array) > 0) {
                    TmpProductAttribute::insert($attr_array);
                }
                DB::commit();
                if ($request->save != 'Save') {
                    return response()->json(['status' => 1, 'redirect' => route('showPendingProductPricing', ['product' => $product->id])]);
                } else {
                    return response()->json(['status' => 1, 'message' => "Product saved temporarly"]);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            // print_r($e->getMessage());exit;
            $errors['couldnot_save'] = "Could not update product";
            return response()->json(['status' => 0, 'errors' => $errors]);
        }
    }

    public function showPendingProductPricing(TmpProduct $product, Request $request){
        $weight_classes = WeightClass::get();
        $data = [
            'product' => $product,
            'weight_classes' => $weight_classes,
            'step' => 2
        ];
        return view('admin.products.pricing', $data);
    }

    public function savePendingProductAdminPricing(TmpProduct $product, Request $request){
        $rules = [
            'gst' => "required|numeric",
            'price' => 'required|numeric',
            'mrp' => 'required|numeric',
            'wholesale' => 'required|numeric',
            'wholesale_unit' => 'required|numeric',
        ];
        $messages = [
            'gst.required' => "GST is required",
            'gst.numeric' => "GST must be a number",
            'price.required' => 'Price is required',
            'price.numeric' => "Price must be a number",
            'mrp.required' => 'MRP is required"',
            'mrp.numeric' => "MRP must be a number",
            'wholesale.required' => 'Wholesale price is required',
            'wholesale.numeric' => "Wholesale must be a number",
            'wholesale_unit.required' => 'Min. wholesale unit is required',
            'wholesale_unit.numeric' => "Wholesale unit must be a number",
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);
        if (!$validator->passes()) {
            $messages = $validator->messages();
            $errors = [];
            foreach ($rules as $key => $value) {
                $err = $messages->first($key);
                if ($err) {
                    $errors[$key] = $err;
                }
            }
            return response()->json(['status' => 0, 'errors' => $errors]);
        }

        try {
            DB::beginTransaction();
            $product->gst = $request->gst;
            $product->price = $request->price;
            $product->mrp = $request->mrp;
            $product->wholesale = $request->wholesale;
            $product->wholesale_unit = $request->wholesale_unit;
            if (isset($request->sess) && $request->sess) {
                $product->sess = 1;
            } else {
                $product->sess = 0;
            }
            if (isset($request->cod) && $request->cod) {
                $product->cod = 1;
            } else {
                $product->cod = 0;
            }

            if (isset($product->step) && $product->step < 2) {
                $product->step = 2;
            }

            $product->save();
            DB::commit();
            if ($request->save != 'Save') {
                return response()->json(['status' => 1, 'redirect' => route('showPendingProductAdminPackageDetails', ['product' => $product->id])]);
            } else {
                return response()->json(['status' => 1, 'message' => "Product pricing details saved temporarly"]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            $errors['couldnot_save'] = "Could not update product pricing details";
            return response()->json(['status' => 0, 'errors' => $errors]);
        }
    }

    public function showPendingProductAdminPackageDetails(TmpProduct $product, Request $request){
         // print_r($product->toArray());
         $length_classes = LengthClass::get();
         $weight_classes = WeightClass::get();
         $data = [
             'product' => $product,
             'length_classes' => $length_classes,
             'weight_classes' => $weight_classes,
             'step' => 3
         ];
         return view('admin.products.package', $data);
    }

    public function savePendingProductAdminPackageDetails(TmpProduct $product, Request $request){
         // print_r($request->all());
         $rules = [
            'weight' => "required|numeric",
            'weight_unit' => 'required',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'dimensions_unit' => 'required',
            'shipping_processing_time' => 'required|numeric',
        ];
        $messages = [
            'weight.required' => "Weight is required",
            'weight.numeric' => "Weight must be a number",
            'weight_unit.required' => 'Weight unit is required',
            'length.required' => 'Length is required',
            'length.numeric' => 'Length must be a number',
            'width.required' => 'Width is required',
            'width.numeric' => 'Width must be a number',
            'height.required' => 'Height is required',
            'height.numeric' => 'Height must be a number',
            'dimensions_unit.required' => 'Dimensions unit is required',
            'shipping_processing_time.required' => 'Shipping processing time is required',
            'shipping_processing_time.numeric' => 'Shipping processing time must be a number',
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);
        if (!$validator->passes()) {
            $messages = $validator->messages();
            $errors = [];
            foreach ($rules as $key => $value) {
                $err = $messages->first($key);
                if ($err) {
                    $errors[$key] = $err;
                }
            }
            return response()->json(['status' => 0, 'errors' => $errors]);
        }

        try {
            DB::beginTransaction();
            $product->weight = $request->weight;
            $product->weight_class_id = $request->weight_unit;
            $product->length = $request->length;
            $product->width = $request->width;
            $product->height = $request->height;
            $product->length_class_id = $request->dimensions_unit;
            $product->shipping_processing_time = $request->shipping_processing_time;

            if (isset($request->free_delivery) && $request->free_delivery) {
                $product->free_delivery = 1;
            } else {
                $product->free_delivery = 0;
            }

            if (isset($product->step) && $product->step < 3) {
                $product->step = 3;
            }

            $product->save();
            DB::commit();
            if ($request->save != 'Save') {
                return response()->json(['status' => 1, 'redirect' => route('showPendingProductAdminImages', ['product' => $product->id])]);
            } else {
                return response()->json(['status' => 1, 'message' => "Product package details saved temporarly"]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            $errors['couldnot_save'] = "Could not update product package details";
            return response()->json(['status' => 0, 'errors' => $errors]);
        }
    }

    public function showPendingProductAdminImages(TmpProduct $product, Request $request){
        $query = TmpProductImage::query();
        $query->where('temp_product_id', '=', $request->product);
        $query->orderBy('created_at', 'desc')->limit(20);
        $product_images =  $query->get();
        $data = [
            'product_images' => $product_images,
            'product' => $product,
            'step' => 4
        ];
        return view('admin.products.images', $data);
    }

    public function savePendingProductAdminImage(TmpProduct $product, Request $request)
    {
        $resp = [];
        if (request()->image) {
            try {
                $imageName = time() . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('images/products/' . $product->id . '/'), $imageName);

                $product_image = new TmpProductImage([
                    'image' =>  'images/products/' . $product->id . '/' . $imageName
                ]);

                $product->images()->save($product_image);

                if (isset($product->step) && $product->step < 4) {
                    $product->step = 4;
                    $product->save();
                }

                $redirect = 0;

                if ($request->save == 'Save') {
                    $redirect = 1;
                }

                $resp = [
                    'status' => true,
                    'message' => 'Product image Uploaded successfully',
                    'redirect' => $redirect,
                    'product' => $product->id
                ];
            } catch (\Exception $e) {
                // print_r($e->getMessage());exit;
                $errors['couldnot_save'] = "Could not upload product image";
                $resp = [
                    'status' => false,
                    'errors' => $errors
                ];
            }
        } else {
            $errors['couldnot_save'] = "Could not upload product image";
            $resp = [
                'status' => false,
                'errors' => $errors
            ];
        }
        return response()->json($resp);
    }

    public function deletePendingProductImage(TmpProductImage $image, Request $request)
    {
        if ($image) {
            $id = $image->temp_product_id;
            $path = public_path() . "/" . $image->image;
            unlink($path);
            $image->delete();
            return redirect()->route('showPendingProductAdminImages', ['product' => $id]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function showPendingProductAdminAdditionalInfo(TmpProduct $product, Request $request)
    {
        // print_r($product->toArray());
        $query = TmpProductAdditionalInfo::query();
        $query->where('temp_product_id', '=', $request->product->id);
        $query->orderBy('created_at', 'desc')->limit(20);
        $additional_fields =  $query->get();
        $data = [
            'product' => $product,
            'additional_fields' => $additional_fields,
            'step' => 5
        ];
        return view('admin.products.additional_info', $data);
    }

    public function savePendingProductAdminAdditionalInfo(TmpProduct $product, Request $request)
    {
        $rules = [
            'name' => "required",
            'field_details' => 'required',
        ];
        $messages = [
            'name.required' => "Filed Name is required",
            'filed_details.required' => "Field Details is required",
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);
        if (!$validator->passes()) {
            $messages = $validator->messages();
            $errors = [];
            foreach ($rules as $key => $value) {
                $err = $messages->first($key);
                if ($err) {
                    $errors[$key] = $err;
                }
            }
            return response()->json(['status' => 0, 'errors' => $errors]);
        }
        $attr_array[] = [
            'name' =>  $request->name,
            'value' => $request->field_details,
            'temp_product_id' => $product->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (count($attr_array) > 0) {
            TmpProductAdditionalInfo::insert($attr_array);
        }

        if (isset($product->step) && $product->step < 5) {
            $product->step = 5;
            $product->save();
        }


        if ($request->save != "Save") {
            return response()->json(['status' => 1, 'redirect' => route('showPendingProductAdminFaq', ['product' => $product->id])]);
        } else {
            return response()->json(['status' => 0, 'message' => "Product addditional information details saved temporarly"]);
        }
    }

    public function deleteCatelogAdditionalInfo(TmpProductAdditionalInfo $additionalinfo, Request $request)
    {
        if ($additionalinfo) {
            $id = $additionalinfo->temp_product_id;
            $additionalinfo->delete();
            return redirect()->route('showPendingProductAdminAdditionalInfo', ['product' => $id]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function showPendingProductAdminFaq(TmpProduct $product, Request $request)
    {
        // print_r($product->toArray());

        $query = TmpProductFaq::query();
        $query->where('temp_product_id', '=', $request->product->id);
        $query->orderBy('created_at', 'desc')->limit(20);
        $faq =  $query->get();
        $data = [
            'product' => $product,
            'faq' => $faq,
            'step' => 6
        ];

        return view('admin.products.faq', $data);
    }

    public function savePendingProductAdminFaq(TmpProduct $product, Request $request)
    {
        $rules = [
            'question' => "required",
            'field_answer' => 'required',
        ];
        $messages = [
            'question.required' => "Question is required",
            'filed_answer.required' => "Answer is required",
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);
        if (!$validator->passes()) {
            $messages = $validator->messages();
            $errors = [];
            foreach ($rules as $key => $value) {
                $err = $messages->first($key);
                if ($err) {
                    $errors[$key] = $err;
                }
            }
            return response()->json(['status' => 0, 'errors' => $errors]);
        }
        $attr_array[] = [
            'question' =>  $request->question,
            'answer' => $request->field_answer,
            'temp_product_id' => $product->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (count($attr_array) > 0) {
            TmpProductFaq::insert($attr_array);
        }

        if (isset($product->step) && $product->step < 6) {
            $product->completed_status = 1;
            $product->step = 6;
            $product->save();
        }

        if ($request->save != "Save") {
            return response()->json(['status' => 1, 'redirect' => route('showPendingProductListApproval',['product'=>$product->id])]);
        } else {
            return response()->json(['status' => 0, 'message' => "Product addditional information details saved temporarly"]);
        }
    }

    public function deletePendingProductFaq(TmpProductFaq $faq, Request $request)
    {
        if ($faq) {
            $id = $faq->temp_product_id;
            $faq->delete();
            return redirect()->route('showPendingProductAdminFaq', ['product' => $id]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function showPendingProductListApproval (TmpProduct $product, Request $request){
        $data = [
            'product' => $product,
            'step' => 7
        ];

        return view('admin.products.approval', $data);
    }

    public function savePendingProductListApproval(TmpProduct $product, Request $request){
        if($request->approved == 1){
            try {
                DB::beginTransaction(); 
                //insert main table

                $insert_array = $product->toArray();
                $insert_array['admin_comments'] = $request->admin_comments;
                unset($insert_array['id']);
                $main_product = Product::create($insert_array);
                
                //insert product additional info
                $additionalinfo = TmpProductAdditionalInfo::where('temp_product_id',$product->id)->get()->toArray();
                if(count($additionalinfo) > 0){
                    foreach($additionalinfo as $key=>$value){
                        $additionalinfo[$key]['product_id'] = $main_product->id;
                        unset($additionalinfo[$key]['temp_product_id']);
                        unset($additionalinfo[$key]['id']);
                        // unset($additionalinfo[$key]['approved']);
                    }
                    // print_r($additionalinfo);exit;
                    ProductAdditionalInfo::insert($additionalinfo);
                }

                //insert product attribute
                $attributes = TmpProductAttribute::where('temp_product_id',$product->id)->get()->toArray();
                if(count($attributes) > 0){
                    foreach($attributes as $key=>$value){
                        $attributes[$key]['product_id'] = $main_product->id;
                        unset($attributes[$key]['temp_product_id']);
                        unset($attributes[$key]['id']);
                    }
                    // print_r($attributes);exit;
                    ProductAttribute::insert($attributes);
                }

                //insert product faq
                $faqs = TmpProductFaq::where('temp_product_id',$product->id)->get()->toArray();
                if(count($faqs) > 0){
                    foreach($faqs as $key=>$value){
                        $faqs[$key]['product_id'] = $main_product->id;
                        unset($faqs[$key]['temp_product_id']);
                        unset($faqs[$key]['id']);
                    }
                    // print_r($additionalinfo);exit;
                    ProductFaq::insert($faqs);
                }

                //insert product image
                $images = TmpProductImage::where('temp_product_id',$product->id)->get()->toArray();
                if(count($images) > 0){
                    foreach($images as $key=>$value){
                        $images[$key]['product_id'] = $main_product->id;
                        unset($images[$key]['temp_product_id']);
                        unset($images[$key]['id']);
                    }
                    ProductImage::insert($images);
                }

                DB::commit();

                $product->delete();

                return response()->json(['status' => 1, 'message' => "Inserted"]);
            } catch (\Exception $e) {
                // print_r($e->getMessage());
                DB::rollback();
                $errors['couldnot_save'] = "Could not approve product details";
                return response()->json(['status' => 0, 'errors' => $errors]);
            }
        }else{
            $product->approved = $request->approved;
            $product->admin_comments = $request->admin_comments;
            $product->save();
            return response()->json(['status' => 1, 'redirect' => 1]);
        }
    }
    //variant Section start Here
    
    //new product variant registration step -1
    public function showNewAdminvariant(Product $product)
    {
        
        $product_details = $variant = isset($product) ? $product : null;
        $category = [];
        if($product_details){
            $category = Category::find($product_details['category_id']);
            if (!$category) {
                return redirect()->route('dashboard');
            }
        }else{
            return redirect()->route('dashboard');
        }
        // $brands = Brand::get();
        $data = [
            // 'brands'        => $brands,
            'category'      => $category,
            'properties'    => $category->properties,
            'product'       => $product_details,
            'variant'       => $variant,
            'step'          => 1
            
        ];
        return view('admin.products.newproductvariant', $data);
    }
    // Save new product variant 
    public function saveNewAdminvariant(Product $product,Request $request)
    {
        $parent_product = $product;
        if (isset($request->variant_id) && $request->variant_id) {
            $product = Product::find($request->variant_id);
        } else {
            $product = new Product();
            $product->variant = isset($request->product_id) ? $request->product_id : null;
        }
        $product->name                 = isset($request->name) ? $request->name : null;
        $product->dcin                 = isset($request->dcin) ? $request->dcin : null;
        $product->ean                  = isset($request->ean) ? $request->ean : null;
        $product->upc                  = isset($request->upc) ? $request->upc : null;
        $product->brand_id             = isset($request->brand) ? $request->brand : null;
        $product->other_brand          = isset($request->brand_other) ? $request->brand_other : null;
        $product->category_id          = isset($request->category) ? $request->category : null;
        $product->pathayapura_listing  = isset($request->pathayapura_listing) ? 1 : 0;  
        $product->vendor_id            = $parent_product['vendor_id'];
        $product->step                 = 1;
        $product->approved              = 3;
        try {
            DB::beginTransaction();
            $product->save();
            if ($product->id) {
                $attr_array = [];
                if (isset($request->attr) && count($request->attr) > 0) {
                    foreach ($request->attr as $key => $value) {
                        if ($value) {
                            $attr_array[] = [
                                'attribute' => $key,
                                'value' => $value,
                                'product_id' => $temp_product->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }
                if (count($attr_array) > 0) {
                    ProductAttribute::insert($attr_array);
                }
                DB::commit();
                if ($request->save != 'Save') {
                    return response()->json(['status' => 1, 'redirect' => route('showNewAdminvariantPricing', ['product' => $product->id,'variant'=> $product->variant])]);
                } else {
                    return response()->json(['status' => 1, 'message' => "Product saved temporarly"]);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
             print_r($e->getMessage());exit;
            $errors['couldnot_save'] = "Could not update product";
            return response()->json(['status' => 0, 'errors' => $errors]);
        }
    }
     //new varinat product registration step-2
    public function showNewAdminvariantPricing(Product $product,Product $variant){
        
        $weight_classes = WeightClass::get();
        $data = [
            'product' => $variant,
            'newvariant' => $product,
            'weight_classes' => $weight_classes,
            'step' => 2
        ];
        return view('admin.products.newproductvariantpricing', $data);
    }
    public function saveNewAdminvariantPricing(Product $product,Request $request)
    {
        $rules = [
            'gst' => "required|numeric",
            'price' => 'required|numeric',
            'mrp' => 'required|numeric',
            'wholesale' => 'required|numeric',
            'wholesale_unit' => 'required|numeric',
        ];
        $messages = [
            'gst.required' => "GST is required",
            'gst.numeric' => "GST must be a number",
            'price.required' => 'Price is required',
            'price.numeric' => "Price must be a number",
            'mrp.required' => 'MRP is required"',
            'mrp.numeric' => "MRP must be a number",
            'wholesale.required' => 'Wholesale price is required',
            'wholesale.numeric' => "Wholesale must be a number",
            'wholesale_unit.required' => 'Min. wholesale unit is required',
            'wholesale_unit.numeric' => "Wholesale unit must be a number",
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);
        if (!$validator->passes()) {
            $messages = $validator->messages();
            $errors = [];
            foreach ($rules as $key => $value) {
                $err = $messages->first($key);
                if ($err) {
                    $errors[$key] = $err;
                }
            }
            return response()->json(['status' => 0, 'errors' => $errors]);
        }

        try {
            DB::beginTransaction();
            $product->gst = $request->gst;
            $product->price = $request->price;
            $product->mrp = $request->mrp;
            $product->wholesale = $request->wholesale;
            $product->wholesale_unit = $request->wholesale_unit;
            
            if (isset($request->sess) && $request->sess) {
                $product->sess = 1;
            } else {
                $product->sess = 0;
            }
            if (isset($request->cod) && $request->cod) {
                $product->cod = 1;
            } else {
                $product->cod = 0;
            }

            if (isset($product->step) && $product->step < 2) {
                $product->step = 2;
            }
            $product->save();
            DB::commit();
            if ($request->save != 'Save') {
                return response()->json(['status' => 1, 'redirect' => route('showNewAdminvariantPackageDetails', ['product' => $product->id,'variant' => $product->variant])]);
            } else {
                return response()->json(['status' => 1, 'message' => "Product pricing details saved temporarly"]);
            }
        } catch (\Exception $e) {
            print_r($e->getMessage());
            exit;
            DB::rollback();
            $errors['couldnot_save'] = "Could not update product pricing details";
            return response()->json(['status' => 0, 'errors' => $errors]);
        }
    }
    //new varinat product registration step-3
    public function showNewAdminvariantPackageDetails(Product $variant,Product $product){
         // print_r($product->toArray());
         $length_classes = LengthClass::get();
         $weight_classes = WeightClass::get();
         $data = [
             'product' => $product,
             'newvariant' => $variant,
             'length_classes' => $length_classes,
             'weight_classes' => $weight_classes,
             'step' => 3
         ];
         return view('admin.products.newproductvariantpackage', $data);

    }
    public function saveNewAdminvariantPackageDetails(Product $product, Request $request)
    {
        // print_r($request->all());
        $rules = [
            'weight' => "required|numeric",
            'weight_unit' => 'required',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'dimensions_unit' => 'required',
            'shipping_processing_time' => 'required|numeric',
        ];
        $messages = [
            'weight.required' => "Weight is required",
            'weight.numeric' => "Weight must be a number",
            'weight_unit.required' => 'Weight unit is required',
            'length.required' => 'Length is required',
            'length.numeric' => 'Length must be a number',
            'width.required' => 'Width is required',
            'width.numeric' => 'Width must be a number',
            'height.required' => 'Height is required',
            'height.numeric' => 'Height must be a number',
            'dimensions_unit.required' => 'Dimensions unit is required',
            'shipping_processing_time.required' => 'Shipping processing time is required',
            'shipping_processing_time.numeric' => 'Shipping processing time must be a number',
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);
        if (!$validator->passes()) {
            $messages = $validator->messages();
            $errors = [];
            foreach ($rules as $key => $value) {
                $err = $messages->first($key);
                if ($err) {
                    $errors[$key] = $err;
                }
            }
            return response()->json(['status' => 0, 'errors' => $errors]);
        }
        try {
            DB::beginTransaction();
            $product->weight = $request->weight;
            $product->weight_class_id = $request->weight_unit;
            $product->length = $request->length;
            $product->width = $request->width;
            $product->height = $request->height;
            $product->length_class_id = $request->dimensions_unit;
            $product->shipping_processing_time = $request->shipping_processing_time;

            if (isset($request->free_delivery) && $request->free_delivery) {
                $product->free_delivery = 1;
            } else {
                $product->free_delivery = 0;
            }

            if (isset($product->step) && $product->step < 3) {
                $product->step = 3;
            }
            $product->save();
            DB::commit();
            if ($request->save != 'Save') {
                return response()->json(['status' => 1, 'redirect' => route('showNewAdminvariantImages',['product' => $product->id,'variant' => $product->variant])]);
            } else {
                return response()->json(['status' => 2, 'message' => "Product package details saved temporarly"]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            print_r($e->getMessage()); 
            $errors['couldnot_save'] = "Could not update product package details";
            return response()->json(['status' => 0, 'errors' => $errors]);
        }
    }
       //new varinat product registration step-4
    public function showNewAdminvariantImages(Product $product,Product $variant,Request $request)
    {
        // if ($variant->step > 3) {
        //         $product = $variant;
        //     }
            
            $query = ProductImage::query();
            $query->where('product_id', '=', $product->id);
            $query->orderBy('created_at', 'desc')->limit(20);
            $product_images =  $query->get();
            $data = [
                'product_images' => $product_images,
                'product' => $variant,
                'variant' => $product,
                'step' => 4
            ];
            return view('admin.products.newproductvariantimages', $data);
    }
    public function saveNewAdminvariantImages(Product $variant,Product $product,Request $request)
    {
        $resp = [];
        if (request()->image) {
            try {
                $imageName = time() . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('images/products/' . $variant->id . '/'), $imageName);
             
                $product_image =array(
                    'product_id' =>  $variant->id,
                    'image' =>  'images/products/' . $variant->id . '/' . $imageName,
                    'created_at' => now(),
                    'updated_at' => now(),
                );
                ProductImage::insert($product_image);
                if (isset($variant->step) && $variant->step < 4) {
                    $variant->step = 4;
                    $variant->save();
                }

                $redirect = 0;

                if ($request->save == 'Save') {
                    $redirect = 1;
                }

                $resp = [
                    'status' => true,
                    'message' => 'Product image Uploaded successfully',
                    'redirect' => $redirect,
                    'product' => $variant->id,
                    'variant' => $product->id
                ];
            } catch (\Exception $e) {
                 print_r($e->getMessage());exit;
                $errors['couldnot_save'] = "Could not upload product image";
                $resp = [
                    'status' => false,
                    'errors' => $errors
                ];
            }
        } else {
            $errors['couldnot_save'] = "Could not upload product image";
            $resp = [
                'status' => false,
                'errors' => $errors
            ];
        }
        return response()->json($resp);
    }
    public function saveNewAdminvariantImageOld(Product $product, Product $variant, Request $request)
    {
        // print_r($request->all());
        $images = $request->images;
        if (isset($images) &&  count($images) > 0) {
            $images = ProductImage::whereIn('id', $images)->get()->toArray();
            if (count($images) > 0) {
                foreach ($images as $key => $image) {
                    $images[$key]['product_id'] = $product->id;
                    unset($images[$key]['id']);
                }
                ProductImage::insert($images);
                if (isset($porduct->step) && $product->step < 4) {
                    $porduct->step = 4;
                    $product->save();
                }
            }
        }
        return response()->json(['status' => true, 'redirect' => 1]);
    }
    public function deleteNewAdminvariantImages(ProductImage $image, Request $request)
    {
        if ($image) {
            $id = $image->product_id;
            $variant = Product::find($id);
            $path = public_path() . "/" . $image->image;
            if(file_exists($path)){
                unlink($path);
            }
            $image->delete();
            return redirect()->route('showNewAdminvariantImages', ['product' => $id,'variant' => $variant->variant]);
        } else {
            return redirect()->route('vendorDashboard');
        }
    }
    //new varinat product registration step-5
    //adding additional information to the variant product
    public function showNewAdminvariantAddinfo(Product $product, Product $variant, Request $request)
    {
        $query = ProductAdditionalInfo::query();
        $query->where('product_id', '=', $product->id);
        $query->orderBy('created_at', 'desc')->limit(20);
        $additional_fields =  $query->get();
        $data = [
            'product' => $product,
            'variant' => $variant,
            'additional_fields' => $additional_fields,
            'step' => 5
        ];
        return view('admin.products.newproductvariantadditional_info', $data);
    }
    //saving additional information to the variant product
    public function saveNewAdminvariantAddinfo(Product $product,Request $request)
    {
        $rules = [
            'name'          => "required",
            'field_details' => 'required',
        ];
        $messages = [
            'name.required'             => "Filed Name is required",
            'filed_details.required'    => "Field Details is required",
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);
        if (!$validator->passes()) {
            $messages = $validator->messages();
            $errors = [];
            foreach ($rules as $key => $value) {
                $err = $messages->first($key);
                if ($err) {
                    $errors[$key] = $err;
                }
            }
            return response()->json(['status' => 0, 'errors' => $errors]);
        }
        $attr_array[] = [
            'name' =>  $request->name,
            'value' => $request->field_details,
            'product_id' => $product->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (count($attr_array) > 0) {
            ProductAdditionalInfo::insert($attr_array);
        }

        if (isset($product->step) && $product->step < 5) {
            $product->step = 5;
            $product->save();
        }

        if ($request->save != "Save") {
            return response()->json(['status' => 1, 'redirect' => route('showNewAdminvariantFaq', ['product' => $product->id, 'variant' => $product->variant])]);
        } else {
            return response()->json(['status' => 0, 'message' => "Product addditional information details saved temporarly"]);
        }
    }
    public function deleteAdminvariantAddInfo(ProductAdditionalInfo $additionalinfo, Request $request)
    {
       

        if ($additionalinfo) {
            
            $id = $additionalinfo->product_id;
            $query = Product::query();
            $query->where('id', '=', $id);
            $product =  $query->get();
            $additionalinfo->delete();
            return redirect()->route('showNewAdminvariantAddinfo', ['product' => $product[0]->id,'variant' => $product[0]->variant]);
    
        } else {
            return redirect()->route('dashboard');
        }
    }
    //new varinat product registration step-6
    public function showNewAdminvariantFaq(Product $product,Product $variant, Request $request)
    {
        $query = ProductFaq::query();
        $query->where('product_id', '=', $product->id);
        $query->orderBy('created_at', 'desc')->limit(20);
        $faq   =  $query->get();
        $data  = [
            'product' => $product,
            'variant' => $variant,
            'faq' => $faq,
            'step' => 6
        ];

        return view('admin.products.newproductvariantfaq', $data);
    }
    // save FAQ of variant product
    public function saveNewAdminvariantFaq(Product $product, Request $request)
    {
        $rules = [
            'question'      => "required",
            'field_answer'  => 'required',
        ];
        $messages = [
            'question.required'     => "Question is required",
            'filed_answer.required' => "Answer is required",
        ];

        $validator = Validator::make(request()->all(), $rules, $messages);
        if (!$validator->passes()) {
            $messages = $validator->messages();
            $errors = [];
            foreach ($rules as $key => $value) {
                $err = $messages->first($key);
                if ($err) {
                    $errors[$key] = $err;
                }
            }
            return response()->json(['status' => 0, 'errors' => $errors]);
        }
        $attr_array[] = [
            'question' =>  $request->question,
            'answer' => $request->field_answer,
            'product_id' => $product->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (count($attr_array) > 0) {
            ProductFaq::insert($attr_array);
        }

        if (isset($product->step) && $product->step < 6) {
            $product->completed_status = 1;
            $product->step = 6;
            $product->save();
        }
        
        if ($request->save != "Save") {
            return response()->json(['status' => 1, 'redirect' => route('showNewAdminvariantProductListApproval', ['product'=>$product->id,'variant'=>$product->variant])]);
        } else {
            return response()->json(['status' => 0, 'message' => "Product addditional information details saved temporarly"]);
        }
    }
    public function deleteAdminvariantFaq(ProductFaq $faqs, Request $request)
    {
        $faq =ProductFaq::find($request->faq);
        if ($faq) {
            $id = $faq->product_id;
            $product = Product::find($id);
            $faq->delete();
            return redirect()->route('showNewAdminvariantFaq', ['product' => $faq->product_id, 'variant' => $product->variant]);
        } else {
            return redirect()->route('vendorDashboard');
        }
    }
    //new variant porduct step 7
    public function showNewAdminvariantProductListApproval(Product $product, Request $request){
        $data = [
            'product' => $product,
            'step' => 7
        ];

        return view('admin.products.newproductvariantapproval', $data);
    }
    public function saveNewAdminvariantProductListApproval(Product $product, Request $request){
        if($request->approved == 1){
            try {
                DB::beginTransaction(); 
                //insert main table

             
                $product->admin_comments = $request->admin_comments;
                $product->completed_status = 1;
                $product->approved = 1;
                $product->step = 7;
                $product->save();
                DB::commit();
                return response()->json(['status' => 1, 'message' => "Inserted"]);
            } catch (\Exception $e) {
                print_r($e->getMessage());
                DB::rollback();
                $errors['couldnot_save'] = "Could not update product pricing details";
                return response()->json(['status' => 0, 'errors' => $errors]);
            }
        }else{
            $product->approved = $request->approved;
            $product->admin_comments = $request->admin_comments;
            $product->save();
            return response()->json(['status' => 1, 'redirect' => 1]);
        }
    }



    
    
}
