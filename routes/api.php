<?php

use Illuminate\Http\Request;
// Product Related Data
// Pim Access
Route::get('categories', 'Api\CategoryController@defaultCategories');
// Item
Route::post('create/item', 'Api\ItemController@createItem');
Route::post('item/status', 'Api\ItemController@statusChange');

Route::prefix('v1')->group(function () {
    Route::group(['namespace' => 'Api\Buyer', 'prefix' => 'buyer'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('social/login', 'AuthController@socialLogin');
        Route::post('register', 'AuthController@register');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');

        Route::post('profile-update', 'BuyerController@profileUpdate');
        Route::post('change-password', 'BuyerController@changePassword');
        Route::post('checkout', 'BuyerController@checkout');
        Route::post('sendOrderEmail', 'BuyerController@sendOrderEmail');
        Route::post('checkOrder', 'BuyerController@checkOrder');
        Route::post('apply-coupon', 'BuyerController@applyCoupon')->middleware('auth:api');
        Route::post('remove-coupon', 'BuyerController@removeCoupon')->middleware('auth:api');
        Route::get('singleOrder', 'BuyerController@singleOrder');

        // Send reset password mail
        Route::post('reset-password', 'PassworResetController@sendPasswordResetLink');
        // handle reset password form process
        Route::post('new-password', 'NewPasswordController@callResetPassword');
    });

    // User Auth
    Route::post('register', 'Api\UserController@register');
    Route::post('login', 'Api\UserController@login');

    // Product Related Data
    Route::get('categories', 'Api\CategoryController@index');

    // Products
    Route::get('/single-product/{slug}', 'Api\HomeController@ProductSingleInfo');
    Route::get('/product-view/{slug}', 'Api\HomeController@productView');
    Route::get('/recently-viewed', 'Api\HomeController@recentlyViewed');
    Route::get('items', 'Api\ItemController@index');
    Route::post('/search_items', 'Api\ItemController@searchItems');
    Route::post('/search_items/{parent}', 'Api\ItemController@searchItems');
    Route::post('/search_items/{parent}/{second}', 'Api\ItemController@searchItems');
    Route::post('/search_items/{parent}/{second}/{third}', 'Api\ItemController@searchItems');
    Route::post('/search_in_site', 'Api\ItemController@searchInSite');


    // Blogs
    Route::get('/blogs', 'Api\BlogController@index');
    Route::get('/blogs/sidebar', 'Api\BlogController@sidebarContent');
    Route::get('/blogs/{slug}', 'Api\BlogController@index');
    Route::get('/blog-details/{slug}', 'Api\BlogController@blogSingle');
    Route::post('/blog/comment-post', 'Api\BlogController@commentPost');

    // sections
    Route::get('/product-values', 'Api\HomeController@productValues');
    Route::get('/product-brands', 'Api\HomeController@productBrands');


    Route::get('/faq/content', 'Api\HomeController@faqContent');

    /* -------------------------- Header Default Route -------------------------- */
    Route::get('/homepage-content', 'Api\HomeController@HomePageDefaultContent');
    Route::get('/headerdefaultcontent', 'Api\HomeController@HeaderDefaultContent');
    Route::get('/default-footer', 'Api\HomeController@FooterDefaultContent');
    Route::get('static-page/{id}', 'Api\HomeController@StaticPage');
    Route::get('home-page', 'Api\HomeController@HomePage');
    Route::get('/get/master-color', 'Api\HomeController@MasterColors');
    Route::get('/get/master-color-item/{id}', 'Api\HomeController@MasterColorsItems');
    Route::get('/get/values', 'Api\HomeController@getValues');
    Route::get('/get/return-shipping', 'Api\HomeController@getReturnsAndShipping');
    Route::get('/get/feature/widget', 'Api\HomeController@getFeatureWidget');
    Route::get('/get/privacy-notice', 'Api\HomeController@privacyNotice');
    Route::get('/get/news-letter', 'Api\HomeController@newsLetter');
    Route::get('/get/blog/banner', 'Api\HomeController@getBlogBanner');
    Route::get('/get/sliders', 'Api\HomeController@getSliders');
    Route::get('/get/instagram', 'Api\HomeController@getInstagramFeeds');
    Route::get('/new-in', 'Api\HomeController@homenewin');
    Route::post('/add-newsletter', 'Api\NewsletterController@addNewsletter');
    Route::post('/newsletter-update','Api\NewsletterController@newsletterUpdate')->middleware('auth:api');
    // Route::get('/category/{category}', 'Api\HomeController@CategoryPage');
    Route::post('/contact/form', 'Api\HomeController@submitContactForm');

    Route::post('/add-to-cart', 'Api\CartController@addToCart');
    Route::post('/product_image', 'Api\ItemController@getimages');
    Route::get('/check-email', 'Api\UserController@checkEmail');
    Route::get('/guest-cart/remove/{id}', 'Api\CartController@guestCartRemove');

    // Route::post('/login', 'Buyer\AuthController@loginPost');
    Route::get('/product','Api\HomeController@ProductPage');
    Route::post('/update-cart-item','Api\CartController@updateCartItem');
    Route::post('/update-cart','Api\CartController@updateCart');
    Route::post('/delete-cart','Api\CartController@deleteCart');
    Route::get('/all-sizes','Api\ItemController@GetAllSizes');
    Route::get('/all-colors','Api\ItemController@GetAllColors');
    Route::get('/country','Api\ItemController@getAllCountry');
    Route::get('/state','Api\ItemController@getAllStates');
    Route::get('/shipping-methods','Api\ItemController@getAllShippingMethods');
    Route::get('/checkout', 'Api\Buyer\CheckoutController@singlePageCheckout')->middleware('auth:api');
    Route::post('/checkoutpost', 'Api\Buyer\CheckoutController@singlePageCheckoutPost')->middleware('auth:api');
    Route::post('/ApplyCoupon', 'Api\Buyer\CheckoutController@ApplyCoupon')->middleware('auth:api');


    /* --------------------------- Buyer Profile Route -------------------------- */

    Route::post('/wishlist','Api\Buyer\WishListController@index');
    Route::post('/remove_wishlist','Api\Buyer\WishListController@remove_wishlist');
    Route::post('/add-to-wishlist','Api\Buyer\WishListController@AddToWishlist');
    Route::post('/resetpassword','Api\UserController@resetpassword');
    Route::post('/newpassword','Api\UserController@newpassword');
    Route::post('/change-password','Api\UserController@ChangePassword')->middleware('auth:api');
    Route::post('/profile-update','Api\UserController@ProfileUpdate')->middleware('auth:api');
    Route::post('/registration/post','Api\UserController@register');
    Route::post('buyer/logout','Api\UserController@logout');
    Route::get('/cart-items','Api\CartController@showCart');
    Route::post('/default-address','Api\Buyer\ProfileController@DefaultAddress')->middleware('auth:api');
    Route::post('/update-address','Api\Buyer\ProfileController@updateAddress')->middleware('auth:api');
    Route::post('/new-address','Api\Buyer\ProfileController@addAddress')->middleware('auth:api');
    Route::post('/buyer/UpdateBillingAddress','Api\Buyer\ProfileController@billingaddresspost')->middleware('auth:api');
    Route::post('/buyer/shippingAddressDelete','Api\Buyer\ProfileController@deleteShippingAddress')->middleware('auth:api');


    // Authenticated Data
    Route::group(['middleware' => 'auth:api'], function(){
        // Users Data
        Route::get('/user','Api\UserController@index');
        Route::get('/users','Api\UserController@users');
        Route::get('/profile','Api\Buyer\ProfileController@index');
        Route::post('/address','Api\Buyer\ProfileController@address');
        Route::post('/remove-address','Api\Buyer\ProfileController@removeAddress');
        Route::post('/default-address','Api\Buyer\ProfileController@defaultAddress');
        Route::get('/buyer/orders','Api\Buyer\ProfileController@orders');
        Route::get('/buyer/messages','Api\Buyer\ProfileController@messages');
        Route::post('/buyer/messages/status','Api\Buyer\ProfileController@messagesStatusChange');
        Route::post('/buyer/messages/replay/send','Api\Buyer\ProfileController@sendMessageReplay');
        Route::get('/single/order/{id}','Api\Buyer\ProfileController@OrderDetails')->middleware('auth:api');
        // Cart Data


        Route::post('/makeReview', 'Api\ReviewController@makeReview');
        Route::post('/review-feedback', 'Api\ReviewController@makeReviewFeedback');
        Route::match(['post'], '/temporaryImageUpload', 'Api\ImageController@temporaryImageUpload');
        Route::match(['post'], '/removeTemporaryImage', 'Api\ImageController@removeTemporaryImage');

        Route::get('/cart','Api\CartController@index');
        Route::get('/cratecheckout','Api\Buyer\CheckoutController@create');
        // Route::get('logout', 'Api\UserController@buyerlogout');
        Route::post('logout', 'Buyer\AuthController@logout')->name('logout_buyer');
    });
    Route::post('/getReviews', 'Api\ReviewController@getReviews');
});

//erp export
Route::post('erp/orders', 'Api\ExportErpController@orders');
Route::post('erp/orderDetails', 'Api\ExportErpController@orderDetails');
Route::post('erp/order/updateStatus', 'Api\ExportErpController@updateStatus');







