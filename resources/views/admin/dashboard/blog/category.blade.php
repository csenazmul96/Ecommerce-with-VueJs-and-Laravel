@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/Nestable/style.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <span class="link mr_20 item_color_btn" id="btnAddCategory">
                + Add New Category
            </span>
        </div>
    </div>

    <br>

    <div class="ly-row">
        <div class="ly-12">
            <div class="dd" id="nestable">
                <ol class="dd-list">
                    @foreach($categories as $category)
                        <li class="dd-item" data-id="{{ $category['id'] }}">
                            <div class="dd-handle dd3-handle">{{ $category['name'] }}</div> 
                            <div class="editdelete">
                                <span>
                                    &nbsp;<a style="color: red" class="btnDelete" data-id="{{ $category['id'] }}">Delete</a>
                                </span> 
                                |
                                <span>
                                    <a style="color: blue" class="btnEdit"  data-id="{{ $category['id'] }}" data-index="{{ $loop->index }}">Edit</a>
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>

    <div class="modal" data-modal="addEditModal">
        <div class="modal_overlay" data-modal-close="addEditModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_470p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Add Category</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="addEditModal"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">                      
                            <form class="form-horizontal" id="form" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="" id="modalInputId">
                                <div class="form_row">
                                    <div class="label_inline required width_150p">
                                        <label for="CategoryName">Category Name</label>
                                    </div>
                                    <div class="form_inline">
                                        <input type="text" id="CategoryName" class="form_global" placeholder="Category Name" name="category_name">
                                    </div>
                                </div>
                                
                                <div class="ly-row">
                                    <div class="ly-12">
                                        <div class="display_table m15">
                                            <div class="float_right">                                            
                                                <button class="ly_btn btn_danger " data-modal-close="addEditModal" id="addEditClose">Close</button>
                                                <button class="ly_btn btn_blue " id="modalBtnAdd">Add</button>
                                                <button class="ly_btn btn_blue " id="modalBtnUpdate">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <script type="text/javascript" src="{{ asset('plugins/Nestable/jquery.nestable.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>
        function readURL(input) {
            $('.file-name').addClass('show');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#categoryImage_show').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).on('change', '.file-upload', function(){
            $('.file-delete-button').trigger('click');
        })

        $('.file-revert-button').click(function(e){
            e.preventDefault();
            $('.is-file-delete').val(false);
            $('.file-name').css('display','inline');
            $('.file-delete-button').show();
            $("#delete_image").val(0);
            $(this).hide();
        })

        $('.file-delete-button').click(function(e){
            e.preventDefault();
            $('.is-file-delete').val(true);
            $('.file-name').css('display','none');
            $('.file-revert-button').show();
            $(this).hide();
        })

        $(document).on("click","#delete_image_btn",function() {
            $("#delete_image").val(1);
        });
        
    </script>
    <script type="text/javascript"> 
        $(function() {
            var categories = <?php echo json_encode($categories); ?>;
            var selectedID = 0;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });  

            $('#btnAddCategory').click(function() {
                $('#CategoryName').removeClass('is-invalid');
                $('#CategoryName').val('');
                $('#modalBtnAdd').show();
                $('#modalBtnUpdate').hide();
                $('#form').attr('action', '{{ route('admin_blog_add_category') }}');

                var targeted_modal_class = 'addEditModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });


            $('#modalBtnAdd').click(function (e) {
                e.preventDefault();
                var name = $('#CategoryName').val();

                if (name == '') {
                    $('#CategoryName').addClass('is-invalid');
                } else {
                    $('#form').submit();
                }
            });

            $('.btnEdit').click(function () {
                $('#btnAddCategory').css('display' , 'none');
                selectedID = $(this).data('id');
                var index = $(this).data('index');
                var category = categories[index];

                $('#CategoryName').removeClass('is-invalid');
                $('#CategoryName').val(category.name);

                $('#modalBtnAdd').hide();
                $('#modalBtnUpdate').show();

                $('#form').attr('action', '{{ route('admin_blog_update_category') }}');
                $('#modalInputId').val(selectedID);
                var targeted_modal_class = 'addEditModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });

            $('#modalBtnUpdate').click(function (e) {
                e.preventDefault();
                var name = $('#CategoryName').val();

                if (name == '') {
                    $('#CategoryName').addClass('is-invalid');
                } else {
                    $('#form').submit();
                }
            });

            $('#addEditClose').click(function (e) {
                $('#btnAddCategory').css('display' , 'block');
            });

            $('.btnDelete').click(function () {
                selectedID = $(this).data('id');
                var targeted_modal_class = 'deleteModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_blog_category_delete') }}",
                    data: { id: selectedID }
                }).done(function( msg ) {
                    location.reload();
                });
            });
        });
    </script>
@stop