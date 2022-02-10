<?php
    use App\Enumeration\PageEnumeration;
    use App\Enumeration\Permissions;
    use App\Enumeration\Role;
?>

<header class="header_area">
    <ul>
        <li>Welcome {{auth()->user()->name}}!&nbsp;&nbsp;&nbsp;&nbsp;</li>

        @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$MESSAGES, $permissions))
            <li class="header_msg">
                <a href="{{ route('all_message') }}"><span class="red_bg">{{ $unread_messages }}</span> MESSAGES&nbsp;&nbsp;&nbsp;&nbsp;</a>
            </li>
        @endif

        @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$ADMINISTRATION, $permissions))
        <li class="header_accordion"> <span><i class="fas fa-cog"></i> ADMINISTRATION&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
            <ul class="header_acc_content">
                @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$ADMIN_INFO, $permissions))
                    <li data-toggle="accordion" data-target="#VendorInfo" class="accordion_heading has_child" data-class="accordion">
                        <a href="{{ route('admin_admin_information') }}#CompanyInfo">Admin Information</a>
                    </li>
                    <ul class="sub_accordion default_accrodion" id="VendorInfo">
                        <li><a href="{{ route('admin_admin_information') }}#CompanyInfo"> Company Information</a></li>
                        <li><a href="{{ route('admin_admin_information') }}#SizeChart"> Size Chart</a></li>
                        {{-- <li><a href="{{ route('admin_admin_information') }}#OrderNotice"> Order Notice</a></li> --}}
                        {{-- <li><a href="{{ route('admin_admin_information') }}#Settings"> Settings</a></li> --}}
                        <li><a href="{{ route('admin_admin_information') }}#ReturnPolicy"> Return Policy</a></li>
                    </ul>
                @endif

                @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$ACCOUNT_SETTINGS, $permissions))
                    <li data-toggle="accordion" data-target="#AccountSettings" class="accordion_heading has_child" data-class="accordion">
                        <a href="{{ route('admin_account_setting') }}#AdminPassword">Account Settings</a>
                    </li>
                    <ul class="sub_accordion default_accrodion" id="AccountSettings">
                        <li><a href="{{ route('admin_account_setting') }}#AdminPassword"> Admin ID & Password</a></li>
                        <li><a href="{{ route('admin_account_setting') }}#ManageAccounts"> Manage Account</a></li>
                        <li><a href="{{ route('admin_account_setting') }}#LoginHistory"> Login History</a></li>
                    </ul>
                @endif

                @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$SHIPPING, $permissions))
                    <li data-toggle="accordion" data-target="#ShippingSettings" class="accordion_heading has_child" data-class="accordion">
                        <a href="#">Shipping</a>
                    </li>
                    <ul class="sub_accordion default_accrodion" id="ShippingSettings">
                        <li><a href="{{ route('admin_courier') }}"> Courier</a></li>
                        <li><a href="{{ route('admin_ship_method') }}"> Ship Method</a></li>
                    </ul>
                @endif

                @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$PROMOTION, $permissions))
                    <li data-toggle="accordion" data-target="#Promotion" class="accordion_heading has_child" data-class="accordion">
                        <a href="#">Promotion</a>
                    </li>
                    <ul class="sub_accordion default_accrodion" id="Promotion">
                        <li><a href="{{ route('admin_promotions') }}"> Coupons</a></li>
{{--                        <li><a href="{{ route('admin_point_system') }}"> Point System</a></li>--}}
                    </ul>
                @endif

                @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$SOCIAL, $permissions))
                    <li data-toggle="accordion" data-target="#Social" class="accordion_heading has_child" data-class="accordion">
                        <a href="#">Social</a>
                    </li>
                    <ul class="sub_accordion default_accrodion" id="Social">
                        <li><a href="{{ route('admin_social_link') }}"> Social Links</a></li>
                        <li><a href="{{ route('admin_social_feed') }}"> Social Feeds</a></li>
                    </ul>
                @endif

                @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$LOGO, $permissions))
                    <li data-toggle="accordion" data-target="#Logo" class="accordion_heading has_child" data-class="accordion">
                        <a href="#">Logo</a>
                    </li>
                    <ul class="sub_accordion default_accrodion" id="Logo">
                        <li><a href="{{ route('admin_logo') }}"> Logo</a></li>
                    </ul>
                @endif

                @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$PAGES, $permissions))
                    <li data-toggle="accordion" data-target="#Pages" class="accordion_heading has_child" data-class="accordion">
                        <a href="#">Pages</a>
                    </li>
                    <ul class="sub_accordion default_accrodion" id="Pages">
                        <li><a href="{{ route('admin_home_page_view') }}">Home</a></li>
                        <li><a href="{{ route('admin_page_view', ['id' => PageEnumeration::$ABOUT_US]) }}">About Us</a></li>
                        <li><a href="{{ route('admin_page_view', ['id' => PageEnumeration::$RETURN_INFO]) }}">Return Policy</a></li>
                        <li><a href="{{ route('admin_page_view', ['id' => PageEnumeration::$TERMS_AND_CONDIOTIONS]) }}">Terms & Conditions</a></li>
                        <li><a href="{{ route('admin_page_view', ['id' => PageEnumeration::$PRIVACY_POLICY]) }}">Privacy Policy</a></li>
                        <li><a href="{{ route('admin_page_view', ['id' => PageEnumeration::$CONTACT_US]) }}">Contact Us</a></li>
                        <li><a href="{{ route('admin_page_view', ['id' => PageEnumeration::$FAQ]) }}">FAQ</a></li>
                        <li><a href="{{ route('admin_page_view', ['id' => PageEnumeration::$SIZE_GUIDE]) }}">Size Guide</a></li>
                    </ul>
                @endif

                @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$NOTIFICATION, $permissions))
                    <li data-toggle="accordion" data-target="#BuyerNotifications" class="accordion_heading has_child" data-class="accordion">
                        <a href="#">Buyer Notifications</a>
                    </li>
                    <ul class="sub_accordion default_accrodion" id="BuyerNotifications">
                        <li><a href="{{ route('admin_welcome_notification') }}">Welcome Notification</a></li>
                        <li><a href="{{ route('admin_top_notification') }}">Top Notification</a></li>
                    </ul>
                @endif

                @if (Auth::user()->role == Role::$ADMIN || in_array(Permissions::$MESSAGES, $permissions))
                    <li data-toggle="accordion" data-target="#Messaging" class="accordion_heading has_child" data-class="accordion">
                        <a href="#">Messaging</a>
                    </li>
                    <ul class="sub_accordion default_accrodion" id="Messaging">
                        <li><a href="{{ route('all_message') }}">Admin Message <span class="red_bg" id="message_count" style="padding:2px">{{ $unread_messages }}</span></a></li>
                    </ul>
                @endif
            </ul>
        </li>
        @endif

        <li class="header_account"> MORE&nbsp;&nbsp;&nbsp;&nbsp; <span></span>
            <ul class="header_account_inner">
                <li>
                    <a href="#" onclick="document.getElementById('logoutForm').submit()"><i class="fas fa-sign-out-alt"></i>  Sign Out</a>
                    <form id="logoutForm" class="" action="{{ route('logout_admin') }}" method="post">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </li>
            &nbsp;&nbsp;
    </ul>
</header>
