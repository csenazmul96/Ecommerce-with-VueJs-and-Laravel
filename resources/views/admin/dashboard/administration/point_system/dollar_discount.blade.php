<form class="form-horizontal" method="POST" id="form-discounts">
    <div class="form_row">
        <div class="label_inline width_150p">
            <label for="points_use">Point Use:</label>             
        </div>
        <div class="form_inline">
            <input type="number" min="0" class="form_global" name="points_use" value="{{ isset($pointDiscount->points_use) ? $pointDiscount->points_use : ''  }}" id="points_use">
        </div>
    </div>

    <div class="form_row">
        <div class="label_inline width_150p">
            <label for="dollar_disounts">Dollar Discount: </label> 
        </div>
        <div class="form_inline">
            <input type="number" class="form_global" name="dollar_disounts" value="{{ isset($pointDiscount->dollar_disounts) ? $pointDiscount->dollar_disounts : ''  }}" id="dollar_disounts"  placeholder="$">
        </div>
    </div>

    <div class="text_right m15">
        <div class="display_inline mr_0">
            <button class="ly_btn  btn_blue min_width_100p " id="btnSaveDiscount">SAVE</button>
        </div>
    </div>
</form>