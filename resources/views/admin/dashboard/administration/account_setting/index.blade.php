@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="vendor_info_content">
    <div class="tab_wrapper pa_0">
        <div class="ly_tab">
            <nav class="tabs">
                <ul class="tab_four">
                    <li id="AdminPasswordTab" href="#AdminPassword"  class="tab_link">Admin ID & Password</li>
                    <li id="ManageAccountsTab" href="#ManageAccounts" class="tab_link">Manage Accounts</li>
                    <li id="LoginHistoryTab" href="#LoginHistory" class="tab_link">Login History</li>
                </ul>
            </nav>
            <div class="tab_content_wrapper">
                <div id="AdminPassword" class="tab_content">
                    <div class="fadein">
                        @include('admin.dashboard.administration.account_setting.includes.admin')
                    </div>
                </div>

                <div id="ManageAccounts" class="tab_content">
                    <div class="fadein">
                        @include('admin.dashboard.administration.account_setting.includes.manage_accounts')
                    </div>
                </div>

                <div id="LoginHistory" class="tab_content">
                    <div class="fadein">
                        @include('admin.dashboard.administration.account_setting.includes.login_history')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.switch_page').click(function() {

                var page = $('.page').val();
                var currentLocation = String(window.location) + '?c=login';

                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + page;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('page=') >= 0) {

                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + page;

                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + page;

                    }


                }

                window.location = switchPageUrl;

            });

            $('.page_first').click(function() {

                var page = 1;
                var currentLocation = String(window.location) + '?c=login';
                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + page;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('page=') >= 0) {

                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + page;

                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + page;

                    }


                }

                window.location = switchPageUrl;

            });

            $('.page_prev').click(function() {

                var page = <?php echo $loginHistory->currentPage(); ?> - 1;
                var currentLocation = String(window.location) + '?c=login';
                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + page;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('page=') >= 0) {

                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + page;

                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + page;

                    }


                }
                window.location = switchPageUrl;

            });

            $('.page_next').click(function() {

                var page = <?php echo $loginHistory->currentPage(); ?> + 1;
                var currentLocation = String(window.location) + '?c=login';
                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + page;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('page=') >= 0) {

                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + page;

                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + page;

                    }


                }
                window.location = switchPageUrl;

            });

            $('.page_last').click(function() {

                var page = <?php echo $loginHistory->lastPage(); ?>;
                var currentLocation = String(window.location) + '?c=login';
                var switchPageUrl = currentLocation.split('?')[0] + '?page=' + page;

                if((currentLocation.split('?')[1])) {

                    if((currentLocation.split('?')[1]).search('page=') >= 0) {

                    } else {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + currentLocation.split('?')[1] + '&page=' + page;

                    }

                    if((currentLocation.split('?')[1]).search('&page=') > 0) {

                        switchPageUrl = currentLocation.split('?')[0] + '?' + (currentLocation.split('?')[1]).split('&page=')[0] + '&page=' + page;

                    }


                }
                window.location = switchPageUrl;

            });

            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);


            $('#adminBtnSave').click(function () {
                $('#adminFirstName').removeClass('is-invalid');
                $('#adminLastName').removeClass('is-invalid');
                var error = false;

                var firstName = $('#adminFirstName').val();
                var lastName = $('#adminLastName').val();
                var password = $('#adminPassword').val();

                if (firstName == ''){
                    error = true;
                    $('#adminFirstName').addClass('is-invalid');
                }

                if (lastName == ''){
                    error = true;
                    $('#adminLastName').addClass('is-invalid');
                }

                if (!error) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_admin_id_post') }}",
                        data: { firstName: firstName, lastName: lastName, password: password }
                    }).done(function( msg ) {
                        toastr.success("Account Setting Updated!");
                    });
                }
            });

            // Manage Accounts
            var users = <?php echo json_encode($users->toArray()); ?>;
            var selectedUserId, selectedUserIndex;

            $('#btnAddNewAccount').click(function () {
                $('#addEditAccountRow').removeClass('d-none');
                $('#addEditTitle').html('Add a New Account');
                $(this).addClass('d-none');

                $('#btnAddAccount').show();
                $('#btnUpdateAccount').hide();
            });

            $('#btnCancelAccount').click(function () {
                $('#addEditAccountRow').addClass('d-none');
                $('#btnAddNewAccount').removeClass('d-none');

                $('#firstName').removeClass('is-invalid');
                $('#lastName').removeClass('is-invalid');
                $('#userId').removeClass('is-invalid');
                $('#password').removeClass('is-invalid');

                $('#firstName').val('');
                $('#lastName').val('');
                $('#userId').val('');
                $('#password').val('');

                $('#statusActive').prop('checked', true);
                $('.permission').prop('checked', false);
                $('#refundAccess').prop('checked', false);
            });

            $('#btnAddAccount').click(function () {
                $('#firstName').removeClass('is-invalid');
                $('#lastName').removeClass('is-invalid');
                $('#userId').removeClass('is-invalid');
                $('#password').removeClass('is-invalid');

                var error = false;
                var status = 0;

                if ($('#statusActive').is(':checked'))
                    status = 1;

                var firstName = $('#firstName').val();
                var lastName = $('#lastName').val();
                var userId = $('#userId').val();
                var password = $('#password').val();
                var permissions = [];

                $( ".permission" ).each(function( index ) {
                    if ($(this).is(':checked'))
                        permissions.push($( this ).val());
                });

                if (firstName == '') {
                    error = true;
                    $('#firstName').addClass('is-invalid');
                }

                if (lastName == '') {
                    error = true;
                    $('#lastName').addClass('is-invalid');
                }

                if (userId == '') {
                    error = true;
                    $('#userId').addClass('is-invalid');
                }

                if (password == '') {
                    error = true;
                    $('#password').addClass('is-invalid');
                }

                if (!error) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_add_account_post') }}",
                        data: { status: status, firstName: firstName, lastName: lastName, userId: userId, password: password, permissions: permissions }
                    }).done(function( data ) {
                        if (data.success) {
                            var user = data.message;
                            users.push(user);
                            var index = users.length - 1;

                            var today = new Date();
                            var date = (today.getMonth() + 1) + '/' + today.getDate() + '/' + today.getFullYear();
                            var html = $('#accountItem').html();
                            var row = $(html);
                            row.find('.date_created').html(date);
                            row.find('.name').html(firstName + ' ' + lastName);
                            row.find('.userId').html(userId);

                            row.find('.status').attr("data-id", user.id);
                            row.find('.btnEdit').attr("data-id", user.id);
                            row.find('.btnEdit').attr("data-index", index);
                            row.find('.btnDelete').attr("data-index", index);
                            row.find('.btnDelete').attr("data-id", user.id);

                            if (status == 1)
                                row.find('.status').prop('checked', true);

                            $('#accountsBody').append(row);

                            $('#btnCancelAccount').trigger('click');
                            toastr.success("Account Added!");
                        } else {
                            alert(data.message);
                        }
                    });
                }
            });

            $('body').on('click', '.btnDelete', function () {
                var id = $(this).data('id');
                var index = $(".btnDelete").index(this);
                selectedUserId = id;
                selectedUserIndex = index + 1;

                var targeted_modal_class = 'accountDeleteModal';
                $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');
            });

            $('#modalBtnDelete').click(function () {
                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_delete_account_post') }}",
                    data: { id: selectedUserId }
                }).done(function( data ) {
                    $('#accountsBody tr:eq('+selectedUserIndex+')').remove();
                    var targeted_modal_class = 'accountDeleteModal';
                    $('[data-modal="' + targeted_modal_class + '"]').removeClass('open_modal');
                    toastr.success('Account Deleted!');
                });
            });

            $('body').on('click', '.btnEdit', function () {
                var id = $(this).data('id');
                var index = $(this).data('index');
                var user = users[index];
                var userPermissions = user.permissions;
                selectedUserId = id;
                selectedUserIndex = index;

                $('#addEditAccountRow').removeClass('d-none');
                $('#addEditTitle').html('Edit Account');
                $('#btnAddNewAccount').addClass('d-none');
                $('#btnAddAccount').hide();
                $('#btnUpdateAccount').show();

                if (user.active == 1)
                    $('#statusActive').prop('checked', true);
                else
                    $('#statusInactive').prop('checked', true);

                $('#firstName').val(user.first_name);
                $('#lastName').val(user.last_name);
                $('#userId').val(user.user_name);
                $('.permission').prop('checked', false);

                $.each(userPermissions, function (index, value) {
                    $('.permission[value="' + value + '"]').prop('checked', true);
                });

                if(!$('#addAccount').is(":visible")) {
                    let target = $('#addAccount');
                    $('.ly_accrodion_title').toggleClass('open_acc');
                    target.slideToggle();
                }
            });

            $('#btnUpdateAccount').click(function () {
                $('#firstName').removeClass('is-invalid');
                $('#lastName').removeClass('is-invalid');
                $('#userId').removeClass('is-invalid');

                var error = false;
                var status = 0;

                if ($('#statusActive').is(':checked'))
                    status = 1;

                var firstName = $('#firstName').val();
                var lastName = $('#lastName').val();
                var userId = $('#userId').val();
                var password = $('#password').val();
                var permissions = [];

                $( ".permission" ).each(function( index ) {
                    if ($(this).is(':checked'))
                        permissions.push($( this ).val());
                });

                if (firstName == '') {
                    error = true;
                    $('#firstName').addClass('is-invalid');
                }

                if (lastName == '') {
                    error = true;
                    $('#lastName').addClass('is-invalid');
                }

                if (userId == '') {
                    error = true;
                    $('#userId').addClass('is-invalid');
                }

                if (!error) {
                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin_update_account_post') }}",
                        data: { id: selectedUserId, status: status, firstName: firstName, lastName: lastName, userId: userId, password: password, permissions: permissions }
                    }).done(function( data ) {
                        if (data.success) {
                            users[selectedUserIndex] = data.message;

                            $('.name:eq(' + selectedUserIndex + ')').html(firstName + ' ' + lastName);
                            $('.userId:eq(' + selectedUserIndex + ')').html(userId);

                            if (status == 1)
                                $('.status:eq(' + selectedUserIndex + ')').prop('checked', true);
                            else
                                $('.status:eq(' + selectedUserIndex + ')').prop('checked', false);

                            $('#btnCancelAccount').trigger('click');
                            toastr.success("Account Updated!");
                        } else {
                            alert(data.message);
                        }
                    });
                }
            });

            $('body').on('change', '.status', function () {
                var status = 0;
                var id = $(this).data('id');

                if ($(this).is(':checked'))
                    status = 1;

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_status_update_account_post') }}",
                    data: { id: id, status: status }
                }).done(function( msg ) {
                    toastr.success('Status Updated!');
                });
            });

            // Store Settings

            $('#btnStoreSettingSave').click(function () {
                var que1 = $('input[name=qus1]:checked').val();
                var que2 = $('input[name=qus2]:checked').val();
                var que3 = $('input[name=qus3]:checked').val();
                var que4 = $('input[name=qus4]:checked').val();
                var que5 = $('input[name=qus5]:checked').val();
                var que6 = $('input[name=qus6]:checked').val();

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_save_store_setting_post') }}",
                    data: { que1: que1, que2: que2, que3: que3, que4: que4, que5: que5, que6: que6 }
                }).done(function( msg ) {
                    toastr.success('Store Setting Updated!');
                });
            });

            $('#btnStylePickSubmit').click(function (e) {
                e.preventDefault();

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin_style_pick_post') }}",
                    data: $('#formStylePick').serialize(),
                }).done(function( msg ) {
                    toastr.success("Style Pick Info updated!");
                });
            });
        });
    </script>
@stop
