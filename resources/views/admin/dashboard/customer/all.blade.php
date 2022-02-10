@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.5.4/css/buttons.dataTables.min.css" rel="stylesheet">
    <style>
        .table th {
           font-size: 12px;
        }
        #customer_off thead:hover .filter_customer:after, .filter_customer span.active.up:after {
            float: right !important;
            font-family: "Font Awesome 5 Free";
            content: "\f0d8";
            font-size: 10px;
            font-weight: 900;
            position: relative;
            right: 20px;
            top: 0;
        }
        #customer_off thead:hover .filter_customer span.active:after, .filter_customer span.active.down:after {
            float: right !important;
            font-family: "Font Awesome 5 Free";
            content: "\f0d7";
            font-size: 10px;
            font-weight: 900;
            position: relative;
            right: 26px;
            top: 5px;
        }
        .filter_customer {
            cursor: pointer;
        }
        .filter_customer span.active.up:after {
            float: right !important;
            font-family: "Font Awesome 5 Free";
            content: "\f0d8";
            font-size: 10px;
            font-weight: 900;
            position: relative;
            right: 26px !important;
            top: 0;
        }
    </style>
@stop

@section('content')
    <div class="ly-wrap-fluid">
        <div class="ly-row">
            <div class="ly-2 pl_0 pr_60">
                <form method="get">
                    <select class="form_global" name="sort_by" onchange="this.form.submit()">
                        <option value="all" {{ (Request::get('sort_by')=='all')?'selected':''}}>All</option>
                        <option value="block" {{ (Request::get('sort_by')=='block')?'selected':''}}>Block</option>
                        <option value="point_asc" {{ (Request::get('sort_by')=='point_asc')?'selected':''}}>Point (ascending)</option>
                        <option value="point_desc" {{ (Request::get('sort_by')=='point_desc')?'selected':''}}>Point (descending)</option>
                    </select>
                </form>
            </div>
            <div class="ly-6 pl_0 pr_60">
                <form method="get" class="form_inline" id="customer_form" role="form" style="display: contents">
                    <div class="form_row">
                        <div class="form_inline display_inline pr_8">
                            <input  value="{{ (request()->get('customer_name') != null ) ? request()->get('customer_name'): '' }}" type="text" class="form_global" name="customer_name" placeholder="Customer Name">
                        </div>
                        <div class="form_inline display_inline pr_8">
                            <input  value="{{ (request()->get('customer_email') != null ) ? request()->get('customer_email'): '' }}" type="text" class="form_global" name="customer_email" placeholder="Customer Email">
                        </div>
                        <input  value="" id="filter_column" type="hidden" class="form_global" name="filter">
                        <input  value="" id="filter_value" type="hidden" class="form_global" name="value">
                        <div class="form_inline display_inline">
                            <button class="btn ly_btn btn_blue" type="submit"><i class="fa fa-search"></i> search</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="ly-4 pl_0 pr_60">
                <div class="form_row">
                    <div class="display_inline mr_0 float_right">
                        <a class="ly_btn btn_blue btn-sm" href="{{route('admin_all_buyer')}}">
                            <i class="fa fa-arrow-left"></i> Reset
                        </a>
                    </div>
                </div>
            </div>

            <div class="ly-12 customer_list">
                <table class="table" id="customer_off">
                    <thead>
                    <tr>
                        <th class="filter_customer name" data-type="name" data-value="asc" style="width: 10%">Name  <span class="active "></span> </th>
                        <th class="filter_customer email" data-type="email" data-value="asc" style="width: 15%">Email <span class="active "></span></th>
                        <th>Block</th>
                        {{-- <th>Age Group</th>
                        <th>Skin Type</th> --}}
                        <th class="filter_customer create_at" data-type="create_at" data-value="asc" style="width: 10%">Created At <span class="active "></span></th>
                        <th class="filter_customer last_login" data-type="last_login" data-value="asc" style="width: 10%">Last Login <span class="active "></span></th>
                        <th>Points</<th>
                        <th>Mailing List</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach($buyers as $buyer)
                        <tr>
                            <td>@if($buyer->user) {{  $buyer->user->first_name .' '. $buyer->user->last_name  }}@endif</td>
                            <td>@if($buyer->user){{   $buyer->user->email }}@endif</td>
                            <td>
                                @if($buyer->user)
                                <div class="form-check custom_checkbox">
                                    <input class="form-check-input block" type="checkbox" id="checkbox-block-{{ $buyer->user->id }}" value="1" data-id="{{ $buyer->user->id }}" {{ $buyer->user->active == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="checkbox-block-{{ $buyer->user->id }}">
                                        &nbsp;
                                    </label>
                                </div>
                                @endif
                            </td>
                            {{-- <td>
                                @if($buyer->ageGroup){{ $buyer->ageGroup->lower_limit }} - {{ $buyer->ageGroup->upper_limit }} @endif
                            </td>
                            <td>
                                @if($buyer->skinType){{ $buyer->skinType->type }} @endif
                            </td> --}}
                            <td>{{ date('m/d/Y g:i:s a', strtotime($buyer->created_at)) }}</td>
                            <td>
                                @if(!empty($buyer->userLastLogin->created_at))
                                {{ date('m/d/Y g:i:s a', strtotime($buyer->userLastLogin->created_at)) }}
                                @endif
                            </td>
                            <td>{{ number_format(((float) $buyer->points - (float) $buyer->points_spent), 2, '.', '') }}</td>
                            <td>
                                <div class="form-check custom_checkbox">
                                    <input class="form-check-input mailing_list" type="checkbox" id="checkbox-mailing-list-{{ $buyer->id }}" value="1" data-id="{{ $buyer->id }}" data-user_id="{{ $buyer->user_id }}" data-billing_phone="{{ $buyer->billing_phone }}" {{ $buyer->mailing_list == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="checkbox-mailing-list-{{ $buyer->id }}">
                                        &nbsp;
                                    </label>
                                </div>
                            </td>
                            <td>
                                <a class="link btnEdit" href="{{ route('admin_buyer_edit', ['buyer' => $buyer->id]) }}" style="color: blue">Edit</a> |
                                <a class="link btnDelete" data-id="{{ $buyer->id }}" role="button" style="color: red">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="pagination">
                    {!! $buyers->appends(request()->input())->links() !!}
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
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.4/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.4/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customer').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            } );
        } );
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            let searchParams = new URLSearchParams(window.location.search)
            searchParams.has('filter')
            var param = searchParams.get('filter')
            var value =  searchParams.get('value') 

            var res = ".".concat(param); 
            if(param !== '' && value !== ''){
                if(value == 'asc'){  
                    $(res).find('.active').removeClass('up');
                    $(res).find('.active').addClass('down');
                    $(res).attr('data-value', 'desc');
                }else{ 
                    $(res).find('.active').removeClass('down');
                    $(res).find('.active').addClass('up');
                    $(res).attr('data-value', 'asc');
                }
            }

            $(".filter_customer").click(function(){
                var type = $(this).data('type');
                var value = $(this).data('value');
                if(value=='asc'){
                    $(this).data('value', 'desc');
                }else{
                   $(this).data('value', 'asc');
                }
                $('#filter_column').val(type);
                $('#filter_value').val(value);
                $("#customer_form").submit(); // Submit the form
            });

            $('.status').change(function () {
                var status = 0;
                var id = $(this).data('id');

                if ($(this).is(':checked'))
                    status = 1;

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_buyer_change_status') }}",
                    data: { id: id, status: status }
                }).done(function( msg ) {
                    toastr.success('Status Updated!');
                });
            });

            $('.mailing_list').change(function () {
                var mailing_list = 0;
                var id = $(this).data('id');
                var user_id = $(this).data('user_id');
                var billing_phone = $(this).data('billing_phone');

                if ($(this).is(':checked'))
                    mailing_list = 1;

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_buyer_change_mailing_list') }}",
                    data: { id: id, user_id: user_id, billing_phone: billing_phone, mailing_list: mailing_list }
                }).done(function( msg ) {
                    toastr.success('Mailing List Updated!');
                });
            });

            $('.block').change(function () {
                var status = 0;
                var id = $(this).data('id');

                if ($(this).is(':checked'))
                    status = 1;

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_buyer_change_block') }}",
                    data: { id: id, status: status }
                }).done(function( msg ) {
                    toastr.success('Status Updated!');
                });
            });

            var selectedId;

            $('.btnDelete').click(function () {
                var targeted_modal_class = 'deleteModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
                selectedId = $(this).data('id');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_buyer_delete') }}",
                    data: { id: selectedId }
                }).done(function( msg ) {
                    location.reload();
                });
            });
        });

    </script>
@stop
