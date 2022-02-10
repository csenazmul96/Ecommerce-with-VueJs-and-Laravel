<?php
use App\Enumeration\PageEnumeration;
use App\Enumeration\Permissions;
use App\Enumeration\Role;
?>

<div class="brand">
    @if(isset($logo_path))
        @if ($logo_path != '')
            <a href="{{ route('admin_dashboard') }}">
                <img src="{{ $logo_path }}" class="admin_img" alt="logo">
            </a>
        @endif
    @endif
</div>

<div class="side_nav_list">
    <ul>
        @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$ITEMS, $permissions))
            <?php
            $menu_items = ['Create a New Item', 'Category', 'Made In Country', 'Item Edit',
                'All Items', 'Data Import', 'Sort Items'];
            ?>

            <li data-toggle="accordion" data-target="#products" class="accordion_heading open_acc active {{ ((isset($page_title) && in_array($page_title, $menu_items)) || \Request::segment(3) == 'category') ? ' open_sec open_acc active' : '' }}" data-class="accordion">
                Products
            </li>

            <ul class="sub_accordion default_accrodion open {{ ((isset($page_title) && in_array($page_title, $menu_items)) || \Request::segment(3) == 'category') ? ' open' : '' }}" id="products">
                <?php $sub_menu_items = ['Category', 'Brands', 'Made In Country', '','Sort Items'] ?>

                <li class="{{ (isset($page_title) && $page_title == 'Create a New Item') ? 'active' : '' }}">
                    <a href="{{ route('admin_create_new_item') }}">New Products</a>
                </li>

                <?php
                $sub_menu_items = ['Item Edit', 'All Items'];
                if(isset($categories)){
                    foreach($categories as $category) {
                        $sub_menu_items[] = $category['name'];

                        if (sizeof($category['subCategories']) > 0) {
                            foreach ($category['subCategories'] as $sub) {
                                $sub_menu_items[] = $sub['name'];
                            }
                        }
                    }
                }
                $title = isset($page_title) ? $page_title : '';
                ?>

                <li class="sub_child_accordion {{ ((isset($page_title) && in_array($page_title, $sub_menu_items)) || \Request::segment(3) == 'category') ? ' active' : '' }}">
                    <div data-toggle="accordion" data-target="#listProducts" class="sub_child_accordion_open accordion_heading  {{ (isset($page_title) && in_array($page_title, $sub_menu_items)) ? ' open_sec' : '' }}" data-class="accordion"></div>
                    <a href="{{ route('admin_item_list_all') }}">List Products</a>
                </li>

                <?php $sub_cat_id = Request::segment(4); ?>
                <ul class="sub_gchild_accordion default_accrodion {{ (isset($page_title) && in_array($page_title, $sub_menu_items)) ? 'open' : '' }}" id="listProducts">
                    @if(isset($categories))
                        @foreach($categories as $category)
                            <?php
                            $subCat = [];

                            foreach ($category['subCategories'] as $sub)
                                $subCat[] = $sub['name'];
                            ?>

                            @if (sizeof($category['subCategories']) > 0)

                                <li class="{{ (in_array($title, $subCat) || $sub_cat_id == $category['id'])  ? ' active' : '' }}">
                                    <a href="{{ route('admin_item_list_by_category', ['category' => $category['id']]) }}">{{ $category['name'] }}</a> <span data-toggle="accordion" data-target="#listProductsSub_{{ $category['id'] }}" class=" sub_gchild_accordion_open accordion_heading {{ in_array($title, $subCat) ? ' open_sec' : '' }}" data-class="accordion"></span>
                                </li>
                                <ul class="sub_gchild_accordion sub_ggchild_accordion default_accrodion {{ in_array($title, $subCat) ? ' open' : '' }}" id="listProductsSub_{{ $category['id'] }}">

                                    @foreach($category['subCategories'] as $sub)
                                        <li class="{{ (isset($page_title) && $title == $sub['name']) ? 'active' : '' }}">
                                            <a href="{{ route('admin_item_list_by_category', ['category' => $sub['id']]) }}"> {{ $sub['name'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <li class="{{ ((isset($page_title) && $title == $category['name']) || in_array($title, $subCat)) ? 'active' : '' }} {{ sizeof($category['subCategories']) > 0 ? 'has-sub-categories' : '' }}" data-id="{{ $category['id'] }}">
                                    <a href="{{ (sizeof($category['subCategories']) > 0) ? 'javascript:;' : route('admin_item_list_by_category', ['category' => $category['id']]) }}"> {{ $category['name'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>

                <?php $sub_menu_items = ['Category', 'Brands', 'Made In Country', 'Sort Items', 'Master Color', 'Color', 'Sizes', 'Item Values'] ?>

                <li class="sub_child_accordion {{ (isset($page_title) && in_array($page_title, $sub_menu_items)) ? ' active' : '' }}">
                    <div data-toggle="accordion" data-target="#productSetting" class="sub_child_accordion_open accordion_heading  {{ (isset($page_title) && in_array($page_title, $sub_menu_items)) ? ' open_sec' : '' }}" data-class="accordion"></div>
                    <a data-toggle="accordion" data-target="#productSetting">Product Settings</a>
                </li>
                <ul class="sub_gchild_accordion default_accrodion {{ (isset($page_title) && in_array($page_title, $sub_menu_items)) ? 'open' : '' }}" id="productSetting">
                    <li class="{{ (isset($page_title) && $page_title == 'Category') ? 'active' : '' }}">
                        <a href="{{ route('admin_category') }}">Category</a>
                    </li>
{{--                    <li class="{{ (isset($page_title) && $page_title == 'Brands') ? 'active' : '' }}">--}}
{{--                        <a href="{{ route('admin_brands') }}">Brands</a>--}}
{{--                    </li>--}}
                    <li class="{{ (isset($page_title) && $page_title == 'Made In Country') ? 'active' : '' }}">
                        <a href="{{ route('admin_made_in_country') }}">Made In Country</a>
                    </li>
                    <li class="{{ (isset($page_title) && $page_title == 'Sort Items') ? 'active' : '' }}">
                        <a href="{{ route('admin_sort_items_view') }}">Sort Items</a>
                    </li>
                    <li class="{{ (isset($page_title) && $page_title == 'Master Color') ? 'active' : '' }}">
                        <a href="{{ route('admin_master_color') }}">Master Color</a>
                    </li>
                    <li class="{{ (isset($page_title) && $page_title == 'Color') ? 'active' : '' }}">
                        <a href="{{ route('admin_color') }}">Color</a>
                    </li>
                    <li class="{{ (isset($page_title) && $page_title == 'Sizes') ? 'active' : '' }}">
                        <a href="{{ route('admin_size') }}">Size</a>
                    </li>
                    <li class="{{ (isset($page_title) && $page_title == 'Item Values') ? 'active' : '' }}">
                        <a href="{{ route('admin_item_values') }}">Item Values</a>
                    </li>
                </ul>
            </ul>
        @endif

        @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$ORDERS, $permissions))
            <?php
            $menu_items = ['New Orders', 'Order Details', 'Confirmed Orders', 'Back Orders', 'Shipped Orders', 'Cancel Orders',
                'Return Orders', 'All Orders', 'Incomplete Checkouts'];
            ?>
            <li data-toggle="accordion" data-target="#orders" class="accordion_heading open_acc active {{ (isset($page_title) && in_array($page_title, $menu_items)) ? ' open_acc active' : '' }}" data-class="accordion">
                Orders
            </li>
            <ul class="sub_accordion default_accrodion open {{ (isset($page_title) && in_array($page_title, $menu_items)) ? ' open' : '' }}" id="orders">
                <?php
                $sub_menu_items = ['New Orders', 'Order Details', 'Confirmed Orders', 'Back Orders', 'Shipped Orders',
                    'Cancel Orders', 'Return Orders', 'All Orders'];
                ?>
                <li class="sub_child_accordion {{ (isset($page_title) && in_array($page_title, $sub_menu_items)) ? ' active' : '' }}">
                    <div data-toggle="accordion" data-target="#allOrders" class="sub_child_accordion_open accordion_heading  {{ (isset($page_title) && in_array($page_title, $sub_menu_items)) ? ' open_sec' : '' }}" data-class="accordion"></div>
                    <a href="{{ route('admin_all_orders') }}">All Orders</a>
                </li>
                <ul class="sub_gchild_accordion default_accrodion {{ (isset($page_title) && in_array($page_title, $sub_menu_items)) ? 'open' : '' }}" id="allOrders">
                    <li class="{{ (isset($page_title) && $page_title == 'New Orders') ? 'active' : '' }}">
                        <a href="{{ route('admin_new_orders') }}">New Orders</a>
                    </li>

                    <li class="{{ (isset($page_title) && $page_title == 'Confirmed Orders') ? 'active' : '' }}">
                        <a href="{{ route('admin_confirmed_orders') }}">Confirmed</a>
                    </li>

                    <li class="{{ (isset($page_title) && $page_title == 'Back Orders') ? 'active' : '' }}">
                        <a href="{{ route('admin_backed_orders') }}">Back Ordered</a>
                    </li>

                    <li class="{{ (isset($page_title) && $page_title == 'Shipped Orders') ? 'active' : '' }}">
                        <a href="{{ route('admin_shipped_orders') }}">Shipped</a>
                    </li>

                    <li class="{{ (isset($page_title) && $page_title == 'Cancel Orders') ? 'active' : '' }}">
                        <a href="{{ route('admin_cancelled_orders') }}">Cancelled</a>
                    </li>

                    <li class="{{ (isset($page_title) && $page_title == 'Return Orders') ? 'active' : '' }}">
                        <a href="{{ route('admin_returned_orders') }}">Returned</a>
                    </li>
                </ul>
                <li class="{{ (isset($page_title) && $page_title == 'Create New Order') ? 'active' : '' }}">
                    <a href="{{ route('admin_new_order_create') }}">Create New Order</a>
                </li>
                <li class="{{ (isset($page_title) && $page_title == 'Incomplete Checkouts') ? 'active' : '' }}">
                    <a href="{{ route('admin_incomplete_orders') }}">Incomplete Checkouts</a>
                </li>
            </ul>
        @endif

        @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$CUSTOMERS, $permissions))
            <?php $menu_items = ['All Customer', 'Block Customers', 'Store Credit','Create Customer', 'Age Group', 'Skin Types'] ?>
            <li data-toggle="accordion" data-target="#customers" class="accordion_heading {{ (isset($page_title) && in_array($page_title, $menu_items)) ? ' open_sec open_acc active' : '' }}" data-class="accordion">
                Customers
            </li>
            <ul class="sub_accordion default_accrodion {{ (isset($page_title) && in_array($page_title, $menu_items)) ? ' open' : '' }}" id="customers">
                <li class="{{ (isset($page_title) && $page_title == 'All Customer') ? 'active' : '' }}">
                    <a href="{{ route('admin_all_buyer') }}">All Customers</a>
                </li>
                <li class="{{ (isset($page_title) && $page_title == 'Create Customer') ? 'active' : '' }}">
                    <a href="{{ route('customer_create') }}">Create Customer</a>
                </li>
                {{-- <li class="{{ (isset($page_title) && $page_title == 'Store Credit') ? 'active' : '' }}">
                    <a href="{{ route('admin_store_credit') }}">Store Credit</a>
                </li> --}}
                {{-- <li class="{{ (isset($page_title) && $page_title == 'Age Group') ? 'active' : '' }}">
                    <a href="{{ route('admin_age_group') }}">Age Group</a>
                </li>
                <li class="{{ (isset($page_title) && $page_title == 'Skin Types') ? 'active' : '' }}">
                    <a href="{{ route('admin_skin_types') }}">Skin Types</a>
                </li> --}}
            </ul>
        @endif

        @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$STATISTICS, $permissions))
            <?php $menu_items = ['Item Statistics'] ?>
            <li data-toggle="accordion" data-target="#statistics" class="accordion_heading {{ (isset($page_title) && in_array($page_title, $menu_items)) ? ' open_sec open_acc active' : '' }}" data-class="accordion">
                STATISTICS
            </li>
            <ul class="sub_accordion default_accrodion {{ (isset($page_title) && in_array($page_title, $menu_items)) ? ' open' : '' }}" id="statistics">
                <li class="{{ (isset($page_title) && $page_title == 'Item Statistics') ? 'active' : '' }}">
                    <a href="{{ route('item_statistics') }}">Item Statistics</a>
                </li>
            </ul>
        @endif

        <?php $menu_items = ['All Post' , 'Blog Category', 'Blog Comments', 'Blog Banners', 'Edit Post'] ?>
        <li data-toggle="accordion" data-target="#blog" class="accordion_heading {{ (isset($page_title) && in_array($page_title, $menu_items)) ? ' open_sec open_acc active' : '' }}" data-class="accordion">
            Blog
        </li>
        <ul class="sub_accordion default_accrodion {{ (isset($page_title) && in_array($page_title, $menu_items)) ? ' open' : '' }}" id="blog">
            <li class="{{ (isset($page_title) && $page_title == 'All Post') ? 'active' : '' }}">
                <a href="{{ route('admin_blog') }}">All Post</a>
            </li>
            <li class="{{ (isset($page_title) && $page_title == 'Blog Category') ? 'active' : '' }}">
                <a href="{{ route('admin_blog_category') }}">Category</a>
            </li>
            <li class="{{ (isset($page_title) && $page_title == 'Blog Comments') ? 'active' : '' }}">
                <a href="{{ route('admin_blog_comments') }}">Comments</a>
            </li>
            <li class="{{ (isset($page_title) && $page_title == 'Blog Banners') ? 'active' : '' }}">
                <a href="{{ route('blog_banner') }}">Banners</a>
            </li>
        </ul>

        <?php $menu_items = ['Menu Banners', 'Main Banner','Category Color','Feature Widget Banner', 'Home Page Selected Items'] ?>
        <li data-toggle="accordion" data-target="#banner" class="accordion_heading {{ (isset($page_title) && in_array($page_title, $menu_items)) ? ' open_sec open_acc active' : '' }}" data-class="accordion">
            Banners
        </li>
        <ul class="sub_accordion default_accrodion {{ (isset($page_title) && in_array($page_title, $menu_items)) ? ' open' : '' }}" id="banner">
            <li class="{{ (isset($page_title) && $page_title == 'Menu Banners') ? 'active' : '' }}">
                <a href="{{ route('menu_banners') }}">Menu's Feature Banners</a>
            </li>

            <li class="{{ (isset($page_title) && $page_title == 'Main Banner') ? 'active' : '' }}">
                <a href="{{ route('admin_main_banner') }}">Main Slider Banner</a>
            </li>

{{--            <li class="{{ (isset($page_title) && $page_title == 'Category Color') ? 'active' : '' }}">--}}
{{--                <a href="{{ route('category_color') }}">Category Color</a>--}}
{{--            </li>--}}

            <li class="{{ (isset($page_title) && $page_title == 'Feature Widget Banner') ? 'active' : '' }}">
                <a href="{{ route('feature_widget') }}?type=top">Feature Widgets</a>
            </li>

            <li class="{{ (isset($page_title) && $page_title == 'Home Page Selected Items') ? 'active' : '' }}">
                <a href="{{ route('home_page_selected_items') }}?type=top">Home Page Items</a>
            </li>
        </ul>

        <?php $menu_items = ['Privacy Notice', 'Section Heading', 'Item Review', 'Faqs'] ?>
        <li data-toggle="accordion" data-target="#settings" class="accordion_heading {{ (isset($page_title) && in_array($page_title, $menu_items)) ? ' open_sec open_acc active' : '' }}" data-class="accordion">
            Settings
        </li>
        <ul class="sub_accordion default_accrodion {{ (isset($page_title) && in_array($page_title, $menu_items)) ? ' open' : '' }}" id="settings">
            <li class="{{ (isset($page_title) && $page_title == 'Privacy Notice') ? 'active' : '' }}">
                <a href="{{ route('privacy_notice') }}">Privacy Notice</a>
            </li>
            <li class="{{ (isset($page_title) && $page_title == 'Item Review') ? 'active' : '' }}">
                <a href="{{ route('item_review') }}">Item Review</a>
            </li>
            <li class="{{ (isset($page_title) && $page_title == 'Section Heading') ? 'active' : '' }}">
                <a href="{{ route('section_heading') }}">Section Heading</a>
            </li>
            <li class="{{ (isset($page_title) && $page_title == 'Faqs') ? 'active' : '' }}">
                <a href="{{ route('faqs') }}">Faqs</a>
            </li>
        </ul>
    </ul>
</div>
