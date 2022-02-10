@extends('admin.layouts.main')

<?php
use App\Enumeration\VendorImageType;
?>

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')

    <div class="ly_page_wrapper">
        <div class="ly-wrap-fluid">
            <div class="ly-row">
                <form class="form-horizontal" method="post" action="{{ route('section_heading_post') }}" >
                    @csrf
                    <input type="hidden" id="id" value="{{ $section_heading->id }}" name="id">
                    <div class="ly-row">
                        <div class="ly-10">
                            <div class="form_row">
                                <div class="label_inline required width_150p">Section Heading</div>
                                <div class="form_inline">
                                    <input type="text" id="style_no" class="form_global{{ $errors->has('heading') ? ' is-invalid' : '' }}" name="heading" value="{{ $section_heading->heading }}">
                                </div>
                            </div>
                        </div>
                        <div class="ly-2">
                            <div class="text_right">
                                <div class="display_inline">
                                    <button type="submit" class="ly_btn  btn_blue min_width_100p ">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="ly-row">
                <div class="ly-12">
                    <div class="item_list_wrapper p10">
                        @foreach($activeItems as $item)
                            <div class="item_list" style="width: 8.7%;">
                                <div class="item_list_text" style="padding:0px">
                                    <a href="javascript:void(0)" class="selectItem" data-item="{{ $item->id }}">
                                        @if (sizeof($item->images) > 0)
                                            <img src="{{ Storage::url($item->images[0]->thumbs_image_path) }}" alt="{{ $item->style_no }}">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" alt="{{ $item->style_no }}">
                                        @endif
                                    </a>
                                    <span class="item_list_desc">
                                        <h2>@if(!empty($item->itemcategory[0])) {{ $item->itemcategory[0]->parent_category['name'] }} @endif</h2>
                                        <h3><a href="{{ route('admin_edit_item', ['item' => $item->id]) }}">{{ $item->style_no }}</a></h3>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="ly-12">
                    <ul class="pagination">
                        <li><button class="ly_btn p1_first{{ $activeItems->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $activeItems->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
                        <li>
                            <button class="ly_btn p1_prev{{ $activeItems->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $activeItems->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
                        </li>
                        <li>
                            <div class="pagination_input">
                                <input type="number" min="1" max="{{ $activeItems->lastPage() }}" class="form_global p1" value="{{ $activeItems->currentPage() }}"> of {{ $activeItems->lastPage() }}
                            </div>
                        </li>
                        <li><button class="ly_btn p1_next{{ $activeItems->currentPage() < $activeItems->lastPage() ?  ' btn_paginate' : ''}}"{{ $activeItems->currentPage() == $activeItems->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
                        <li>
                            <button class="ly_btn p1_last{{ $activeItems->currentPage() < $activeItems->lastPage() ?  ' btn_paginate' : ''}}"{{ $activeItems->currentPage() == $activeItems->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <br>
    <br>
    <div class="ly_page_wrapper">
        <div class="ly-wrap-fluid">
            <div class="ly-row">
                <div class="ly-12">
                    @if(count($selectedItems) > 0)
                        <ul id="mainBanner" data-id="8">
                            @foreach($selectedItems as $item)
                                <li data-id="{{ $item->id }}">
                                    <span class="width_full display_flex">
                                    @if (sizeof($item->item_image) > 0)
                                        <img src="{{ Storage::url($item->item_image[0]->thumbs_image_path) }}" width="100px">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" width="100px" alt="     ">
                                    @endif
                                    </span>
                                    <span class="width_full mt-2 display_flex">{{ $item->item->style_no }}</span>
                                    <a href="javascript:void(0)" class="width_full display_flex mt-2 text-danger btnRemove" data-id="{{ $item->id }}">Remove</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="modal" data-modal="deleteModal">
        <div class="modal_overlay" data-modal-close="deleteModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_470p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Are you sure want to delete?</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="deleteModal"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">
                            <div class="ly-row">
                                <div class="ly-12">
                                    <div class="display_table m15">
                                        <div class="float_right">
                                            <button class="ly_btn btn_blue width_150p " data-modal-close="deleteModal">Close</button>
                                            <button class="ly_btn btn_danger width_150p" id="modalBtnDelete">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/sortable/js/Sortable.min.js') }}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".selectItem").click(function(){
                var id = $(this).data('item');
                if(id){
                    $.ajax({
                        method: "POST",
                        url: "{{ route('home_page_selected_items_add') }}",
                        data: { id: id }
                    }).done(function( msg ) {
                        window.location.reload()
                        toastr.success(msg.message);
                    });
                }
            });
            var elsmll = document.getElementById('mainBanner');
            Sortable.create(elsmll, {
                animation: 150,
                dataIdAttr: 'data-id',
                onEnd: function () {
                    updateSort(this.toArray());
                },
            });

            function updateSort(ids) {
                $.ajax({
                    method: "POST",
                    url: "{{ route('home_page_selected_items_sort') }}",
                    data: { ids: ids}
                }).done(function( msg ) {
                    toastr.success('Items sort updated!');
                });
            }
            var selectedId = '';
            $('.btnRemove').click(function () {
                $('[data-modal="deleteModal"]').addClass('open_modal');
                selectedId = $(this).data('id');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('home_page_selected_items_delete') }}",
                    data: { id: selectedId }
                }).done(function( msg ) {
                    location.reload();
                });
            });


            $('.p1_first').click(function() {

                var p1 = 1;
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 ;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 ;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 ;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p1_prev').click(function() {
                var p1 = <?php echo $activeItems->currentPage(); ?> - 1;
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 ;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {
                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 ;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 ;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p1_next').click(function() {
                var p1 = <?php echo $activeItems->currentPage(); ?> + 1;
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 ;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {
                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 ;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 ;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p1_last').click(function() {
                var p1 = <?php echo $activeItems->lastPage(); ?>;
                var p2 = $('.p2').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 ;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {
                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 ;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 ;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p2_first').click(function() {

                var p2 = 1;
                var p1 = $('.p1').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 ;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 ;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 ;
                    }
                }
                window.location = switchPageUrl;

            });

            $('.p2_prev').click(function() {
                var p1 = $('.p1').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 ;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 ;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 ;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p2_next').click(function() {

                var p1 = $('.p1').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 ;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 ;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 ;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p2_last').click(function() {
                var p1 = $('.p1').val();
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?p1=' + p1 ;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('p1=') >= 0) {

                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&p1=' + p1 ;
                    }

                    if((currentLocation.split('?')[1]).search('&p1=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&p1=')[0] + '&p1=' + p1 ;
                    }
                }
                window.location = switchPageUrl;

            });


        })
    </script>
@stop
