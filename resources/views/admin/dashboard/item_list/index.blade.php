@extends('admin.layouts.main')

@section('additionalCSS')
@stop

@section('content')
    <div class="divLoading">
        <img src="{{ asset('images/loading.gif') }}">
    </div>
    <div class="ly_page_wrapper">
        <div class="item_list_search">
            <div class="item_list_search_checkbox">
                <div class="display_inline mr_50">
                    <b class="font_16p fw_500">Search</b>
                </div>
                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="searchStyleNo"
                        {{ (request()->get('style') == '1' || request()->get('style') == null) ? 'checked' : '' }}>
                    <label for="searchStyleNo">Style No.</label>
                </div>
                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="searchDescription"
                        {{ (request()->get('des') == '1') ? 'checked' : '' }}>
                    <label for="searchDescription">Full Description</label>
                </div>
                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="searchItemName"
                        {{ (request()->get('name') == '1') ? 'checked' : '' }}>
                    <label for="searchItemName">Item Name</label>
                </div>
            </div>
            <div class="item_list_searchbox m15">
                <div class="display_inline width_350p">
                    <input type="text" class="form_global" placeholder="(Use commas(,) for multiple style search)"
                           id="inputText" value="{{ request()->get('text') }}">
                </div>
                <div class="display_inline">
                    <button class="ly_btn btn_blue width_100p toggle_item_search" id="btnSearch">Search</button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="ly_accrodion active_item_list">
        <div class="ly_accrodion_heading display_table">
            <div class="ly_accrodion_title accordion_heading open_acc" data-toggle="accordion" data-target="#ActiveItems" data-class="accordion">
                <span>  Active Items - {{ $totalActiveitem }} Items</span>
            </div>
        </div>
        <div class="accordion_body default_accrodion open" id="ActiveItems">
            <div class="list_item_content">
                <div class="pagination_wrapper p10 pt_0">
                    <div class="display_inline width_150p">
                        <div class="select">
                            <select class="form_global" id="selectSortActiveItems">
                                <option value="0" {{ request()->get('s1') == '0' ? 'selected' : '' }}>Sort Number</option>
                                <option value="3" {{ request()->get('s1') == '3' ? 'selected' : '' }}>Activation Date</option>
                                <option value="1" {{ request()->get('s1') == '1' ? 'selected' : '' }}>Last Update</option>
                                <option value="2" {{ request()->get('s1') == '2' ? 'selected' : '' }}>Upload Date</option>
                                <option value="4" {{ request()->get('s1') == '4' ? 'selected' : '' }}>Price Low to High</option>
                                <option value="5" {{ request()->get('s1') == '5' ? 'selected' : '' }}>Price High to Low</option>
                                <option value="6" {{ request()->get('s1') == '6' ? 'selected' : '' }}>Style No.</option>
                            </select>
                        </div>
                    </div>

                    <ul class="pagination">
                        <li><button class="ly_btn p1_first{{ $activeItems->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $activeItems->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
                        <li>
                            <button class="ly_btn p1_prev{{ $activeItems->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $activeItems->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
                        </li>
                        <li>
                            <div class="pagination_input">
                                <input type="number" min="1" max="{{ $activeItems->lastPage() }}" class="form_global p1" value="{{ $activeItems->currentPage() }}"> of {{ $activeItems->lastPage() }}
                            </div>
                            <div class="pagination_btn">
                                <button class="ly_btn switch_page">GO</button>
                            </div>
                        </li>
                        <li><button class="ly_btn p1_next{{ $activeItems->currentPage() < $activeItems->lastPage() ?  ' btn_paginate' : ''}}"{{ $activeItems->currentPage() == $activeItems->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
                        <li>
                            <button class="ly_btn p1_last{{ $activeItems->currentPage() < $activeItems->lastPage() ?  ' btn_paginate' : ''}}"{{ $activeItems->currentPage() == $activeItems->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
                        </li>
                    </ul>
                </div>
                <hr>
                <div class="item_list_advanced_search p10">
                    <div class="display_inline width_150p">
                        <div class="select">
                            <select class="form_global" id="select-active-move-category">
                                <option value="">Move To</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>

                                    @if (sizeof($category->subCategories) > 0)
                                        @foreach($category->subCategories as $sub)
                                            <option value="{{ $sub->id }}">- {{ $sub->name }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="display_inline">
                        <button class="ly_btn btn_blue_hover width_100p" id="btn-active-move-category">Move</button>
                    </div>
                    <div class="float_right">
                        <div class="display_inline">
                            <span class="link toggle_item_checked">Select All</span>
                        </div>
                        <button class="ly_btn btn_blue width_100p btnCloneActive">Clone</button>
                        <button class="ly_btn btn_blue width_100p" id="btnDeactive">Deactivate</button>
                    </div>
                </div>
                <hr>
                <div class="item_list_wrapper p10">
                    @foreach($activeItems as $item)
                        <div class="item_list">
                            <div class="custom_checkbox">
                                <input class="checkbox-active-items" type="checkbox" id="productCB_{{ $item->id }}" data-id="{{ $item->id }}">
                                <label for="productCB_{{ $item->id }}"></label>
                            </div>
                            <div class="item_list_text">
                                <span class="single_img">
                                    <a href="{{ route('admin_edit_item', ['item' => $item->id]) }}">
                                        @if (sizeof($item->images) > 0)
                                            <img src="{{ Storage::url($item->images[0]->thumbs_image_path) }}" alt="{{ $item->style_no }}">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" alt="{{ $item->style_no }}">
                                        @endif
                                    </a>
                                    <a href="{{ route('admin_edit_item', ['item' => $item->id]) }}" onclick="centeredmodal(this.href,'myWindow','1150','800','yes');return false" class="edit"><i class="fas fa-edit"></i></a>
                                </span>
                                <span class="item_list_desc">

                                    <h2>
                                        @if(!empty($item->itemcategory[0])) {{ $item->itemcategory[0]->parent_category['name'] }} @endif
                                        @if(!empty($item->itemcategory[0]) && !empty($item->itemcategory[0]->second_category))  / {{ $item->itemcategory[0]->second_category['name'] }} @endif
                                    </h2>
                                    <h3><a href="{{ route('admin_edit_item', ['item' => $item->id]) }}">{{ $item->style_no }}</a></h3>
                                    <h2>
                                        @if ($item->orig_price != null)
                                            <del>${{ number_format($item->orig_price, 2, '.', '') }}</del>
                                        @endif
                                        ${{ number_format($item->price, 2, '.', '') }}
                                    </h2>
                                    <p>{{ date('m/d/Y - h:i:s a', strtotime($item->activated_at)) }}</p>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="ly_accrodion inactive_item_list">
        <div class="ly_accrodion_heading display_table">
            <div class="ly_accrodion_title accordion_heading open_acc" data-toggle="accordion" data-target="#InactiveItems" data-class="accordion">
                <span>  Inactive Items - {{ $totalInactiveitem }} Items</span>
            </div>
        </div>
        <div class="accordion_body default_accrodion open" id="InactiveItems">
            <div class="list_item_content">
                <div class="pagination_wrapper p10 pt_0">
                    <div class="display_inline width_150p">
                        <div class="select">
                            <select class="form_global" id="selectSortInactiveItems">
                                <option value="2" {{ request()->get('s2') == '2' ? 'selected' : '' }}>Upload Date</option>
                                <option value="0" {{ request()->get('s2') == '0' ? 'selected' : '' }}>Sort Number</option>
                                <option value="1" {{ request()->get('s2') == '1' ? 'selected' : '' }}>Last Update</option>
                                <option value="3" {{ request()->get('s2') == '3' ? 'selected' : '' }}>Activation Date</option>
                                <option value="4" {{ request()->get('s2') == '4' ? 'selected' : '' }}>Price Low to High</option>
                                <option value="5" {{ request()->get('s2') == '5' ? 'selected' : '' }}>Price High to Low</option>
                                <option value="6" {{ request()->get('s2') == '6' ? 'selected' : '' }}>Style No.</option>
                            </select>
                        </div>
                    </div>
                    <ul class="pagination">
                        <li><button class="ly_btn p2_first{{ $inactiveItems->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $inactiveItems->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
                        <li>
                            <button class="ly_btn p2_prev{{ $inactiveItems->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $inactiveItems->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
                        </li>
                        <li>
                            <div class="pagination_input">
                                <input type="number" min="1" max="{{ $inactiveItems->lastPage() }}" class="form_global p2" value="{{ $inactiveItems->currentPage() }}"> of {{ $inactiveItems->lastPage() }}
                            </div>
                            <div class="pagination_btn">
                                <button class="ly_btn switch_page">GO</button>
                            </div>
                        </li>
                        <li><button class="ly_btn p2_next{{ $inactiveItems->currentPage() < $inactiveItems->lastPage() ?  ' btn_paginate' : ''}}"{{ $inactiveItems->currentPage() == $inactiveItems->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
                        <li>
                            <button class="ly_btn p2_last{{ $inactiveItems->currentPage() < $inactiveItems->lastPage() ?  ' btn_paginate' : ''}}"{{ $inactiveItems->currentPage() == $inactiveItems->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
                        </li>
                    </ul>
                </div>
                <hr>
                <div class="item_list_advanced_search p10">
                    <div class="display_inline width_150p">
                        <div class="select">
                            <select class="form_global" id="select-inactive-move-category">
                                <option value="">Move To</option>
                                @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @if (sizeof($category->subCategories) > 0)
                                        @foreach($category->subCategories as $sub)
                                            <option value="{{ $sub->id }}">- {{ $sub->name }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="display_inline">
                        <button class="ly_btn btn_blue_hover width_100p" id="btn-inactive-move-category">Move</button>
                    </div>
                    <div class="float_right">
                        <div class="display_inline">
                            <span class="link toggle_item_checked_inactive">Select All</span>
                        </div>
                        <button class="ly_btn btn_blue width_100p btnCloneInactive">Clone</button>
                        <button class="ly_btn btn_danger width_100p" id="btnDelete">Delete</button>
                        <button class="ly_btn btn_blue width_100p" id="btnActive">Activate</button>
                    </div>
                </div>
                <hr>
                <div class="item_list_wrapper p10">
                    @foreach($inactiveItems as $item)
                        <div class="item_list">
                            <div class="custom_checkbox">
                                <input class="checkbox-inactive-items" type="checkbox" id="productCB_{{ $item->id }}" data-id="{{ $item->id }}">
                                <label for="productCB_{{ $item->id }}"></label>
                            </div>
                            <div class="item_list_text">
                                <span class="single_img">
                                    <a href="{{ route('admin_edit_item', ['item' => $item->id]) }}">
                                        @if (sizeof($item->images) > 0)
                                            <img src="{{ Storage::url($item->images[0]->thumbs_image_path) }}" alt="{{ $item->style_no }}">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" alt="{{ $item->style_no }}">
                                        @endif
                                    </a>
                                    <a href="{{ route('admin_edit_item', ['item' => $item->id]) }}" onclick="centeredmodal(this.href,'myWindow','1150','800','yes');return false" class="edit"><i class="fas fa-edit"></i></a>
                                </span>
                                <span class="item_list_desc">
                                    <h2>
                                        @if(!empty($item['itemcategory'][0]['parent_category'])) {{ $item['itemcategory'][0]['parent_category']->name }} @endif
                                            @if(!empty($item->itemcategory[0]) && !empty($item->itemcategory[0]->second_category))  / {{ $item->itemcategory[0]->second_category['name'] }} @endif
                                    </h2>
                                    <h3><a href="{{ route('admin_edit_item', ['item' => $item->id]) }}">{{ $item->style_no }}</a></h3>
                                    <h2>
                                        @if ($item->orig_price != null)
                                            <del>${{ number_format($item->orig_price, 2, '.', '') }}</del>
                                        @endif
                                        ${{ number_format($item->price, 2, '.', '') }}
                                    </h2>
                                    <p>{{ date('m/d/Y - h:i:s a', strtotime($item->updated_at)) }}</p>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop

@section('additionalJS')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.switch_page').click(function() {

                var p1 = $('.p1').val();
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);

                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {
                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;
                    }
                }

                window.location = switchPageUrl;

            });

            $('.p1_first').click(function() {

                var p1 = 1;
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p1_prev').click(function() {
                var p1 = <?php echo $activeItems->currentPage(); ?> - 1;
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {
                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p1_next').click(function() {
                var p1 = <?php echo $activeItems->currentPage(); ?> + 1;
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {
                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p1_last').click(function() {
                var p1 = <?php echo $activeItems->lastPage(); ?>;
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {
                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p2_first').click(function() {

                var p2 = 1;
                var p1 = $('.p1').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;
                    }
                }
                window.location = switchPageUrl;

            });

            $('.p2_prev').click(function() {

                var p2 = <?php echo $inactiveItems->currentPage(); ?> - 1;
                var p1 = $('.p1').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p2_next').click(function() {

                var p2 = <?php echo $inactiveItems->currentPage(); ?> + 1;
                var p1 = $('.p1').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p2_last').click(function() {

                var p2 = <?php echo $inactiveItems->lastPage(); ?>;
                var p1 = $('.p1').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 + '&p2=' + p2;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 + '&p2=' + p2;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 + '&p2=' + p2;
                    }
                }
                window.location = switchPageUrl;

            });

            $('#selectSortActiveItems, #selectSortInactiveItems').change(function () {
                checkParameters();
            });

            $('#btnSearch').click(function () {
                search();
            });

            $('#inputText').keypress(function(e) {
                if(e.which == 13) {
                    search();
                }
            });

            $('#btnSelectAllActive').click(function () {
                $('.checkbox-active-items').prop('checked', false).trigger('click');
            });

            $('#btnDeselectAllActive').click(function () {
                $('.checkbox-active-items').prop('checked', true).trigger('click');
            });

            $('#btnSelectAllInactive').click(function () {
                $('.checkbox-inactive-items').prop('checked', false).trigger('click');
            });

            $('#btnDeselectAllInactive').click(function () {
                $('.checkbox-inactive-items').prop('checked', true).trigger('click');
            });

            $('#btnDeactive').click(function () {
                var ids = [];

                $('.checkbox-active-items').each(function () {
                    if ($(this).is(':checked')) {
                        ids.push($(this).data('id'));
                    }
                });

                if (ids.length > 0) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_item_list_change_to_inactive') }}",
                        data: {ids: ids}
                    }).done(function (msg) {
                        window.location.reload();
                    });
                }
            });

            $('#btnActive').click(function () {
                var ids = [];

                $('.checkbox-inactive-items').each(function () {
                    if ($(this).is(':checked')) {
                        ids.push($(this).data('id'));
                    }
                });

                if (ids.length > 0) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_item_list_change_to_active') }}",
                        data: {ids: ids}
                    }).done(function (msg) {
                        window.location.reload();
                    });
                }
            });

            $('#btnDelete').click(function () {
                var ids = [];

                $('.checkbox-inactive-items').each(function () {
                    if ($(this).is(':checked')) {
                        ids.push($(this).data('id'));
                    }
                });

                if (ids.length > 0) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_item_list_delete') }}",
                        data: {ids: ids}
                    }).done(function (msg) {
                        window.location.reload();
                    });
                }
            });

            function checkParameters() {
                var s1 = $('#selectSortActiveItems').val();
                var s2 = $('#selectSortInactiveItems').val();

                var parameters = <?php echo json_encode(request()->all()); ?>;
                var url = '{{ url()->current() }}' + '?s1=' + s1 + '&s2=' + s2;

                $.each(parameters, function (key, value) {
                    if (key != 's1' && key != 's2' && key != 'p1' && key != 'p2') {
                        var val = '';

                        if (value != null)
                            val = value;

                        url += '&' + key + '=' + val;
                    }
                });
                window.location.replace(url);
            }

            function search() {
                var s1 = $('#selectSortActiveItems').val();
                var s2 = $('#selectSortInactiveItems').val();
                var text = $('#inputText').val();
                var searchStyleNo = ($('#searchStyleNo').is(':checked')) ? 1 : 0;
                var description = ($('#searchDescription').is(':checked')) ? 1 : 0;
                var name = ($('#searchItemName').is(':checked')) ? 1 : 0;



                var url = '{{ url()->current() }}' + '?s1=' + s1 + '&s2=' + s2 + '&text=' + text + '&style=' + searchStyleNo +
                    '&des=' + description + '&name=' + name;
                window.location.replace(url);
            }

            $('#btn-active-move-category').click(function () {
                var ids = [];
                var cat_id = '';

                cat_id = $('#select-active-move-category').val();

                $('.checkbox-active-items').each(function () {
                    if ($(this).is(':checked')) {
                        ids.push($(this).data('id'));
                    }
                });

                if (ids.length > 0 && cat_id != '') {
                    $(".divLoading").addClass('show');

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_category_move') }}",
                        data: { ids: ids, cat_id: cat_id }
                    }).done(function (data) {
                        location.reload();
                    });
                }
            });

            $('#btn-inactive-move-category').click(function () {
                var ids = [];
                var cat_id = '';

                cat_id = $('#select-inactive-move-category').val();

                $('.checkbox-inactive-items').each(function () {
                    if ($(this).is(':checked')) {
                        ids.push($(this).data('id'));
                    }
                });

                if (ids.length > 0 && cat_id != '') {
                    $(".divLoading").addClass('show');

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_category_move') }}",
                        data: { ids: ids, cat_id: cat_id }
                    }).done(function (data) {
                        location.reload();
                    });
                }
            });

            $('.btnCloneActive').click(function () {
                var ids = [];

                $('.checkbox-active-items').each(function () {
                    if ($(this).is(':checked')) {
                        ids.push($(this).data('id'));
                    }
                });

                if (ids.length > 0) {
                    $(".divLoading").addClass('show');

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_clone_multi_items') }}",
                        data: { ids: ids }
                    }).done(function (data) {
                        location.reload();
                    });
                }
            });

            $('.btnCloneInactive').click(function () {
                var ids = [];

                $('.checkbox-inactive-items').each(function () {
                    if ($(this).is(':checked')) {
                        ids.push($(this).data('id'));
                    }
                });
                if (ids.length > 0) {
                    $(".divLoading").addClass('show');

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_clone_multi_items') }}",
                        data: { ids: ids }
                    }).done(function (data) {
                        location.reload();
                    });
                }
            });
        });
    </script>
@stop
