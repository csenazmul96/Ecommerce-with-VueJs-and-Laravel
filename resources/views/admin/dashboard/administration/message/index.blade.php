@extends('admin.layouts.main')

@section('additionalCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/themify/css/themify-icons.css') }}" />
@stop

@section('content')

    <div class="row">
        @if(Session::has('message'))
            <div class="grey_bg width_full mb_15 text_center color_green message_">
                <span class="glyphicon glyphicon-ok"></span><em> {!! session('message') !!}</em>
            </div>
        @endif
        <div class="col-md-12">
            <div class="message_content">
                <ul class="head grey_bg">
                    <li>Subject</li>
                    <li>Sender</li>
                    <li>company</li>
                    <li>Unread</li>
                    <li>Date</li>
                </ul>
                @forelse($messages as $key=>$message)
                <div class="home_accordion">
                    <div class="home_accordion_heading" data-toggle="accordion" data-target="#accordion_{{$message->id}}" data-class="accordion">
                    <ul id="{{$message->id}}">
                        <li >{{ $message->subject }}</li>
                        <li>{{ $message->recipient}}</li>
                        <li> @if(!empty($message->user->buyer)){{ $message->user->buyer->company_name}} @endif</li>
                        <li id="reading_status_{{$message->id}}" class="{{ ($message->reading_status == 0) ? 'orange_bg p5' : '' }}" >{{($message->reading_status == 0) ? 'Unread' : 'Read' }}</li>
                        <li >{{ $message->created_at->diffForHumans() }}</li>
                    </ul>
                    </div>
                    <div class="accordion_body width_full    default_accrodion" id="accordion_{{$message->id}}" style="display: none;">
                        <div class="muyer_message width_full   ">
                            <h5 class="mb_15">Dear {{ $message->recipient}}</h5>
                            <p>{{ $message->message}}</p>
                            <h5 class=" mb_5">Attachment</h5>
                            <span class=" width_full    mb_5">
                                @if(empty($message->attachment1) && empty($message->attachment1) && empty($message->attachment1))
                                    <p class="pt-2">No Attachment</p>
                                @else
                                    @if(!empty($message->attachment1))
                                        <a class="show-image" data-href="{{ asset($message->attachment1) }}" href="#">View Attachment</a><br>
                                    @endif

                                    @if(!empty($message->attachment2))
                                        <a class="show-image" data-href="{{ asset($message->attachment2) }}" href="#">View Attachment</a><br>
                                    @endif

                                    @if(!empty($message->attachment3))
                                        <a class="show-image" data-href="{{ asset($message->attachment3) }}" href="#">View Attachment</a><br>
                                    @endif
                                @endif

                                <a class="ly_btn  btn_blue min_width_100p mt_15 btnReplay" role="button" data-message_id="{{ $message->id }}" data-message_user_id="{{ $message->user_id }}"
                                    data-message_subject="{{ $message->subject }}"  data-message_recipient="  @if(!empty($message['user']))  {{ $message['user']->first_name }} {{ $message['user']->last_name }} @endif"
                                    >Reply Message
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal" data-modal="replyModal">
        <div class="modal_overlay" data-modal-close="replyModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_700p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Are you sure want to delete?</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="replyModal"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">
                            <form id="message-form" action="{{ route('send_message_buyer') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" id="message_user_id" value="">
                                <div class="ly-row">
                                    <label class="ly-3 required label_inline">From</label>
                                    <div class="ly-9">
                                        <p id="sender">Shop Hologram</p>
                                        <input type="hidden" name="sender" value="Shop Hologram">
                                    </div>
                                </div>
                                <div class="ly-row">
                                    <label class="ly-3 required label_inline">To</label>
                                    <div class="ly-9 mb_5">
                                        <p id="recipient"></p>
                                        <input class="form_global" type="hidden" name="recipient" id="message_recipient" value="">
                                    </div>
                                </div>

                                <div class="ly-row">
                                    <label class="ly-3">Title</label>
                                    <div class="ly-9 mb_5">
                                        <input class="form_global" name="topics" id="message_subject" value="" class="form-control">
                                    </div>
                                </div>
                                <div class="ly-row">
                                    <label class="ly-3 required label_inline">Message</label>
                                    <div class="ly-9 mb_5">
                                        <textarea name="message" class="form-control" cols="30" rows="10" placeholder="Write your message here ...." required></textarea>
                                    </div>
                                </div>
                                <div class="ly-row">
                                    <label class="ly-3 required label_inline"></label>
                                    <div class="ly-9 mb_5">
                                        <p class="font-italic">File type allowed .jpg, .gif, .png, .pdf, .xls, .xlsx</p>
                                    </div>
                                </div>
                                <div class="ly-row">
                                    <label class="ly-3   label_inline"></label>
                                    <div class="ly-9 mb_5">
                                        <span> <i class="fa fa-paperclip" aria-hidden="true"></i>  File 1</span>
                                        <input class="form_global" type="file" id="attachment1" name="attachment1">
                                    </div>
                                </div>
                                <div class="ly-row">
                                    <label class="ly-3   label_inline"></label>
                                    <div class="ly-9 mb_5">
                                        <span> <i class="fa fa-paperclip" aria-hidden="true"></i>  File 2</span>
                                        <input class="form_global" type="file" id="attachment2" name="attachment2">
                                    </div>
                                </div>
                                <div class="ly-row">
                                    <label class="ly-3   label_inline"></label>
                                    <div class="ly-9 mb_5">
                                        <span><i class="fa fa-paperclip" aria-hidden="true"></i> File 3</span>
                                        <input class="form_global" type="file" id="attachment3" name="attachment3">
                                    </div>
                                </div>
                                <div class="ly-row mb_15">
                                    <div class="ly-3"></div>
                                    <div class="ly-9">
                                        <button type="submit" id="btnMessageSend" class="ly_btn   btn_blue float_right" >Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="message-replay-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabelLarge">Send Message To Buyer  </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal" data-modal="modalShowImage">
        <div class="modal_overlay" data-modal-close="modalShowImage"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_470p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Document</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="modalShowImage"></span>
                        </div>
                    </div>
                    <div class="modal_content">
                        <div class="ly-wrap-fluid">
                            <div class="ly-row">
                                <div class="ly-12">
                                    <img id="img" src="" width="100%">
                                </div>
                                <div class="ly-12">
                                    <div class="display_table m15">
                                        <div class="float_right">
                                            <button class="ly_btn btn_danger text_center width_150p " data-modal-close="modalShowImage">Close</button>
                                            <a class="ly_btn btn_blue width_150p text_center" id="btnDownload" download>Download</a>
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
    <script>
        $(function (){

            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            $(" .home_accordion_heading ul").click(function () {
                $(this).find('li').removeClass('orange_bg');
                var message_count = $('#message_count').text();
                if(message_count > 0){
                    message_count--;
                }
                $('#message_count').text(message_count);
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    url: "{{ route('all_message_status') }}",
                    data: {"_token": "{{ csrf_token() }}","id": id},
                }).done(function( response ) {
                    if (response.success) {
                        $("#reading_status_"+id+"").html('Read')
                    } else {
                        alert(response.message);
                    }
                });
            });

            $('.btnReplay').click(function () {
                var message_id = $(this).data('message_id');
                var message_user_id = $(this).data('message_user_id');
                var message_subject = $(this).data('message_subject');
                var message_recipient = $(this).data('message_recipient');
                var targeted_modal_class = 'replyModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
                $('#message_id').val(message_id);
                $('#message_user_id').val(message_user_id);
                $('#message_subject').val(message_subject);
                $('#recipient').html(message_recipient);
                $('#message_recipient').val(message_recipient);
            });

            $('.show-image').click(function (e) {
                e.preventDefault();

                var url = $(this).data('href');

                $('#img').attr('src', url);
                $('#btnDownload').attr('href', url);
                var targeted_modal_class = 'modalShowImage';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });
        });
    </script>
@stop


