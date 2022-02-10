@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="fadein">
        <div class="item_color_heading m15 mt_0">
            <div class="ly-wrap">
                <div class="ly-row">
                    <div class="ly-12 pr_0">
                        <div class="text_right">
                            <span class="font_italic color_grey_type2 font_12p">You currently have {{ count($comments) }} Posts.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="item_size_content">
            <table class="table header-border">
                <thead>
                    <tr>
                        <th># <span class="data_table_arrow"></span> </th>
                        <th>Name  <span class="data_table_arrow"></span> </th>
                        <th>Email  <span class="data_table_arrow"></span> </th>
                        <th>Comment  <span class="data_table_arrow"></span> </th>
                        <th>Date  <span class="data_table_arrow"></span> </th>
                        <th class="text_center width_150p">Status  <span class="data_table_arrow"></span> </th>
                        <th class="width_150p">Action <span class="data_table_arrow"></span> </th>
                    </tr>
                </thead>
                <tbody id="CommentTbody">
                    @foreach($comments as $comment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $comment->name }}</td>
                        <td>{{ $comment->name }}</td>
                        <td class="comment_link">   {{ strip_tags($comment->comment) }}</td>
                        <td>{{ date("F j, Y, g:i a",strtotime($comment->created_at)) }}</td>
                        <td class="text_center">
                            <div class="custom_checkbox">
                                <input type="checkbox" id="mics_{{ $comment->id }}" data-id="{{ $comment->id }}" class="statusChangeComment"
                                value="1" {{ $comment->status == 1 ? 'checked' : '' }}>
                                <label for="mics_{{ $comment->id }}" class="pr_0"></label>
                            </div>
                        </td>
                        <td>
                            <a class="viewFullComment" data-id="{{ $comment->id }}"><span class="color_blue">View</span></a> |
                            <a class="btnDeleteComment" data-id="{{ $comment->id }}" role="button"><span class="color_red">Delete</span></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination_wrapper p10 item_color_pagination_color">
        <ul class="pagination">
            <li><button class="ly_btn p1_first{{ $comments->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $comments->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
            <li>
                <button class="ly_btn p1_prev{{ $comments->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $comments->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
            </li>
            <li>
                <div class="pagination_input">
                    <input type="number" min="1" max="{{ $comments->lastPage() }}" class="form_global p1" value="{{ $comments->currentPage() }}"> of {{ $comments->lastPage() }}
                </div>
                <div class="pagination_btn">
                    <button class="ly_btn switch_page">GO</button>
                </div>
            </li>
            <li><button class="ly_btn p1_next{{ $comments->currentPage() < $comments->lastPage() ?  ' btn_paginate' : ''}}"{{ $comments->currentPage() == $comments->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
            <li>
                <button class="ly_btn p1_last{{ $comments->currentPage() < $comments->lastPage() ?  ' btn_paginate' : ''}}"{{ $comments->currentPage() == $comments->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
            </li>
        </ul>
    </div>

    <div id="viewFullComment" class="modal" data-modal="viewFullComment">
        <div class="modal_overlay" data-modal-close="viewFullComment"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_380p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Comment Details</span>
                    </div>
                    <div class="modal_content pa15">
                        <div class="view-info" style="">
                            <div class="row">
                                <div class="ly-12">
                                    <div class="general-info">
                                        <div class="row">
                                            <div class="ly-12">
                                                <div id="commentData">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form_row mb_0 pt_15">
                            <div class="form_inline">
                                <div class="text_right">
                                    <div class="display_inline mr_0">
                                        <button data-modal-close="viewFullComment" class="ly_btn btn_grey min_width_100p close_item_color">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModalComment" class="modal" data-modal="deleteModalComment">
        <div class="modal_overlay" data-modal-close="deleteModalComment"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_380p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Delete Confirmation</span>
                    </div>
                    <div class="modal_content pa15">
                        <p class="fw_500 ">Are you sure that you want to delete?</p>
                        <div class="form_row mb_0 pt_15">
                            <div class="form_inline">
                                <div class="text_right">
                                    <div class="display_inline mr_0">
                                        <button data-modal-close="deleteModalComment" class="ly_btn btn_grey min_width_100p close_item_color">Close</button>
                                    </div>
                                    <div class="display_inline mr_0">
                                        <button class="ly_btn btn_blue min_width_100p" id="modalBtnDeleteComment">Delete</button>
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
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var message = '{{ session('message') }}';
            var error = '{{ session('error') }}';

            if (message != '')
                toastr.success(message);

            // Comment
            var comments = <?php echo json_encode($comments->toArray()); ?>;
            var comments = comments.data;
            var selectedCommentId;
            var selectedCommentIndex;

            $('.viewFullComment').click(function () {
                var commentId = $(this).data('id');
                var output = ''; 
                var checked = ''; 
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_blog_comment_details') }}",
                    data: { id: commentId }
                }).done(function( res ) {
                    if(res.status == 1){
                        checked = 'checked';
                    }
                    output += '<table class="table m-0">';
                    output += '<tbody>';
                    output += '<tr>';
                    output += '<th scope="row">Name</th>';
                    output += '<td>'+res.name+'</td>';
                    output += '</tr>';
                    output += '<tr>';
                    output += '<th scope="row">Email</th>';
                    output += '<td>'+res.email+'</td>';
                    output += '</tr>';
                    output += '<tr>';
                    output += '<th scope="row">Comment</th>';
                    output += '<td>'+res.comment+'</td>';
                    output += '</tr>';
                    output += '</tbody>';
                    output += '</table>';
                    $('#commentData').html(output);
                });
                $('#viewFullComment').addClass('open_modal');
            });

            $('body').on('click', '.btnDeleteComment', function () {
                var id = $(this).data('id');
                var index = $(".btnDeleteComment").index(this);
                selectedCommentId = id;
                selectedCommentIndex = index;

                $('#deleteModalComment').addClass('open_modal');
            });
            
            $('#modalBtnDeleteComment').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_blog_comment_delete') }}",
                    data: { id: selectedCommentId }
                }).done(function( country ) {
                    $('#CommentTbody tr:eq('+selectedCommentIndex+')').remove();
                    $('#deleteModalComment').removeClass('open_modal');
                    toastr.success('Comment Deleted!');
                });

            });
            
            $('body').on('change', '.statusChangeComment', function () {
                var status = 0;
                var id = $(this).data('id');

                if ($(this).is(':checked'))
                    status = 1;

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_blog_change_comment_status') }}",
                    data: { id: id, status: status }
                }).done(function( msg ) {
                    toastr.success('Status Updated!');
                });
            });

            $('.switch_page').click(function() {
                var p1 = $('.p1').val();
                var currentLocation = String(window.location);

                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + p1;
                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('page=') >= 0) {
                        
                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + p1;
                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + p1;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p1_first').click(function() {
                var p1 = 1;
                var currentLocation = String(window.location);

                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + p1;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('page=') >= 0) {
                        
                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + p1;

                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + p1;

                    }
                }
                window.location = switchPageUrl;
            });

            $('.p1_prev').click(function() {
                var p1 = <?php echo $comments->currentPage(); ?> - 1;
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + p1;
                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('page=') >= 0) {
                        
                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + p1;
                    }
                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + p1;
                    }
                }

                window.location = switchPageUrl;
            });

            $('.p1_next').click(function() {
                var p1 = <?php echo $comments->currentPage(); ?> + 1;
                var currentLocation = String(window.location);
                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + p1;

                if((currentLocation.split('?')[1])) {
                    if((currentLocation.split('?')[1]).search('page=') >= 0) {
                        
                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + p1;

                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + p1;
                    }
                }
                window.location = switchPageUrl;
            });

            $('.p1_last').click(function() {
                var p1 = <?php echo $comments->lastPage(); ?>;
                var currentLocation = String(window.location);

                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + p1;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('page=') >= 0) {
                        
                    } else {
                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + p1;
                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + p1;
                    }
                }
                window.location = switchPageUrl;

            });
        })
    </script>
@stop