<div class="form_row">
    <div class="label_inline fw_500 width_150p align_top">
        Company Description
    </div>
    <div class="form_inline">
        <textarea class="form_global{{ $errors->has('company_description') ? ' is-invalid' : '' }}"
            rows="5" name="company_description" id="company_description">@if(!empty($user->vendor->company_info)) {{ empty(old('company_description')) ? ($errors->has('company_description') ? '' : $user->vendor->company_info) : old('company_description') }} @endif</textarea>
    </div>
</div>

<div class="ly-wrap-fluid">
    <div class="ly-row">
        <div class="ly-6 pl_0 pr_60">
            <h1 class="inner_title no_border"> Showroom Address</h1>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    Address
                </div>
                <div class="form_inline">
                    <input type="text" class="form_global{{ $errors->has('showroom_address') ? ' is-invalid' : '' }}" name="showroom_address" id="showroom_address"
                            value="@if(!empty($user->vendor->billing_address)) {{ empty(old('showroom_address')) ? ($errors->has('showroom_address') ? '' : $user->vendor->billing_address) : old('showroom_address') }} @endif">
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    City
                </div>
                <div class="form_inline">
                    <input type="text" class="form_global{{ $errors->has('showroom_city') ? ' is-invalid' : '' }}" name="showroom_city" id="showroom_city"
                            value="@if(!empty($user->vendor->billing_city)) {{ empty(old('showroom_city')) ? ($errors->has('showroom_city') ? '' : $user->vendor->billing_city) : old('showroom_city') }} @endif">
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    State
                </div>
                <div class="form_inline display_inline">
                    <div class="select">
                        <select class="form_global" name="showroom_state" id="showroom_state">
                            <option value="">Select State</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    Zip Code
                </div>
                <div class="form_inline">
                    <input type="text" class="form_global{{ $errors->has('showroom_zip_code') ? ' is-invalid' : '' }}" name="showroom_zip_code" id="showroom_zip_code"
                            value="@if(!empty($user->vendor->billing_zip)) {{ empty(old('showroom_zip_code')) ? ($errors->has('showroom_zip_code') ? '' : $user->vendor->billing_zip) : old('showroom_zip_code') }} @endif">
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    Country
                </div>
                <div class="form_inline">
                    <div class="select">
                        <select class="form_global{{ $errors->has('showroom_country') ? ' is-invalid' : '' }}" name="showroom_country" id="showroom_country">
                            <option value="">Select Country</option>

                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $user->vendor->billing_country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    Tel
                </div>
                <div class="form_inline">
                    <input type="text" class="form_global{{ $errors->has('showroom_tel') ? ' is-invalid' : '' }}" name="showroom_tel" id="showroom_tel"
                            value="{{ empty(old('showroom_tel')) ? ($errors->has('showroom_tel') ? '' : $user->vendor->billing_phone) : old('showroom_tel') }}">
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 width_150p">
                    Alt
                </div>
                <div class="form_inline">
                    <input type="text" class="form_global{{ $errors->has('showroom_alt') ? ' is-invalid' : '' }}" name="showroom_alt" id="showroom_alt"
                            value="{{ empty(old('showroom_alt')) ? ($errors->has('showroom_alt') ? '' : $user->vendor->billing_alternate_phone) : old('showroom_alt') }}">
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    Fax
                </div>
                <div class="form_inline">
                    <input type="text" class="form_global{{ $errors->has('showroom_fax') ? ' is-invalid' : '' }}" name="showroom_fax" id="showroom_fax"
                            value="{{ empty(old('showroom_fax')) ? ($errors->has('showroom_fax') ? '' : $user->vendor->billing_fax) : old('showroom_fax') }}">
                </div>
            </div>
        </div>
        <div class="ly-6 pl_0 pr_60">
            <h1 class="inner_title no_border"> Warehouse Address</h1>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    Address
                </div>
                <div class="form_inline">
                    <input type="text" class="form_global{{ $errors->has('warehouse_address') ? ' is-invalid' : '' }}" name="warehouse_address" id="warehouse_address"
                            value="{{ empty(old('warehouse_address')) ? ($errors->has('warehouse_address') ? '' : $user->vendor->factory_address) : old('warehouse_address') }}">
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    City
                </div>
                <div class="form_inline">
                    <input type="text" class="form_global{{ $errors->has('warehouse_city') ? ' is-invalid' : '' }}" name="warehouse_city" id="warehouse_city"
                            value="{{ empty(old('warehouse_city')) ? ($errors->has('warehouse_city') ? '' : $user->vendor->factory_city) : old('warehouse_city') }}">
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    State
                </div>
                <div class="form_inline display_inline">
                    <div class="select">
                        <select class="form_global" name="warehouse_state" id="warehouse_state">
                            <option value="">Select State</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    Zip Code
                </div>
                <div class="form_inline">
                    <input type="text" class="form_global{{ $errors->has('warehouse_zip_code') ? ' is-invalid' : '' }}" name="warehouse_zip_code" id="warehouse_zip_code"
                            value="{{ empty(old('warehouse_zip_code')) ? ($errors->has('warehouse_zip_code') ? '' : $user->vendor->factory_zip) : old('warehouse_zip_code') }}">
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    Country
                </div>
                <div class="form_inline">
                    <div class="select">
                        <select class="form_global{{ $errors->has('warehouse_country') ? ' is-invalid' : '' }}" name="warehouse_country" id="warehouse_country">
                            <option value="">Select Country</option>

                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $user->vendor->factory_country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    Tel
                </div>
                <div class="form_inline">
                    <input type="text" class="form_global{{ $errors->has('warehouse_tel') ? ' is-invalid' : '' }}" name="warehouse_tel" id="warehouse_tel"
                            value="{{ empty(old('warehouse_tel')) ? ($errors->has('warehouse_tel') ? '' : $user->vendor->factory_phone) : old('warehouse_tel') }}">
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 width_150p">
                    Alt
                </div>
                <div class="form_inline">
                    <input type="text" class="form_global{{ $errors->has('warehouse_alt') ? ' is-invalid' : '' }}" name="warehouse_alt" id="warehouse_alt"
                            value="{{ empty(old('warehouse_alt')) ? ($errors->has('warehouse_alt') ? '' : $user->vendor->factory_alternate_phone) : old('warehouse_alt') }}">
                </div>
            </div>
            <div class="form_row">
                <div class="label_inline fw_500 required width_150p">
                    Fax
                </div>
                <div class="form_inline">
                    <input type="text" class="form_global{{ $errors->has('warehouse_fax') ? ' is-invalid' : '' }}" name="warehouse_fax" id="warehouse_fax"
                            value="{{ empty(old('warehouse_fax')) ? ($errors->has('warehouse_fax') ? '' : $user->vendor->factory_fax) : old('warehouse_fax') }}">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text_right m15">
    <div class="display_inline mr_0">
        <button class="ly_btn  btn_blue min_width_100p" id="companyInfoSave">Save</button>
    </div>
</div>