<?php use App\Enumeration\Permissions; ?>

<div class="ly_accrodion">
    <div class="ly_accrodion_heading">
        <div class="ly_accrodion_title open_acc" data-toggle="accordion" data-target="#addAccount" data-class="accordion" id="btnAddNewAccount">
            <span id="addEditTitle">Add New</span>
        </div>
    </div>
    <div class="accordion_body default_accrodion open" id="addAccount" style="">

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
                First & Last Name
            </div>
            <div class="form_inline pr_8">
                <input type="text" class="form_global" id="firstName" value="{{ old('firstName') }}" name="firstName">
            </div>
            <div class="form_inline">
                <input type="text" class="form_global" id="lastName" value="{{ old('lastName') }}" name="lastName">
            </div>

        </div>
        <div class="form_row">
            <div class="label_inline width_150p">
                    User Name
            </div>
            <div class="form_inline">
                <input type="text" class="form_global" value="{{ old('user_name') }}" name="user_name"  id="userId">
            </div>
            <div class="form_inline text_right width_200p">
                User Name cannot be changed.
            </div>
        </div>
        <div class="form_row">
            <div class="label_inline width_150p">
                Password
            </div>
            <div class="form_inline">
                <input type="password" class="form_global" id="password" name="password" placeholder="*****">
            </div>
        </div>

        <div class="form_row">
            <div class="label_inline width_150p">
                Permissions
            </div>
            <div class="form_inline">
                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="items" class="permission" value="{{ Permissions::$ITEMS }}">
                    <label for="items">Products</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="ordersId" class="permission" value="{{ Permissions::$ORDERS }}">
                    <label for="ordersId">Orders</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="customersId" class="permission" value="{{ Permissions::$CUSTOMERS }}">
                    <label for="customersId">Customers</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="statisticsId" class="permission" value="{{ Permissions::$STATISTICS }}">
                    <label for="statisticsId">Statistics</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="messages" class="permission" value="{{ Permissions::$MESSAGES }}">
                    <label for="messages">Messages</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="administration" class="permission" value="{{ Permissions::$ADMINISTRATION }}">
                    <label for="administration">Administration</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="adminInfo" class="permission" value="{{ Permissions::$ADMIN_INFO }}">
                    <label for="adminInfo">Admin Info</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="accountSettings" class="permission" value="{{ Permissions::$ACCOUNT_SETTINGS }}">
                    <label for="accountSettings">Account Settings</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="shipingSettings" class="permission" value="{{ Permissions::$SHIPPING }}">
                    <label for="shipingSettings">Shiping</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="promotionSettings" class="permission" value="{{ Permissions::$PROMOTION }}">
                    <label for="promotionSettings">Promotion</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="socialSettings" class="permission" value="{{ Permissions::$SOCIAL }}">
                    <label for="socialSettings">Social</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="logoSettings" class="permission" value="{{ Permissions::$LOGO }}">
                    <label for="logoSettings">Logo</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="pageSettings" class="permission" value="{{ Permissions::$PAGES }}">
                    <label for="pageSettings">Page</label>
                </div>

                <div class="custom_checkbox mr_20">
                    <input type="checkbox" id="notificationSettings" class="permission" value="{{ Permissions::$NOTIFICATION }}">
                    <label for="notificationSettings">Notification</label>
                </div>
            </div>
        </div>

        <div class="create_item_color">
            <div class="float_right">
                <div class="display_inline">
                    <button class="ly_btn  btn_blue" id="btnCancelAccount" data-toggle="accordion" data-target="#addAccount" data-class="accordion" class="accordion_heading" data-class="accordion" >Cancel</button>
                    <button id="btnAddAccount" class="ly_btn  btn_blue">Add</button>
                    <button id="btnUpdateAccount" class="ly_btn  btn_blue" style="display: none;">Update</button>
                </div>
            </div>
        </div>
        <br>
    </div>
</div>

<br>

<div class="ly-row">
    <div class="ly-12">
        <table class="table table_border_top">
            <thead>
                <tr>
                    <th>Date Created</th>
                    <th>Name</th>
                    <th>Level</th>
                    <th>User Name</th>
                    <th>Last Login Date</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="accountsBody">
                <tr>
                    <td>{{ date('m/d/Y', strtotime($user->created_at)) }}</td>
                    <td>{{ $user->first_name.' '.$user->last_name }}</td>
                    <td>Master</td>
                    <td>{{ $user->user_name }}</td>
                    <td>{{ $user->last_login != null ? date('m/d/Y', strtotime($user->last_login)) : '' }}</td>
                    <td>
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" checked disabled>
                            <span class="custom-control-indicator"></span>
                        </label>
                    </td>
                    <td></td>
                </tr>
                
                @foreach($users as $item)
                    <tr>
                        <td><span class="date_created">{{ date('m/d/Y', strtotime($item->created_at)) }}</span></td>
                        <td><span class="name">{{ $item->first_name.' '.$item->last_name }}</span></td>
                        <td><span class="level">Lower Level</span></td>
                        <td><span class="userId">{{ $item->user_name }}</span></td>
                        <td>{{ $item->last_login != null ? date('m/d/Y', strtotime($item->last_login)) : '' }}</td>
                        <td>
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="status" data-id="{{ $item->id }}" value="1" {{ $item->active == 1 ? 'checked' : '' }}>
                                <span class="custom-control-indicator"></span>
                            </label>
                        </td>
                        <td>
                            <a class="btnEdit" role="button" data-id="{{ $item->id }}" data-index="{{ $loop->index }}" style="color: blue">Edit</a> |
                            <a class="btnDelete" role="button" data-id="{{ $item->id }}" data-index="{{ $loop->index }}" style="color: red">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<template id="accountItem">
    <tr>
        <td><span class="date_created"></span></td>
        <td><span class="name"></span></td>
        <td><span class="level">Lower Level</span></td>
        <td><span class="userId"></span></td>
        <td></td>
        <td>
            <label class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input status" value="1">
                <span class="custom-control-indicator"></span>
            </label>
        </td>
        <td>
            <a class="btnEdit" role="button" style="color: blue">Edit</a> |
            <a class="btnDelete" role="button" style="color: red">Delete</a>
        </td>
    </tr>
</template>
<div class="modal" data-modal="accountDeleteModal">
    <div class="modal_overlay" data-modal-close="accountDeleteModal"></div>
    <div class="modal_inner">
        <div class="modal_wrapper modal_470p">
            <div class="item_list_popup">
                <div class="modal_header display_table">
                    <span class="modal_header_title">Are you sure want to delete?</span>
                    <div class="float_right">
                        <span class="close_modal" data-modal-close="accountDeleteModal"></span>
                    </div>
                </div>
                <div class="modal_content">
                    <div class="ly-wrap-fluid">
                        <div class="ly-row">
                            <div class="ly-12">
                                <div class="display_table m15">
                                    <div class="float_right">
                                        <button class="ly_btn btn_blue width_150p " data-modal-close="accountDeleteModal">Close</button>
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