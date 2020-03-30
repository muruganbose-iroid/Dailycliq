<?php
$active = "production1";

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Route::domain('{account}.myapp.com')->group(function () {
//     Route::get('user/{id}', function ($account, $id) {
//         //
//     });
// });

$vendor_routes = function () {
    Route::group(['prefix' => '/', 'middleware' => 'redirect_to_admin'], function () {
        Route::get('/', 'Web\HomeController@showHome')->name('showHome');
    });
    //admin login
    // Route::get('/', 'Vendor\LoginController@showLogin');
    Route::get('/login', 'Vendor\LoginController@showLogin')->name('showLogin');
    Route::post('/', 'Vendor\LoginController@loginVendor');
    Route::post('/loginvendor', 'Vendor\LoginController@loginVendor')->name('loginVendor');
    Route::post('/vendorforgotpassword', 'Vendor\LoginController@vendorForgotPassword')->name('vendorForgotPassword');
    Route::get('/resetlink/{email}/{token}/', 'Vendor\LoginController@resetLink')->name('resetLink');
    Route::post('/vendorchangepassword', 'Vendor\LoginController@vendorChangePassword')->name('vendorChangePassword');
    Route::get('/signup-vendor', 'Vendor\LoginController@showSignUp')->name('showSignUp');
    Route::post('/vendor-signup', 'Vendor\LoginController@signUpVendor')->name('signUpVendor');
    Route::get('/vendor/terms-condition', 'Vendor\LoginController@termsConditions')->name('termsConditions');

    Route::post('/sendotp', 'Vendor\LoginController@sendOtp')->name('sendOtp');
    Route::post('/verifyotp', 'Vendor\LoginController@verifyOtp')->name('verifyOtp');

    //vendor login
    Route::group(['middleware' => ['auth:vendor', 'redirect_to_register']], function () {
        Route::get('/my-board', 'Vendor\DashboardController@vendorDashboard')->name('vendorDashboard');
        Route::get('/registration', 'Vendor\DashboardController@showRegistration')->name('showRegistration');
        Route::post('/add', 'Vendor\DashboardController@addRegistration')->name('addRegistration');
        Route::post('/get-districts', 'Vendor\DashboardController@getDistrict')->name('getDistrict');
        Route::get('/vendorlogout', 'Vendor\LoginController@vendorLogout')->name('vendorLogout');
        Route::get('/vendor-profile', 'Vendor\ProfileController@showVendorProfile')->name('showVendorProfile');
        Route::post('/vendors/update-vendor-profile', 'Vendor\ProfileController@updateVendorProfile')->name('updateVendorProfile');
        //catelog
        Route::get('/catelogs', 'Vendor\ProductController@showCatelog')->name('showCatelog');
        Route::get('/catelogs/delete/approvedproducts/{product}', 'Vendor\ProductController@deleteVendorApprovedProduct')->name('deleteVendorApprovedProduct');
        Route::get('/catelogs/categories', 'Vendor\ProductController@showVendorCategories')->name('showVendorCategories');

        Route::get('/catelogs/new', 'Vendor\ProductController@showNewCatelog')->name('showNewCatelog');
        //variant
        Route::get('/catelogs/new/variant/{product}', 'Vendor\ProductController@showNewCatelognewvarient')->name('showNewCatelognewvarient');
        
        Route::post('/catelogs/new', 'Vendor\ProductController@saveNewCatelog')->name('saveNewCatelog');
        
        Route::post('/catelogs/newvariant', 'Vendor\ProductController@saveNewvariantCatelog')->name('saveNewvariantCatelog');

        Route::get('/catelogs/new/pricing/{product}', 'Vendor\ProductController@showNewPricingCatelog')->name('showNewPricingCatelog');

        Route::get('/catelogs/new/variantpricing/{product}/{variant}', 'Vendor\ProductController@showNewvarinatPricingCatelog')->name('showNewvarinatPricingCatelog');
        
        Route::post('/catelogs/new/pricing/{product}', 'Vendor\ProductController@saveNewCatelogPricing')->name('saveNewCatelogPricing');
        Route::post('/catelogs/new/pricingvariant/{product}/{variant}', 'Vendor\ProductController@saveNewvariantCatelogPricing')->name('saveNewvariantCatelogPricing');

        Route::get('/catelogs/new/package/{product}', 'Vendor\ProductController@showNewCatelogPackageDetails')->name('showNewCatelogPackageDetails');
        Route::get('/catelogs/new/packagevariant/{product}/{variant}', 'Vendor\ProductController@showNewvariantCatelogPackageDetails')->name('showNewvariantCatelogPackageDetails');
        Route::post('/catelogs/new/package/{product}', 'Vendor\ProductController@saveNewCatelogPackageDetails')->name('saveNewCatelogPackageDetails');
        Route::post('/catelogs/new/variantpackage/{product}/{variant}', 'Vendor\ProductController@saveNewvariantCatelogPackageDetails')->name('saveNewvariantCatelogPackageDetails');
        Route::get('/catelogs/new/images/{product}', 'Vendor\ProductController@showNewCatelogImages')->name('showNewCatelogImages');
        Route::get('/catelogs/new/variantimages/{product}/{variant}', 'Vendor\ProductController@showNewvariantCatelogImages')->name('showNewvariantCatelogImages');
        Route::post('/catelogs/new/images/{product}', 'Vendor\ProductController@saveNewCatelogImage')->name('saveNewCatelogImage');
        Route::post('/catelogs/new/imagesvariant/{product}/{variant}', 'Vendor\ProductController@saveNewvariantCatelogImage')->name('saveNewvariantCatelogImage');

        Route::post('/catelogs/new/imagesvariant/old/{product}/{variant}', 'Vendor\ProductController@saveNewvariantCatelogImageOld')->name('saveNewvariantCatelogImageOld');
        
        Route::get('/catelogs/delete/image/{image}', 'Vendor\ProductController@deleteCatelogueImage')->name('deleteCatelogueImage');
        
        Route::get('/catelogs/new/additionalinfo/{product}', 'Vendor\ProductController@showNewCatelogAdditionalInfo')->name('showNewCatelogAdditionalInfo');
        Route::get('/catelogs/new/variantadditionalinfo/{product}/{variant}', 'Vendor\ProductController@showNewvariantCatelogAdditionalInfo')->name('showNewvariantCatelogAdditionalInfo');
        Route::post('/catelogs/new/additionalinfo/{product}', 'Vendor\ProductController@saveNewCatelogAdditionalInfo')->name('saveNewCatelogAdditionalInfo');
        Route::post('/catelogs/new/additionalinfovariant/{product}/{variant}', 'Vendor\ProductController@saveNewvariantCatelogAdditionalInfo')->name('saveNewvariantCatelogAdditionalInfo');
        Route::post('/catelogs/new/additionalinfovariant/old/{product}/{variant}', 'Vendor\ProductController@saveNewvariantCatelogAdditionalInfoOld')->name('saveNewvariantCatelogAdditionalInfoOld');
        Route::get('/catelogs/delete/additionalinfo/{additionalinfo}', 'Vendor\ProductController@deleteCatelogAdditionalInfo')->name('deleteCatelogAdditionalInfo');
        Route::get('/catelogs/variant/delete/additionalinfo/{additionalinfo}', 'Vendor\ProductController@deleteCatelogAdditionalInfoVariant')->name('deleteCatelogAdditionalInfoVariant');
         
        Route::get('/catelogs/new/faq/{product}', 'Vendor\ProductController@showNewCatelogueFaq')->name('showNewCatelogueFaq');
        Route::get('/catelogs/new/variantfaq/{product}/{variant}', 'Vendor\ProductController@showNewvariantCatelogueFaq')->name('showNewvariantCatelogueFaq');
        Route::post('/catelogs/new/faq/{product}', 'Vendor\ProductController@saveNewCatelogueFaq')->name('saveNewCatelogueFaq');
        Route::post('/catelogs/new/faqvariant/{product}/{variant}', 'Vendor\ProductController@saveNewvariantCatelogueFaq')->name('saveNewvariantCatelogueFaq');
        Route::post('/catelogs/new/faqvariant/old/{product}/{variant}', 'Vendor\ProductController@saveNewvariantCatelogueFaqOld')->name('saveNewvariantCatelogueFaqOld');
        
        Route::get('/catelogs/delete/faq/{faq}', 'Vendor\ProductController@deleteCatelogueFaq')->name('deleteCatelogueFaq');
        Route::get('/catelogs/variant/delete/faq/{faq}', 'Vendor\ProductController@deleteCatelogueFaqVariant')->name('deleteCatelogueFaqVariant');
        

        Route::get('/catelog/delete/{product}','Vendor\ProductController@deleteCatelog')->name('deleteCatelog');

        //categories
        Route::get('/subcategories', 'Vendor\CategoryController@getSubCategories')->name('getSubCategories');
        //product search
        Route::get('/search/products', 'Vendor\ProductController@searchProduct')->name('searchProduct');
        //vendor products
        Route::get('/products/pending', 'Vendor\ProductController@showVendorProductList')->name('showVendorProductList');

        //Inventory
        Route::get('/inventory/product/{product}', 'Vendor\InventoryController@showProduct')->name('showProduct');
        Route::get('/inventory', 'Vendor\InventoryController@showInventory')->name('showInventory');
        Route::post('/inventory/product/save/{product}/{vendor}', 'Vendor\InventoryController@saveInventory')->name('saveInventory');
        
        //Flash Sale
        Route::get('/vendor/flashsale/', 'Vendor\FlashsaleProductsController@flashSale')->name('flashSale');
        Route::get('/vendor/flashsale/show/{flashsale}', 'Vendor\FlashsaleProductsController@showFlashsale')->name('showFlashsale');
        Route::post('/vendor/flashsale/save/{flashsale}', 'Vendor\FlashsaleProductsController@saveFlashsaleProduct')->name('saveFlashsaleProduct');
        Route::get('/vendor/flashsale/delete/{flashsale}/{product}', 'Vendor\FlashsaleProductsController@deleteFlashsaleProduct')->name('deleteFlashsaleProduct');

        //Deal Of the Day
        Route::get('/vendor/dealoftheday/', 'Vendor\DealOftheDayController@showDealOftheDay')->name('showDealOftheDay');
        Route::post('/vendor/dealoftheday/save/', 'Vendor\DealOftheDayController@saveDealOftheDayProduct')->name('saveDealOftheDayProduct');
        Route::get('/vendor/delete/dealoftheday/{dealoftheday}/{product}', 'Vendor\DealOftheDayController@deleteDealOftheDayProduct')->name('deleteDealOftheDayProduct');

        //Orders
        Route::get('/vendor/order/show/', 'Vendor\OrderController@showOrder')->name('showOrder');
        Route::post('/vendor/order/change/', 'Vendor\OrderController@orderChangeStatus')->name('orderChangeStatus');
        Route::get('/vendor/order/show/product/{order}', 'Vendor\OrderController@showOrderedProduct')->name('showOrderedProduct');
        Route::get('/vendor/order/show/customer/{order}', 'Vendor\OrderController@showCustomerDetails')->name('showCustomerDetails');
        Route::get('/vendor/order/sort/', 'Vendor\OrderController@SortOrder')->name('SortOrder'); 
        Route::get('/brand', 'Vendor\BrandController@searchBrand')->name('searchBrand');
        Route::get('/id', 'Vendor\BrandController@searchBrandById')->name('searchBrandById');

        //Reports
        Route::get('/vendor/report/sales/', 'Vendor\ReportController@salesReport')->name('salesReport');
        Route::get('/vendor/report/stock/', 'Vendor\ReportController@stockReport')->name('stockReport');
        Route::get('/vendor/report/order/', 'Vendor\ReportController@orderReport')->name('orderReport');

    }); 
};

$admin_routes = function () {
    //admin login
    Route::get('/', 'LoginController@showLogin');
    Route::get('/login', 'LoginController@showLogin')->name('showAdminLogin');
    Route::post('/', 'LoginController@login');
    Route::post('/login', 'LoginController@login')->name('login');
    Route::post('/forgotpassword', 'LoginController@forgotPassword')->name('forgotPassword');
    //after login
    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
        Route::get('/logout', 'LoginController@logout')->name('logout');

        //profile
        Route::get('/profile', 'ProfileController@showProfile')->name('showProfile');
        Route::post('/profile/update', 'ProfileController@updateProfile')->name('updateProfile');
        Route::post('/profile/password', 'ProfileController@updateProfilePassword')->name('updateProfilePassword');

        //vendors
        Route::get('/vendors', 'VendorController@showVendors')->name('showVendors');
        Route::get('/vendors/add', 'VendorController@showNewVendors')->name('showNewVendors');
        Route::get('/vendors/edit/{code}', 'VendorController@showEditVendor')->name('showEditVendor');
        Route::post('/vendors/store', 'VendorController@addVendor')->name('addVendor');
        Route::post('/vendors/update', 'VendorController@updateVendor')->name('updateVendor');
        Route::post('/vendors/delete', 'VendorController@deleteVendor')->name('deleteVendor');
        Route::post('/vendors/block', 'VendorController@blockVendor')->name('blockVendor');
        Route::post('/vendors/send-invitation', 'VendorController@sendInvite')->name('sendInvite');
        Route::get('/vendors/approve/{vendor}', 'VendorController@approveVendor')->name('approveVendor');

        //products
        Route::get('/products', 'ProductController@showProducts')->name('showProducts');
        Route::get('/product/new', 'ProductController@showNewProduct')->name('showNewProduct');
        Route::get('/product/edit/{id}', 'ProductController@editProduct')->name('editProduct');
        Route::get('/product/images/{id}', 'ProductController@showProductImages')->name('showProductImages');
        Route::get('/product/images/featured/{id}', 'ProductController@makeFeaturedImage')->name('makeFeaturedImage');
        Route::get('/product/images/delete/{id}', 'ProductController@deleteFeaturedImage')->name('deleteFeaturedImage');

        Route::post('/product/images/{id}', 'ProductController@addProductImage')->name('addProductImage');
        Route::post('/product/new', 'ProductController@newProduct')->name('newProduct');
        Route::post('/product/edit/{id}', 'ProductController@updateProduct')->name('updateProduct');
        Route::get('/product/delete/{id}', 'ProductController@deleteProduct')->name('deleteProduct');


        // Murugan Start 

        //Product Update on Store 99
        
        Route::get('/store99products', 'ProductController@store99products')->name('store99products');
        Route::get('/product/store99/{id}', 'ProductController@store99')->name('store99');
        Route::get('/product/unstore99/{id}', 'ProductController@unstore99')->name('unstore99');

        //
        

        //product categories
        Route::get('/categories', 'CategoryController@showCategories')->name('showCategories');
        Route::get('/category/edit/{id}', 'CategoryController@editCategory')->name('editCategory');
        Route::get('/category/new', 'CategoryController@showNewCategory')->name('showNewCategory');
        Route::get('/category/subcategories/{id}', 'CategoryController@showSubCategories')->name('showSubCategories');
        Route::get('/category/subcategories/{id}/new', 'CategoryController@showNewSubCategory')->name('showNewSubCategories');
        Route::get('/category/edit/{main}/{id}', 'CategoryController@editSubCategory')->name('editSubCategory');
        Route::post('/category/update', 'CategoryController@updateCategory')->name('updateCategory');
        Route::post('/category/subcategories/{id}/new', 'CategoryController@newSubCategory')->name('newSubCategory');
        Route::post('/category/new', 'CategoryController@newCategory')->name('newCategory');
        Route::post('/category/block', 'CategoryController@blockCategory')->name('blockCategory');

        //attributes
        Route::get('/attributes/{category}', 'AttributeController@listAttributes')->name('listAttributes');
        Route::get('/attributes/new/{category}', 'AttributeController@showNewAttribute')->name('showNewAttribute');
        Route::get('/attributes/edit/{attribute}', 'AttributeController@showEditAttribute')->name('showEditAttribute');
        Route::get('/attributes/values/{attribute}', 'AttributeController@getAttributeValues')->name('getAttributeValues');
        Route::post('/attributes/categoryattribute', 'AttributeController@saveCategoryAttribute')->name('saveCategoryAttribute');
        Route::post('/attributes/updatesearchable', 'AttributeController@updateSearchable')->name('updateSearchable');
        Route::post('/categoryattributes/update', 'AttributeController@updateCategoryAttribute')->name('updateCategoryAttribute');
        Route::post('/categoryattributes/delete', 'AttributeController@deleteAttribute')->name('deleteAttribute');
        Route::get('/search/test', 'AttributeController@testSearch')->name('testSearch');

        //manufacturers
        Route::get('/manufacturers', 'ManufacturerController@showManufacturers')->name('showManufacturers');
        Route::get('/manufacturer/new', 'ManufacturerController@showNewManufacturer')->name('showNewManufacturer');
        Route::get('/manufacturer/edit/{id}', 'ManufacturerController@editManufacturer')->name('editManufacturer');
        Route::post('/manufacturer/new', 'ManufacturerController@newManufacturer')->name('newManufacturer');
        Route::post('/manufacturer/edit/{id}', 'ManufacturerController@updateManufacturer')->name('updateManufacturer');

        //brands
        Route::get('/brands', 'BrandController@showBrands')->name('showBrands');
        Route::get('/brands/new', 'BrandController@showNewBrand')->name('showNewBrand');
        Route::get('/brands/edit/{id}', 'BrandController@editBrand')->name('editBrand');
        Route::post('/brands/new', 'BrandController@newBrand')->name('newBrand');

        Route::post('/brands/edit/{id}', 'BrandController@updateBrand')->name('updateBrand');
        Route::post('/brands/featured', 'BrandController@brandAddFeatured')->name('brandAddFeatured');
        


        //products new
        
        Route::get('/products/pending/{product}', 'ProductController@showPendingProductAdmin')->name('showPendingProductAdmin');
        Route::post('/products/pending/{product}', 'ProductController@savePendingProductAdmin')->name('savePendingProductAdmin');

        Route::get('/products/pending/pricing/{product}', 'ProductController@showPendingProductPricing')->name('showPendingProductPricing');
        Route::post('/products/pending/pricing/{product}', 'ProductController@savePendingProductAdminPricing')->name('savePendingProductAdminPricing');

        Route::get('/products/pending/package/{product}', 'ProductController@showPendingProductAdminPackageDetails')->name('showPendingProductAdminPackageDetails');
        Route::post('/products/pending/package/{product}', 'ProductController@savePendingProductAdminPackageDetails')->name('savePendingProductAdminPackageDetails');
        
        Route::get('/products/pending/images/{product}', 'ProductController@showPendingProductAdminImages')->name('showPendingProductAdminImages');
        Route::post('/products/pending/images/{product}', 'ProductController@savePendingProductAdminImage')->name('savePendingProductAdminImage');
        Route::get('/products/delete/image/{image}', 'ProductController@deletePendingProductImage')->name('deletePendingProductImage');
        
        Route::get('/products/pending/additionalinfo/{product}', 'ProductController@showPendingProductAdminAdditionalInfo')->name('showPendingProductAdminAdditionalInfo');
        Route::post('/products/pending/additionalinfo/{product}', 'ProductController@savePendingProductAdminAdditionalInfo')->name('savePendingProductAdminAdditionalInfo');
       /// Route::get('/products/delete/additionalinfo/{additionalinfo}', 'ProductController@deleteCatelogAdditionalInfo')->name('deleteCatelogAdditionalInfo');
         
        Route::get('/products/pending/faq/{product}', 'ProductController@showPendingProductAdminFaq')->name('showPendingProductAdminFaq');
        Route::post('/products/pending/faq/{product}', 'ProductController@savePendingProductAdminFaq')->name('savePendingProductAdminFaq');
        Route::get('/products/delete/faq/{faq}', 'ProductController@deletePendingProductFaq')->name('deletePendingProductFaq');

        //pending products
        Route::get('/products/pending', 'ProductController@showAdminProductList')->name('showAdminProductList');

        Route::get('/products/pending/approve/{product}', 'ProductController@showPendingProductListApproval')->name('showPendingProductListApproval');
        Route::post('/products/pending/approve/{product}', 'ProductController@savePendingProductListApproval')->name('savePendingProductListApproval');

         //variant
         Route::get('/products/new/variant/{product}', 'ProductController@showNewAdminvariant')->name('showNewAdminvariant');
         Route::post('/products/new/variant/save/{product}', 'ProductController@saveNewAdminvariant')->name('saveNewAdminvariant');

         Route::get('/products/new/variant/pricing/{product}/{variant}', 'ProductController@showNewAdminvariantPricing')->name('showNewAdminvariantPricing');
         Route::post('/products/new/variant/pricing/save/{product}', 'ProductController@saveNewAdminvariantPricing')->name('saveNewAdminvariantPricing');

         Route::get('/products/new/variant/package/{product}/{variant}', 'ProductController@showNewAdminvariantPackageDetails')->name('showNewAdminvariantPackageDetails');
         Route::post('/products/new/variant/package/save/{product}', 'ProductController@saveNewAdminvariantPackageDetails')->name('saveNewAdminvariantPackageDetails');

         Route::get('/products/new/variant/image/{product}/{variant}', 'ProductController@showNewAdminvariantImages')->name('showNewAdminvariantImages');
         Route::post('/products/new/variant/image/save/{product}/{variant}', 'ProductController@saveNewAdminvariantImages')->name('saveNewAdminvariantImages');
         Route::get('/products/new/variant/delete/image/{image}', 'ProductController@deleteNewAdminvariantImages')->name('deleteNewAdminvariantImages');
         Route::post('/products/new/variant/save/old/{product}/{variant}', 'ProductController@saveNewAdminvariantImageOld')->name('saveNewAdminvariantImageOld');

         Route::get('/products/new/variant/addinfo/{product}/{variant}', 'ProductController@showNewAdminvariantAddinfo')->name('showNewAdminvariantAddinfo');
         Route::post('/products/new/variant/addinfo/save/{product}', 'ProductController@saveNewAdminvariantAddinfo')->name('saveNewAdminvariantAddinfo');
         Route::get('/products/new/variant/delete/addinfo/{additionalinfo}', 'ProductController@deleteAdminvariantAddInfo')->name('deleteAdminvariantAddInfo');
         
         Route::get('/products/new/variant/faq/{product}/{variant}', 'ProductController@showNewAdminvariantFaq')->name('showNewAdminvariantFaq');
         Route::post('/products/new/variant/faq/save/{product}', 'ProductController@saveNewAdminvariantFaq')->name('saveNewAdminvariantFaq');
         Route::get('/products/new/variant/delete/faq/{faq}', 'ProductController@deleteAdminvariantFaq')->name('deleteAdminvariantFaq');

         Route::get('/products/new/variant/approve/{product}/{variant}', 'ProductController@showNewAdminvariantProductListApproval')->name('showNewAdminvariantProductListApproval');
         Route::post('/products/new/variant/approve/save/{product}/', 'ProductController@saveNewAdminvariantProductListApproval')->name('saveNewAdminvariantProductListApproval');

         
         Route::get('/brand', 'BrandController@searchBrandAdmin')->name('searchBrandAdmin');
         Route::get('/id', 'BrandController@searchBrandByIdAdmin')->name('searchBrandByIdAdmin');
         
    });
};

if ($active == "production2") {
    Route::group(['domain' => 'admin.dailycliq.com'], $admin_routes);
    Route::group(['domain' => 'vendor.dailycliq.com'], $vendor_routes);
    Route::group(['domain' => 'www.admin.dailycliq.com'], $admin_routes);
    Route::group(['domain' => 'www.vendor.dailycliq.com'], $vendor_routes);
}else{    
    Route::group(['prefix' => 'vendor'], $vendor_routes);
    Route::group(['prefix' => 'admin'], $admin_routes);
}
