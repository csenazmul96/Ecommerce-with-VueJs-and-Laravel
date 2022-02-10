<form class="form-horizontal" method="POST" id="form-settings">
    
    <div class="form_row">
        <div class="label_inline fw_500 required width_150p">
            <label for="company_description">Minimum Order: </label>            
        </div>
        <div class="form_inline">
            <input id="company_description" type="text" class="form_global" name="min_order" value="{{ $vendor->min_order }}" id="min_order" placeholder="$">
        </div>
    </div>
    <div class="text_right m15">
        <div class="display_inline mr_0">
            <button class="ly_btn  btn_blue min_width_100p " id="btnSaveSettings">Save</button>
        </div>
    </div>
</form>