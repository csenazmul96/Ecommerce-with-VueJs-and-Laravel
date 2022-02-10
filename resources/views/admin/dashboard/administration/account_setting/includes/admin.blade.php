
<div class="form_row">
    <div class="label_inline width_150p">
            User Name
    </div>
    <div class="form_inline">
        <input type="text" class="form_global" value="{{ $user->user_name }}" disabled readonly>
    </div>
    <div class="form_inline text_right width_200p">
        User Name cannot be changed.
    </div>
</div>
<div class="form_row">
    <div class="label_inline width_150p">
        First & Last Name
    </div>
    <div class="form_inline pr_8">
        <input type="text" class="form_global" id="adminFirstName" value="{{ $user->first_name }}">
    </div>
    <div class="form_inline">
        <input type="text" class="form_global" id="adminLastName" value="{{ $user->last_name }}">
    </div>
</div>
<div class="form_row">
    <div class="label_inline width_150p">
        Password
    </div>
    <div class="form_inline">
        <input type="password" class="form_global" id="adminPassword" placeholder="*****">
    </div>
</div>
<div class="text_right m15">
    <div class="display_inline mr_0">
        <button class="ly_btn  btn_blue min_width_100p " id="adminBtnSave">Save</button>
    </div>
</div>    