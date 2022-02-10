@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="fadein">
        <div class="ly_page_wrapper mb_15  {{ count($errors) === 0 ? 'd-none' : '' }}" id="addEditRowNotice">
            <div class="add_new_cat_color">
                <h3 class="font_16p mb_15 item_change_title" id="addEditTitleNotice">Add New Notice</h3>
                <form action="" id="form" method="post">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="form_row">
                        <div class="label_inline width_150p fw_500"> Status </div>
                        <div class="form_inline">
                            <div class="custom_radio">
                                <input type="radio" id="statusActive" name="status" class="custom-control-input"
                                       value="1" {{ (old('status') == '1' || empty(old('status'))) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="statusActive">Active</label>
                            </div>
                            <div class="custom_radio">
                                <input type="radio" id="statusInactive" name="status" class="custom-control-input"
                                       value="0" {{ old('status') == '0' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="statusInactive">Inactive</label>
                            </div>
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500"> Notice Name </div>
                        <div class="form_inline">
                            <input type="text" class="form_global {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name">
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="label_inline required width_150p fw_500"> Notice Description </div>
                        <div class="form_inline">
                            <textarea name="description" class="form_global {{ $errors->has('description') ? ' is-invalid' : '' }}" id="description" cols="30" rows="10">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="form_inline">
                            <div class="text_right">
                                <div class="display_inline">
                                    <button type="button" class="ly_btn btn_grey min_width_100p" id="btnCancelNotice">Cancel</button>
                                </div>
                                <div class="display_inline mr_0">
                                    <button class="ly_btn btn_blue min_width_100p" id="btnAddNotice">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="item_color_heading m15 mt_0">
            <div class="ly-wrap">
                <div class="ly-row">
                    <div class="ly-6 pl_0">
                        <div class="item_color_heading_left">
                            <span class="link mr_20 item_color_btn" id="btnAddNewNotice">+ Add New Notice</span>
                        </div>
                    </div>
                    <div class="ly-6 pr_0">
                        <div class="text_right">
                            <span class="font_italic color_grey_type2 font_12p">You currently have {{ count($notices) }} Privacy Notice.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="item_size_content">
            <table class="table header-border">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Notice Name</th>
                    <th class="text_center width_150p">Status</th>
                    <th class="width_150p">Action</th>
                </tr>
                </thead>
                <tbody id="NoticeTbody">
                    @foreach($notices as $notice)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $notice->name }}</td>
                            <td class="text_center">
                                <div class="custom_checkbox">
                                    <input type="checkbox" id="mics_{{ $notice->id }}" data-id="{{ $notice->id }}" class="statusNotice"
                                        value="1" {{ $notice->status == 1 ? 'checked' : '' }}>
                                    <label for="mics_{{ $notice->id }}" class="pr_0"></label>
                                </div>
                            </td>
                            <td>
                                <a class="btnEditNotice" data-index="{{ $loop->index }}" role="button"><span class="color_blue">Edit</span></a> |
                                <a class="btnDeleteNotice" data-id="{{ $notice->id }}" role="button"><span class="color_red">Delete</span></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div id="deleteModalNotice" class="modal" data-modal="deleteModalNotice">
        <div class="modal_overlay" data-modal-close="deleteModalNotice"></div>
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
                                        <button data-modal-close="deleteModalNotice" class="ly_btn btn_grey min_width_100p close_item_color">Close</button>
                                    </div>
                                    <div class="display_inline mr_0">
                                        <button class="ly_btn btn_blue min_width_100p" id="modalBtnDeleteNotice">Delete</button>
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
            var deleteId = null;

            // Notice
            var notices = <?php echo json_encode($notices->toArray()); ?>;

            $('#btnAddNewNotice').click(function () {
                $("#form").attr('action', "{{ route('privacy_notice_add') }}");
                $("#name").val('');
                $("#description").val('');
                $("#id").val('');
                $('#addEditRowNotice').removeClass('d-none');
                $('#btnAddNewNotice').addClass('d-none');
                $('#addEditTitleNotice').html('Add a New Notice');
            });

            $(document).on("click","#btnCancelNotice",function() {
                $("#name").val('');
                $("#description").val('');
                $("#id").val('');
                $('#addEditRowNotice').addClass('d-none');
                $('#btnAddNewNotice').removeClass('d-none');
                $('#btnAddNewNotice').css('display' , 'block');
                $('#addEditTitleNotice').html('Add a New Notice');
            });

            $('body').on('click', '.btnEditNotice', function () {
                var index = $(this).data('index');
                var notice = notices[index];
                if(notice){
                    $("#form").attr('action', "{{ route('privacy_notice_edit') }}");
                    $("#name").val(notice.name);
                    $("#description").val(notice.description);
                    $("#id").val(notice.id);
                    if(notice.status === '1')
                        $("input[name='status'][value='1']").attr("checked", true);
                    else
                        $("input[name='status'][value='0']").attr("checked", true);
                }
                $('#addEditRowNotice').removeClass('d-none');
                $('#btnAddNewNotice').addClass('d-none');
            });

            $('body').on('change', '.statusNotice', function () {
                var status = 0;
                var id = $(this).data('id');

                if ($(this).is(':checked'))
                    status = 1;

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_privacy_notice_change_status') }}",
                    data: { id: id, status: status }
                }).done(function( msg ) {
                    toastr.success('Status Updated!');
                });
            });

            $('body').on('click', '.btnDeleteNotice', function () {
                var id = $(this).data('id');
                deleteId = id;
                $('#deleteModalNotice').addClass('open_modal');
            });

            $('#modalBtnDeleteNotice').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_privacy_notice_delete') }}",
                    data: { id: deleteId }
                }).done(function( country ) {
                    toastr.success('Privacy Notice Deleted!');
                    location.reload();
                });

            });

        })
    </script>
@stop
