<?php
    use App\Model\Category;

    // Home
    Breadcrumbs::for('home', function ($trail) {
        $trail->push('Home', route('home'));
    });


    // New Arrival Home
    Breadcrumbs::for('new_arrival_home', function ($trail) {
        $trail->parent('home');
        $trail->push('New Arrivals', route('new_arrival_page'));
    });

	// About Us
	Breadcrumbs::for('about_us', function ($trail) {
		$trail->parent('home');
		$trail->push('About Us', route('about_us'));
	});

	// Contact Us
	Breadcrumbs::for('contact_us', function ($trail) {
		$trail->parent('home');
		$trail->push('Contact Us', route('contact_us'));
	});

	// Privacy Policy
	Breadcrumbs::for('privacy_policy', function ($trail) {
		$trail->parent('home');
		$trail->push('Privacy Policy', route('privacy_policy'));
	});

	// Return Info
	Breadcrumbs::for('return_info', function ($trail) {
		$trail->parent('home');
		$trail->push('Return Info', route('return_info'));
	});

    // FAQ
    Breadcrumbs::for('faq', function ($trail) {
        $trail->parent('home');
        $trail->push('FAQ', route('faq'));
    });


// Billing Shipping
	Breadcrumbs::for('billing_shipping', function ($trail) {
		$trail->parent('home');
		$trail->push('Billing & Shipping Info', route('billing_shipping'));
	});

    // Parent Category
    Breadcrumbs::for('parent_category_page', function ($trail, $category) {
        $trail->parent('home');
        $trail->push($category->name, route('category_page', changeSpecialChar($category->name)));
    });

	// Best Seller Page
	Breadcrumbs::for('best_seller_page', function ($trail) {
		$trail->parent('home');
		$trail->push('Best Sellers', route('best_seller_page'));
	});

    // Sub Category
    Breadcrumbs::for('second_parent_category_page', function ($trail, $category) {
        $trail->parent('parent_category_page', $category->parentCategory);
        $trail->push($category->name, route('second_category', ['parent' => changeSpecialChar($category->parentCategory->name), 'category' => changeSpecialChar($category->name)]));
    });

    // Catalog
    Breadcrumbs::for('catalog_page', function ($trail, $category) {
        $trail->parent('second_parent_category_page', $category->parentCategory);
        $trail->push($category->name, route('third_category', ['subcategory' => changeSpecialChar($category->name), 'category' => changeSpecialChar($category->parentCategory->name), 'parent' => changeSpecialChar($category->parentCategory->parentCategory->name)]));
    });

    // Item Details
    Breadcrumbs::for('item_details', function ($trail, $item) {
        if ($item->default_third_category != null && $item->default_third_category != '') {
            $category = Category::where('id', $item->default_third_category)->first();
            $trail->parent('catalog_page', $category);
        } else if ($item->default_second_category != null && $item->default_second_category != '') {
            $category = Category::where('id', $item->default_second_category)->first();
            $trail->parent('second_parent_category_page', $category);
        } else {
            $category = Category::where('id', $item->default_parent_category)->first();
            $trail->parent('parent_category_page', $category);
        }

        $trail->push($item->style_no, route('item_details_page', $item->id));
    });

