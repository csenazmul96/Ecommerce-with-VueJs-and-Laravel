<?php use App\Enumeration\Availability; ?>
@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/ezdz/jquery.ezdz.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="text_right m15">
        <div class="display_inline mr_0">
            <button type="submit" class="ly_btn  btn_blue min_width_100p addNewFaq">Add New Faq</button>
        </div>
    </div>

    <div class="item_size_content">
        <table class="table header-border">
            <thead>
            <tr>
                <th># <span class="data_table_arrow"></span> </th>
                <th>Question</th>
                <th>Answer</th>
                <th class="width_150p">Action</th>
            </tr>
            </thead>
            <tbody id="PostTbody">
                @if(count($faqs) > 0)
                    @foreach($faqs as $faq)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $faq->question }}</td>
                            <td>{{ $faq->answer }}</td>
                            <td>
                                <button class="ly_btn btn_blue btn_common editFaq" data-id="{{ $faq->id }}" data-index="{{ $loop->index }}">Edit</button>
                                <button class="ly_btn btn_danger btn_common deleteFaq" data-id="{{ $faq->id }}" data-index="{{ $loop->index }}">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class="modal {{count($errors) > 0 ? 'open_modal' : null}}" data-modal="addFaq" id="addFaq">
        <div class="modal_overlay" data-modal-close="addFaq"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_700p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Add New FAQ</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="addFaq"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <form class="form-horizontal" method="post" action="{{ route('add_new_faq_post') }}" id="form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="faq_id" name="id" value="">
                            <div class="ly-wrap-fluid">
                                <div class="ly-row">
                                    <div class="ly-12">
                                        <div class="form_row">
                                            <div class="label_inline required width_150p"> Question </div>
                                            <div class="form_inline display_inline">
                                                <input type="text" id="question" class="form_global" placeholder="Question" name="question" value="">
                                                @if ($errors->has('question'))
                                                    <span class="text-danger">{{ $errors->first('question') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form_row">
                                            <div class="label_inline required width_150p">Answer</div>
                                            <div class="form_inline display_inline">
                                                <textarea class="form_global" name="answer" id="answer" placeholder="Answer"></textarea>
                                                @if ($errors->has('answer'))
                                                    <span class="text-danger">{{ $errors->first('answer') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ly-row">
                                    <div class="ly-12">
                                        <div class="display_table m15">
                                            <div class="float_right">
                                                <button class="ly_btn btn_danger width_150p " data-modal-close="addFaq">Close</button>
                                                <button class="ly_btn btn_blue width_150p" id="addMasterColorSave">Save</button>
                                            </div>
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

    <div id="deleteModal" class="modal" data-modal="deleteModal">
        <div class="modal_overlay" data-modal-close="deleteModal"></div>
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
                                        <button data-modal-close="deleteModal" class="ly_btn btn_grey min_width_100p close_item_color">Close</button>
                                    </div>
                                    <div class="display_inline mr_0">
                                        <button class="ly_btn btn_blue min_width_100p" id="modalBtnDelete">Yes</button>
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
            var message = '{{ session('message') }}';
            if (message != '')
                toastr.success(message);
            var deleteId = null;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var faqs = <?php echo json_encode($faqs); ?>;

            $('.addNewFaq').click(function(){
                $("#faq_id").val('');
                $("#question").val('');
                $("#answer").val('');
                $(".modal_header_title").html('Add New FAQ')
                $('#addFaq').addClass('open_modal')
            })

            $(".editFaq").click(function(){
                var id = $(this).data('id')
                var faq = faqs.find(x => x.id === id);
                if(faq){
                    $("#faq_id").val(faq.id);
                    $("#question").val(faq.question);
                    $("#answer").val(faq.answer);
                    $(".modal_header_title").html('Edit FAQ')

                    $('#addFaq').addClass('open_modal')
                }
            });
            $(".deleteFaq").click(function(){
                $('#deleteModal').addClass('open_modal');
                deleteId = $(this).data('id');
            });

            $("#modalBtnDelete").click(function(){
                $.ajax({
                    method: "POST",
                    url: "{{ route('faq_delete') }}",
                    data: { id: deleteId }
                }).done(function( data ) {
                    location.reload()
                });
            });
        });
    </script>
@stop
