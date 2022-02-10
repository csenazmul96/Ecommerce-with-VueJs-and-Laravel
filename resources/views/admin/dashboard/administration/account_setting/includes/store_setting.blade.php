<h4>Settings</h4>

<div class="row">
    <div class="col-md-4">
        Allow users who have not logged in to view your front page (not inside)?
    </div>

    <div class="col-md-5">
        <label for="qus1Yes" class="custom-control custom-radio">
            <input id="qus1Yes" name="qus1" type="radio" class="custom-control-input"
                   value="1" {{ $user->vendor->setting_not_logged == 1 ? 'checked' : '' }}>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Yes</span>
        </label>
        <label for="qus1No" class="custom-control custom-radio">
            <input id="qus1No" name="qus1" type="radio" class="custom-control-input" value="0" {{ $user->vendor->setting_not_logged == 0 ? 'checked' : '' }}>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">No</span>
        </label>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        Allow unverified users to access product/listing pages?
    </div>

    <div class="col-md-5">
        <label for="qus2Yes" class="custom-control custom-radio">
            <input id="qus2Yes" name="qus2" type="radio" class="custom-control-input"
                   value="1" {{ $user->vendor->setting_unverified_user == 1 ? 'checked' : '' }}>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Yes</span>
        </label>
        <label for="qus2No" class="custom-control custom-radio">
            <input id="qus2No" name="qus2" type="radio" class="custom-control-input" value="0" {{ $user->vendor->setting_unverified_user == 0 ? 'checked' : '' }}>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">No</span>
        </label>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        Allow unverified users to checkout?
    </div>

    <div class="col-md-5">
        <label for="qus3Yes" class="custom-control custom-radio">
            <input id="qus3Yes" name="qus3" type="radio" class="custom-control-input"
                   value="1" {{ $user->vendor->setting_unverified_checkout == 1 ? 'checked' : '' }}>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Yes</span>
        </label>
        <label for="qus3No" class="custom-control custom-radio">
            <input id="qus3No" name="qus3" type="radio" class="custom-control-input" value="0" {{ $user->vendor->setting_unverified_checkout == 0 ? 'checked' : '' }}>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">No</span>
        </label>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        Sort products by
    </div>

    <div class="col-md-5">
        <label for="qus4Yes" class="custom-control custom-radio">
            <input id="qus4Yes" name="qus4" type="radio" class="custom-control-input"
                   value="1" {{ $user->vendor->setting_sort_activation_date == 1 ? 'checked' : '' }}>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Activation Date</span>
        </label>
        <label for="qus4No" class="custom-control custom-radio">
            <input id="qus4No" name="qus4" type="radio" class="custom-control-input" value="0" {{ $user->vendor->setting_sort_activation_date == 0 ? 'checked' : '' }}>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Last Update Date</span>
        </label>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        Consolidation
    </div>

    <div class="col-md-5">
        <label for="qus5Yes" class="custom-control custom-radio">
            <input id="qus5Yes" name="qus5" type="radio" class="custom-control-input"
                   value="1" {{ $user->vendor->setting_consolidation == 1 ? 'checked' : '' }}>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Yes</span>
        </label>
        <label for="qus5No" class="custom-control custom-radio">
            <input id="qus5No" name="qus5" type="radio" class="custom-control-input" value="0" {{ $user->vendor->setting_consolidation == 0 ? 'checked' : '' }}>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">No</span>
        </label>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        Estimated Shipping Charge
    </div>

    <div class="col-md-5">
        <label for="qus6Yes" class="custom-control custom-radio">
            <input id="qus6Yes" name="qus6" type="radio" class="custom-control-input"
                   value="1" {{ $user->vendor->setting_estimated_shipping_charge == 1 ? 'checked' : '' }}>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Yes</span>
        </label>
        <label for="qus6No" class="custom-control custom-radio">
            <input id="qus6No" name="qus6" type="radio" class="custom-control-input" value="0" {{ $user->vendor->setting_estimated_shipping_charge == 0 ? 'checked' : '' }}>
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">No</span>
        </label>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-right">
        <button class="btn btn-primary" id="btnStoreSettingSave">Save</button>
    </div>
</div>