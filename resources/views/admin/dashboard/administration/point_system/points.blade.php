<div class="ly_accrodion">
    <div class="ly_accrodion_heading">
        <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#addRow" data-class="accordion" id="addBtnRow">
            <span id="addEditTitle">{{ old('inputAdd') == '0' ? 'Edit Point' : 'Add Point' }}</span>
        </div>
    </div>
    <div class="accordion_body default_accrodion open" id="addRow" style="">

        <form class="form-horizontal" enctype="multipart/form-data" id="form"
              method="post" action="{{ (old('inputAdd') == '1') ? route('admin_points_edit_post') : route('admin_points_add_post') }}">
            @csrf

            <input type="hidden" name="inputAdd" id="inputAdd" value="{{ old('inputAdd') }}">
            <input type="hidden" name="pointId" id="pointId" value="{{ old('pointId') }}">

            <div class="form_row">
                <div class="label_inline required width_150p">
                    Status:
                </div>
                <div class="form_inline">
                    <div class="custom_radio">
                        <input type="radio" id="statusActive" name="status" value="1"
                            {{ (old('status') == '1' || empty(old('status'))) ? 'checked' : '' }}>
                        <label for="statusActive">Active</label>
                    </div>
                    <div class="custom_radio">
                        <input type="radio" id="statusInactive" name="status" value="0" 
                            {{ old('status') == '0' ? 'checked' : '' }}>
                        <label for="statusInactive">Inactive</label>
                    </div>
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline width_150p">
                    <label for="point_type">Point Type</label>
                </div>
                <div class="form_inline">
                    <div class="select">
                        <select class="form_global" name="point_type" id="point_type">
                             <option value="Percentage discount by order amount">Percentage discount by order amount</option>
                             <option value="Unit price discount by order amount">Unit price discount by order amount</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form_row">
                <div class="label_inline width_150p">
                    <label for="cost_dollars">Discount Details</label>             
                </div>
                <div class="form_inline">
                    <div class="point_table">
                        <table>
                            <tr>
                                <th>Requirement</th>
                                <th>Discount Options</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id="from_price_1" name="from_price_1" size="8" class=""> point
                                </td>
                                <td>
                                    <div class="form-check custom_checkbox">
                                        <input class="form-check-input" type="checkbox" value="1" id="free_shipping_1" name="free_shipping_1">
                                        <label class="form-check-label" for="free_shipping_1">
                                            Free Shipping
                                        </label>
                                    </div>
                                    <span class="dollarSpan"></span>
                                    <input type="text" id="discount_1" name="discount_1" size="3" class=""> <span class="discountSpan"></span>
                                    <br>
                                    <br>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="create_item_color">
                <div class="float_right">
                    <div class="display_inline">
                        <button class="ly_btn  btn_blue" id="btnCancel" data-toggle="accordion" data-target="#addRow" data-class="accordion" class="accordion_heading" data-class="accordion" >Cancel</button>
                        <input type="submit" id="btnSubmit" class="ly_btn  btn_blue" value="{{ old('inputAdd') == '0' ? 'Update' : 'Add' }}">
                    </div>
                </div>
            </div>
            <br>
        </form>
    </div>
</div>


<div class="ly-row">
    <div class="ly-12">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Point</th>
                <th>Discount</th>
                <th>Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            @foreach($points as $point)
                <tr>
                    <td>{{ $point->from_price_1 }}</td>
                    <td>
                        @if($point->free_shipping_1 != 0)
                            Free Shipping
                            
                        @elseif($point->point_type === 'Unit price discount by order amount')
                            $ {{ $point->unit_price_discount_1 }}
                        @elseif($point->point_type === 'Percentage discount by order amount')
                            {{ $point->percentage_discount_1 }} %
                        @endif
                    </td>
                    <td>{{ $point->point_type }}</td>
                    <td>
                        @if ($point->status == 1)
                            Active
                        @else
                            Inactive
                        @endif
                    </td>
                    <td>
                        <a class="btnEdit link" data-id="{{ $point->id }}" data-index="{{ $loop->index }}" role="button" style="color: blue">Edit</a> |
                        <a class="btnDelete link" data-id="{{ $point->id }}" role="button" style="color: red">Delete</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
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