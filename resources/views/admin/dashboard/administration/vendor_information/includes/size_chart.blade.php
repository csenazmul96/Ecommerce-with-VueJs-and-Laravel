<form action="{{ route('admin_size_chart_post') }}" method="POST">
    @csrf
    <div class="form_row">
        <div class="label_inline fw_500 required width_150p  align_top">
            Description
        </div>
        <div class="form_inline">
            <textarea class="form_global" name="size_chart_editor" id="size_chart_editor" rows="10" cols="80">{{ $user->vendor->size_chart }}</textarea>
        </div>
    </div>

    <br>

    <div class="text_right m15">
        <div class="display_inline mr_0">
            <button class="ly_btn  btn_blue min_width_100p " id="btnSizeChartSubmit">Save</button>
        </div>
    </div>
</form>