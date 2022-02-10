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
            <table class="table table-bordered statistics-table">
                <thead>
                <tr class="list_item">
                    <th>Name</th>
                    <th>Description</th>
                    <th id="total_amount_sort" class="active show_arrow text-right"> <span>Action</span> </th>
                </tr>
                </thead>

                <tbody id="st-data">
                    @foreach($sections as $section)
                        @if($section->section_name == 'great_offer')
                            <tr>
                                <td>{{ $section->heading }}</td>
                                <td>{{ $section->description }}</td>
                                <td><button class="ly_btn btn_blue width_100p editSection" data-id="{{ $section->id }}" data-heading="{{ $section->heading }}" data-description="{{ $section->description }}">Edit</button></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <br>
    <div id="sectionModal" class="modal" data-modal="sectionModal">
        <div class="modal_overlay" data-modal-close="sectionModal"></div>
        <div class="modal_inner">
            <div class="modal_wrapper modal_850p">
                <div class="item_list_popup">
                    <div class="modal_header display_table">
                        <span class="modal_header_title">Delete Confirmation</span>
                        <div class="float_right">
                            <span class="close_modal" data-modal-close="sectionModal"></span>
                        </div>
                    </div>
                    <div class="modal_content pa15">
                        <form action="{{ route('section_heading_post') }}" method="post">
                            @csrf
                            <input type="hidden" id="id" value="" name="id">
                            <div class="form_row">
                                <div class="label_inline width_200p">
                                    Section Name
                                </div>
                                <div class="form_inline">
                                    <input type="text" id="heading" class="form_global" name="heading" value="{{ old('heading') }}">
                                </div>
                            </div>
                            <div class="form_row">
                                <div class="label_inline width_200p">
                                    Section Description
                                </div>
                                <div class="form_inline ">
                                    <input type="text" id="description" class="form_global" name="description" value="{{ old('description') }}">
                                </div>
                            </div>
                            <div class="text_right m15">
                                <div class="display_inline mr_0">
                                    <button type="submit" class="ly_btn  btn_blue min_width_100p ">Save</button>
                                </div>
                            </div>
                        </form>
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

            $(".editSection").click(function(){
                var id = $(this).data('id')
                var heading = $(this).data('heading')
                var description = $(this).data('description')
                $('#description').val(description)
                $('#heading').val(heading)
                $('#id').val(id)
                $("#sectionModal").addClass('open_modal')
            });

        })
    </script>
@stop
