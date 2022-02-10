@extends('admin.layouts.main')

@section('additionalCSS')
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/themify/css/themify-icons.css') }}" />
@stop

@section('content')

<section class="section main_content_sec">
    <div class="container">
        <div class="row">
            <div class="col-md-1 col-lg-1"></div>
            <div class="col-md-10 col-lg-10 col-sm-12">
                <div class="shipping_cart_area2 signup_form">
                    <form class="" action="{{ route('customer_register_post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="ly_card">
                            <div class="ly_card_heading">
                                <h5 class="mb-0"> Customer Information</h5>
                            </div>
                            <div class="ly_card_body">
                                <div class="ly-wrap-fluid">
                                    <div class="ly-row">
                                        <div class="ly-6 pl_0 pl_60">
                                            <div class="form_row {{ $errors->has('firstName') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    First Name
                                                </div>
                                                <div class="form_inline">
                                                    <input type="text" class="form_global" id="firstName" name="firstName" value="{{ old('firstName') }}" placeholder="First name">
                                                    @if ($errors->has('firstName'))
                                                        <div class="form-control-feedback">{{ $errors->first('firstName') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ly-6">
                                            <div class="form_row {{ $errors->has('lastName') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    Last Name
                                                </div>
                                                <div class="form_inline">
                                                    <input type="text" class="form_global" id="lastName" name="lastName" value="{{ old('lastName') }}" placeholder="Last name">
                                                    @if ($errors->has('lastName'))
                                                        <div class="form-control-feedback">{{ $errors->first('lastName') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ly-row">
                                        <div class="ly-6 pl_0 pl_60">
                                            <div class="form_row {{ $errors->has('email') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    Email
                                                </div>
                                                <div class="form_inline">
                                                    <input type="email" class="form_global" id="email" name="email" value="{{ old('email') }}" placeholder="email">
                                                    @if ($errors->has('email'))
                                                        <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ly-6">
                                            <div class="form_row {{ $errors->has('password') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    Password
                                                </div>
                                                <div class="form_inline">
                                                    <input type="text" class="form_global" id="password" name="password" value="{{ old('password') }}" placeholder="Password">
                                                    @if ($errors->has('password'))
                                                        <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="ly-row">
                                        <div class="ly-6 pl_0 pl_60">
                                            <div class="form_row {{ $errors->has('age_group') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    Age
                                                </div>
                                                <div class="form_inline">
                                                    <div class="select">
                                                        <select class="form_global form-control-rounded form-control-sm" name="age_group">
                                                            <option value="">Select Age</option>
                                                            @foreach($ageGroups as $age)
                                                                <option value="{{ $age->id }}" {{ old('age_group') == $age->id ? 'selected' : '' }}>{{ $age->lower_limit }} to {{ $age->upper_limit }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('age_group'))
                                                        <div class="form-control-feedback">{{ $errors->first('age_group') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ly-6">
                                            <div class="form_row {{ $errors->has('skin_type') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    Skin Type
                                                </div>
                                                <div class="form_inline">
                                                    <div class="select">
                                                        <select class="form_global form-control-rounded form-control-sm" name="skin_type">
                                                            <option value="">Select Skin Type</option>
                                                            @foreach($skinTypes as $skin)
                                                                <option value="{{ $skin->id }}" {{ old('skin_type') == $skin->id ? 'selected' : '' }}>{{ $skin->type }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('skin_type'))
                                                        <div class="form-control-feedback">{{ $errors->first('skin_type') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="ly_card">
                            <div class="ly_card_heading">
                                <h5 class="mb-0"> Shipping Address</h5>
                            </div>
                            <div class="ly_card_body">
                                <div class="ly-wrap-fluid">
                                    <div class="ly-row">
                                        <div class="ly-12 pl_60 pr_60">
                                            <div class="form_row">
                                                <div class="label_inline width_150p">
                                                    Location
                                                </div>
                                                <div class="form_inline">
                                                    <div class="custom_radio">
                                                        <input class="location" type="radio" id="locationUS" name="location" value="US" {{ (old('location') == 'US' || empty(old('location'))) ? 'checked' : '' }}>
                                                        <label for="locationUS">United States</label>
                                                    </div>
                                                    <div class="custom_radio">
                                                        <input class="location" type="radio" id="locationCA" name="location" value="CA" {{ old('location') == 'CA'  ? 'checked' : '' }}>
                                                        <label for="locationCA">Canada</label>
                                                    </div>
                                                    <div class="custom_radio">
                                                        <input class="location" type="radio" id="locationInt" name="location" value="INT" {{ old('location') == 'INT'  ? 'checked' : '' }}>
                                                        <label for="locationInt">International</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ly-row">
                                        <div class="ly-6 pl_0 pl_60">
                                            <div class="form_row">
                                                <div class="label_inline required width_150p{{ $errors->has('address') ? ' has-danger' : '' }}">
                                                    Address
                                                </div>
                                                <div class="form_inline">
                                                    <div class="input_inline">
                                                        <div class="display_inline">
                                                            <input type="text" class="form_global width_350p" id="address" name="address" value="{{ old('address') }}" placeholder="Address">
                                                            @if ($errors->has('address'))
                                                                <div class="form-control-feedback">{{ $errors->first('address') }}</div>
                                                            @endif
                                                        </div>
                                                        <div class="display_inline float_right mr_0{{ $errors->has('unit') ? ' has-danger' : '' }}">
                                                            <span class="mr_8">Unit #</span>
                                                            <div class="width_50p">
                                                                <input type="text" class="form_global" id="unit" name="unit" value="{{ old('unit') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form_row {{ $errors->has('state') ? ' has-danger' : '' }}" id="form-group-state">
                                                <div class="label_inline width_150p">
                                                    State
                                                </div>
                                                <div class="form_inline">
                                                    <input type="text" class="form_global" id="state" name="state" value="{{ old('state') }}" placeholder="State">
                                                    @if ($errors->has('state'))
                                                        <div class="form-control-feedback">{{ $errors->first('state') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form_row {{ $errors->has('state') ? ' has-danger' : '' }}" id="form-group-state-select">
                                                <div class="label_inline width_150p">
                                                    Select State
                                                </div>
                                                <div class="form_inline">
                                                    <div class="select">
                                                        <select class="form_global form-control-rounded form-control-sm" id="stateSelect" name="stateSelect">
                                                            <option value="">Select State</option>
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('state'))
                                                        <div class="form-control-feedback">{{ $errors->first('state') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ly-6 pl_0 pr_60">
                                            <div class="form_row {{ $errors->has('city') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    City
                                                </div>
                                                <div class="form_inline">
                                                    <input type="text" class="form_global" id="city" name="city" value="{{ old('city') }}" placeholder="City">
                                                    @if ($errors->has('city'))
                                                        <div class="form-control-feedback">{{ $errors->first('city') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form_row {{ $errors->has('zipCode') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    Zip Code
                                                </div>
                                                <div class="form_inline">
                                                    <input type="text" class="form_global" id="zipCode" name="zipCode" value="{{ old('zipCode') }}" placeholder="Zip Code">
                                                    @if ($errors->has('zipCode'))
                                                        <div class="form-control-feedback">{{ $errors->first('zipCode') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ly-row">
                                        <div class="ly-6 pl_60">
                                            <div class="form_row {{ $errors->has('country') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    Country
                                                </div>
                                                <div class="form_inline">
                                                    <div class="select">
                                                        <select class="form_global form-control-rounded form-control-sm" id="country" name="country">
                                                            <option value="">Select Country</option>
                                                            @foreach($countries as $country)
                                                                <option data-code="{{ $country->code }}" value="{{ $country->id }}" {{ old('country') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('country'))
                                                        <div class="form-control-feedback">{{ $errors->first('country') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ly-6 pl_0 pr_60">
                                            <div class="form_row {{ $errors->has('phone') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    Phone
                                                </div>
                                                <div class="form_inline">
                                                    <input type="number" class="form_global" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Phone" min="0">
                                                    @if ($errors->has('phone'))
                                                        <div class="form-control-feedback">{{ $errors->first('phone') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ly-row">
                                        <div class="ly-6 pl_60">
                                            <div class="form_row {{ $errors->has('fax') ? ' has-danger' : '' }}">
                                                <div class="label_inline width_150p">
                                                    Fax
                                                </div>
                                                <div class="form_inline">
                                                    <input type="text" class="form_global" id="fax" name="fax" value="{{ old('fax') }}" placeholder="Fax">
                                                    @if ($errors->has('fax'))
                                                        <div class="form-control-feedback">{{ $errors->first('fax') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ly_card">
                            <div class="ly_card_heading">
                                <h5 class="mb-0"> Billing Address
                                    <div class="form_inline">
                                        <div class="custom_checkbox">
                                            <input type="checkbox" id="sameAsShippingAddress" name="sameAsShippingAddress" value="1" {{ old('sameAsShippingAddress') ? 'checked' : '' }}>
                                            <label for="sameAsShippingAddress">Check here if same as shipping address.</label>
                                        </div>
                                    </div>
                                </h5>
                            </div>
                            <div class="ly_card_body">
                                <div class="ly-wrap-fluid">
                                    <div class="ly-row">
                                        <div class="ly-12 pl_60 pr_60">
                                            <div class="form_row">
                                                <div class="label_inline width_150p">
                                                    Location
                                                </div>
                                                <div class="form_inline">
                                                    <div class="custom_radio">
                                                        <input class="billingLocation" type="radio" id="billingLocationUS" name="billingLocation" value="US" {{ (old('billingLocation') == 'US' || empty(old('billingLocation'))) ? 'checked' : '' }}>
                                                        <label for="billingLocationUS">United States</label>
                                                    </div>
                                                    <div class="custom_radio">
                                                        <input class="billingLocation" type="radio" id="billingLocationCA" name="billingLocation" value="CA" {{ old('billingLocation') == 'CA'  ? 'checked' : '' }}>
                                                        <label for="billingLocationCA">Canada</label>
                                                    </div>
                                                    <div class="custom_radio">
                                                        <input class="billingLocation" type="radio" id="billingLocationInt" name="billingLocation" value="INT" {{ old('billingLocation') == 'INT'  ? 'checked' : '' }}>
                                                        <label for="billingLocationInt">International</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ly-row">
                                        <div class="ly-6 pl_0 pl_60">
                                            <div class="form_row">
                                                <div class="label_inline required width_150p{{ $errors->has('billingAddress') ? ' has-danger' : '' }}">
                                                    Address
                                                </div>
                                                <div class="form_inline">
                                                    <div class="input_inline">
                                                        <div class="display_inline">
                                                            <input type="text" class="form_global width_350p" id="billingAddress" name="billingAddress" value="{{ old('billingAddress') }}" placeholder="Address">
                                                            @if ($errors->has('billingAddress'))
                                                                <div class="form-control-feedback">{{ $errors->first('billingAddress') }}</div>
                                                            @endif
                                                        </div>
                                                        <div class="display_inline float_right mr_0{{ $errors->has('billingUnit') ? ' has-danger' : '' }}">
                                                            <span class="mr_8">Unit #</span>
                                                            <div class="width_50p">
                                                                <input type="text" class="form_global" id="billingUnit" name="billingUnit" value="{{ old('billingUnit') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form_row {{ $errors->has('billingState') ? ' has-danger' : '' }}" id="form-group-factory-state">
                                                <div class="label_inline width_150p">
                                                    State
                                                </div>
                                                <div class="form_inline">
                                                    <input type="text" class="form_global" id="billingState" name="billingState" value="{{ old('billingState') }}" placeholder="State">
                                                    @if ($errors->has('billingState'))
                                                        <div class="form-control-feedback">{{ $errors->first('billingState') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form_row {{ $errors->has('billingState') ? ' has-danger' : '' }}" id="form-group-factory-state-select">
                                                <div class="label_inline width_150p">
                                                    State
                                                </div>
                                                <div class="form_inline">
                                                    <div class="select">
                                                        <select class="form_global form-control-rounded form-control-sm" id="billingStateSelect" name="billingStateSelect">
                                                            <option value="">@lang('frontend.register_select_state') </option>
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('billingState'))
                                                        <div class="form-control-feedback">{{ $errors->first('billingState') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ly-6 pl_0 pr_60">
                                            <div class="form_row {{ $errors->has('billingCity') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    City
                                                </div>
                                                <div class="form_inline">
                                                    <input type="text" class="form_global" id="billingCity" name="billingCity" value="{{ old('billingCity') }}" placeholder="City">
                                                    @if ($errors->has('billingCity'))
                                                        <div class="form-control-feedback">{{ $errors->first('billingCity') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form_row {{ $errors->has('billingZipCode') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    Zip Code
                                                </div>
                                                <div class="form_inline">
                                                    <input type="text" class="form_global" id="billingZipCode" name="billingZipCode" value="{{ old('billingZipCode') }}" placeholder="Zip Code">
                                                    @if ($errors->has('billingZipCode'))
                                                        <div class="form-control-feedback">{{ $errors->first('billingZipCode') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ly-row">
                                        <div class="ly-6 pl_60">
                                            <div class="form_row {{ $errors->has('billingCountry') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    Country
                                                </div>
                                                <div class="form_inline">
                                                    <div class="select">
                                                        <select class="form_global form-control-rounded form-control-sm" id="billingCountry" name="billingCountry">
                                                            <option value="">Select Country</option>
                                                            @foreach($countries as $country)
                                                                <option data-code="{{ $country->code }}" value="{{ $country->id }}" {{ old('billingCountry') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('billingCountry'))
                                                        <div class="form-control-feedback">{{ $errors->first('billingCountry') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ly-6 pl_0 pr_60">
                                            <div class="form_row {{ $errors->has('billingPhone') ? ' has-danger' : '' }}">
                                                <div class="label_inline required width_150p">
                                                    Phone
                                                </div>
                                                <div class="form_inline">
                                                    <input type="number" class="form_global" id="billingPhone" name="billingPhone" value="{{ old('billingPhone') }}" placeholder="Phone" min="0">
                                                    @if ($errors->has('billingPhone'))
                                                        <div class="form-control-feedback">{{ $errors->first('billingPhone') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ly-row">
                                        <div class="ly-6 pl_60">
                                            <div class="form_row {{ $errors->has('billingFax') ? ' has-danger' : '' }}">
                                                <div class="label_inline width_150p">
                                                    Fax
                                                </div>
                                                <div class="form_inline">
                                                    <input type="text" class="form_global" id="billingFax" name="billingFax" value="{{ old('billingFax') }}" placeholder="Fax">
                                                    @if ($errors->has('billingFax'))
                                                        <div class="form-control-feedback">{{ $errors->first('billingFax') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ly-row">
                                        <div class="ly-12 pl_60 pr_60">
                                            <div class="form_row">
                                                <div class="label_inline width_150p">
                                                </div>
                                                <div class="form_inline">
                                                    <div class="custom_checkbox">
                                                        <input type="checkbox" id="receiveSpecialOffers" name="receiveSpecialOffers" value="1" checked>
                                                        <label for="receiveSpecialOffers">Sign up to receive special offers and information.</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text_right m15">
                            <div class="display_inline mr_0">
                                <button class="ly_btn btn_blue min_width_100p" type="submit">REGISTER</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@stop

@section('additionalJS')
    <script>
        $(function () {
            $('.btnShowHide').click(function () {
                if ($(this).hasClass('collapsed')) {
                    $(this).closest('.col-md-3').find('.span_icon').html('<i class="ti-arrow-down"></i>');
                } else {
                    $(this).closest('.col-md-3').find('.span_icon').html('<i class="ti-arrow-right"></i>');
                }
            });

            $('.span_icon').click(function () {
                $(this).siblings('.btnShowHide').trigger('click');
            });
        });
    </script>

<script>
    var usStates = <?php echo json_encode($usStates); ?>;
    var caStates = <?php echo json_encode($caStates); ?>;
    var oldState = '{{ old('stateSelect') }}';
    var oldBillingState = '{{ old('billingStateSelect') }}';

    $(function () {
        $('form').bind('submit', function () {
            $(this).find(':input').prop('disabled', false);
        });

        $('#address').keyup(function () {
            var text = $(this).val();
            if ($("#sameAsShippingAddress").is(':checked'))
                $('#billingAddress').val(text);
        });

        $('#unit').keyup(function () {
            var text = $(this).val();
            if ($("#sameAsShippingAddress").is(':checked'))
                $('#billingUnit').val(text);
        });

        $('#city').keyup(function () {
            var text = $(this).val();
            if ($("#sameAsShippingAddress").is(':checked'))
                $('#billingCity').val(text);
        });

        $('#state').keyup(function () {
            var text = $(this).val();
            if ($("#sameAsShippingAddress").is(':checked'))
                $('#billingState').val(text);
        });

        $('#zipCode').keyup(function () {
            var text = $(this).val();
            if ($("#sameAsShippingAddress").is(':checked'))
                $('#billingZipCode').val(text);
        });

        $('#phone').keyup(function () {
            var text = $(this).val();
            if ($("#sameAsShippingAddress").is(':checked'))
                $('#billingPhone').val(text);
        });

        $('#fax').keyup(function () {
            var text = $(this).val();
            if ($("#sameAsShippingAddress").is(':checked'))
                $('#billingFax').val(text);
        });

        $('#sameAsShippingAddress').change(function () {
            $('#address').trigger('keyup');
            $('#unit').trigger('keyup');
            $('#city').trigger('keyup');
            $('#state').trigger('keyup');
            $('#zipCode').trigger('keyup');
            $('#phone').trigger('keyup');
            $('#fax').trigger('keyup');

            var location = $('.location:checked').val();
            $('.billingLocation[value=' + location + ']').prop('checked', true);
            $('.billingLocation').trigger('change');

            $('#billingCountry').val($('#country').val());
            $('#billingState').val($('#state').val());
            $('#billingStateSelect').val($('#stateSelect').val());
        });

        $('.location').change(function () {
            var location = $('.location:checked').val();

            if ($("#sameAsShippingAddress").is(':checked')) {
                $('.billingLocation[value=' + location + ']').prop('checked', true);
                $('.billingLocation').trigger('change');
            }

            if (location == 'CA' || location == 'US') {
                if (location == 'US')
                    $('#country').val('1');
                else
                    $('#country').val('2');


                $('#country').prop('disabled', 'disabled');
                $('#form-group-state-select').show();
                $('#stateSelect').val('');
                $('#form-group-state').hide();

                $('#stateSelect').html('<option value="">@lang('Select State')</option>');

                if (location == 'US') {
                    $.each(usStates, function (index, value) {
                        if (value.id == oldState)
                            $('#stateSelect').append('<option value="'+value.id+'" selected>'+value.name+'</option>');
                        else
                            $('#stateSelect').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }

                if (location == 'CA') {
                    $.each(caStates, function (index, value) {
                        if (value.id == oldState)
                            $('#stateSelect').append('<option value="'+value.id+'" selected>'+value.name+'</option>');
                        else
                            $('#stateSelect').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            } else {
                $('#country').val('');
                $('#country').prop('disabled', false);
                $('#form-group-state-select').hide();
                $('#form-group-state').show();
            }
        });

        $('.billingLocation').change(function () {
            var location = $('.billingLocation:checked').val();

            if (location == 'CA' || location == 'US') {
                if (location == 'US')
                    $('#billingCountry').val('1');
                else
                    $('#billingCountry').val('2');

                $('#billingCountry').prop('disabled', 'disabled');
                $('#form-group-factory-state-select').show();
                $('#billingStateSelect').val('');
                $('#form-group-factory-state').hide();

                $('#billingStateSelect').html('<option value="">Select State</option>');

                if (location == 'US') {
                    $.each(usStates, function (index, value) {
                        if (value.id == oldBillingState)
                            $('#billingStateSelect').append('<option value="'+value.id+'" selected>'+value.name+'</option>');
                        else
                            $('#billingStateSelect').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }

                if (location == 'CA') {
                    $.each(caStates, function (index, value) {
                        if (value.id == oldBillingState)
                            $('#billingStateSelect').append('<option value="'+value.id+'" selected>'+value.name+'</option>');
                        else
                            $('#billingStateSelect').append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            } else {
                 $('#billingCountry').val('');
                $('#billingCountry').prop('disabled', false);
                $('#form-group-factory-state-select').hide();
                $('#form-group-factory-state').show();
            }
        });

        $('#country').change(function () {
            var countryId = $(this).val();

            if (countryId == 1) {
                $("#locationUS").prop("checked", true);
                $('.location').trigger('change');
            } else if (countryId == 2) {
                $("#locationCA").prop("checked", true);
                $('.location').trigger('change');
            }
        });

        $('.location').trigger('change');
        $('.billingLocation').trigger('change');
    })
</script>
@stop
