$(document).ready(function() {

    /*
    ===================================
        HEADER AREA
    ===================================
    */
    // $(".header_accordion").hover(function() {
    //     $(".header_acc_content").slideDown();
    // }, function() {
    //     $(".header_acc_content").slideUp();
    // });

    $(".header_acc_content .sub_accordion li , .header_acc_content > li > a ").click(function() {
        $(".header_acc_content").css('display' , 'none');
    });
    $(".header_acc_content").hover(function() {
        $(".header_acc_content").css('display' , 'block');
    });
    $('.header_area .header_account').click(function() {
        $('.header_account > span').toggleClass('header_account_arrow');
        $('.header_account_inner').slideToggle();
    });


    $('.header_accordion > span').click(function() {
        $('.header_acc_content').slideToggle();
    });

    /*
    ===================================
        CROSS SELLING ITEM
    ===================================
    */
    var base_url = window.location.origin;
    $(".cross_selling_img ul li").click(function(){
        var single_img_path = $(this).find("img").attr('src');
        var full_img_path = base_url + '/' + single_img_path;
        var data = `<span class="remove"> <i class="fas fa-times"></i> </span> <img src="${full_img_path}" alt="" class="img_mx_width" />`;
        $('.show_cross_selling_item ul').append("<li>" + data + "</li>");
        $(this).find("img").remove();
      });

    $(document).on('click','.show_cross_selling_item ul li span', function(e) {
        $(this).parent().remove();
    });

    /*
    ===================================
        ITEM LIST 
    ===================================
    */

   $('.onleft_acc_arrow').click(function() {
        $('.toggle_item_search').toggle();
    });

    // CHECKED IF CHECKBOX CHEEKED OR NOT
   $('.item_list .custom_checkbox input').change(function(e) {
        var value = $(this).prop('checked');
        if(value) {

            $(this).parent().parent().addClass('selected')

        } else {
            
            $(this).parent().parent().removeClass('selected')
        }
    });

    //TOGGLE CHECKBOX WHEN CLICK SELECT OR UNSELECT
    $('.toggle_item_checked').click(function() {
        var text = 'Select All';
        if ($(this).text() === text) {
            $('.active_item_list .item_list .custom_checkbox input').parent().parent().addClass('selected');
            var checkBoxes = $('.active_item_list .item_list .custom_checkbox input');
            checkBoxes.prop("checked", true);
            $('.toggle_item_checked').text('Unselect All');
        } else {
            $('.toggle_item_checked').text(text);
            $('.active_item_list .item_list .custom_checkbox input').parent().parent().removeClass('selected');
            var checkBoxes = $('.active_item_list .item_list .custom_checkbox input');
            checkBoxes.prop("checked", false);
        };
        

    });


    //TOGGLE CHECKBOX WHEN CLICK SELECT OR UNSELECT
    $('.toggle_item_checked_inactive').click(function() {
        var text = 'Select All';
        if ($(this).text() === text) {
            $('.inactive_item_list .item_list .custom_checkbox input').parent().parent().addClass('selected');
            var checkBoxes = $('.inactive_item_list .item_list .custom_checkbox input');
            checkBoxes.prop("checked", true);
            $('.toggle_item_checked_inactive').text('Unselect All');
        } else {
            $('.toggle_item_checked_inactive').text(text);
            $('.inactive_item_list .item_list .custom_checkbox input').parent().parent().removeClass('selected');
            var checkBoxes = $('.inactive_item_list .item_list .custom_checkbox input');
            checkBoxes.prop("checked", false);
        };
        

    });



    // ITEM LIST IMAGE POP UP IAMGE CHANGE ON CLICK
    $('.list_item_img_thumnail ul li').click(function() {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        $('.list_item_popup_img').empty();
        var single_img_path2 = $(this).find("img").attr('src');
        var full_img_path2 = base_url + '/' + single_img_path2;
        var data2 = `
        <img id="list_img" src="${full_img_path2}" alt="" class="width_full" />
        `;
        $('.list_item_popup_img').append(data2);
    });

    //ITEM LIST IMAGE POP UP TAG OPTION
    $('.edit_photo_tag , .back_preview').click(function() {
        $('.photo_tag').toggle();
        $('.list_item_img_thumnail').toggle();
        $('.write_photo_tag').hide();
        var text = 'Edit Photo Tag';
        if ($('.edit_photo_tag').text() === text) {
            $('.edit_photo_tag').text('Back to Image View');
        } else {
            $('.edit_photo_tag').text(text);
        }
    });
    $('.write_tag').click(function() {
        $('.write_photo_tag').show();
    });

    /*
    ===================================
        ITEM INVENTORY
    ===================================
    */
    $('.inventory_table_img').each(function() {
        inventory_img = $( this).find("img");
        $(this).hover(function() {
            //     console.log(inventory_img);
            //   $( this ).append('inventory_img');
            }, function() {
            //   $( this ).find('inventory_img').remove();
            }
          );
    });

    /*
    ===================================
        FAST EDIT
    ===================================
    */
    // CHECKED IF CHECKBOX CHEEKED OR NOT

    //TOGGLE CHECKBOX WHEN CLICK SELECT OR UNSELECT
    $('.first_edit_item_checked ').click(function() {
        let text = 'Select All';
        if ($(this).text() === text) {
            let checkBoxes = $('.fast_edit_img .custom_checkbox input');
            checkBoxes.prop("checked", true);
            $('.first_edit_item_checked').text('Unselect All');
            $('.fast_edit_save').removeAttr('disabled' , 'disabled');
        } else {
            $('.first_edit_item_checked').text(text);
            let checkBoxes = $('.fast_edit_img .custom_checkbox input');
            checkBoxes.prop("checked", false);
            $('.fast_edit_save').attr('disabled' , 'disabled');
        };
    });

    $('.fast_edit_img input').change(function(e) {
        if($('.fast_edit_img .custom_checkbox input').prop("checked") == true) {
            $('.fast_edit_save').removeAttr('disabled' , 'disabled');
        } else {
            $('.fast_edit_save').attr('disabled' , 'disabled');
        }
    });
 
    /*
    ===================================
        SHORT ITEM PAGE
    ===================================
    */
    $('.short_item_content_list li').click(function() {
        $(this).toggleClass('selected');
    });

    $('.sort_item_checked ').click(function() {
        let text = 'Select All';
        if ($(this).text() === text) {
            $('.sort_item_checked').text('Unselect All');
            $('.short_item_content_list li').addClass('selected');
        } else {
            $('.sort_item_checked').text(text);
            $('.short_item_content_list li').removeClass('selected');
        };
    });

    $(".short_item_content_list").sortable({
        containment: 'parent',
        tolerance: 'pointer',
        cursor: 'move',
        connectWith: '.short_item_content_list'
    });

    /*
    ===================================
        ITEM CATEGORY 
    ===================================
    */
    // ITEM CATEGORY SHORTING
   $(function() {
        $(".Item_sort").sortable({
            containment: 'parent',
            tolerance: 'pointer',
            cursor: 'move',
        });
        $(".Item_sort_child").sortable({
            containment: 'parent',
            tolerance: 'pointer',
            cursor: 'move',
            connectWith: '.Item_sort_child'
        });
    });
    // HIDE SHOW ADD NEW CATEGORY
    $('.add_new_cat_open').click(function() {
        $('#cat_name_id').text('Add a New Category');
        $('.add_new_cat').show();
        $('.add_new_cat_open').css('opacity' , '0');
    });
    $('.item_cat_close').click(function() {
        $('.add_new_cat').hide();
        $('.add_new_cat_open').css('opacity' , '1');
        $('#cat_name_id').text('Add a New Category');
    });

    $('.Item_sort li span').click(function() {
        $('#cat_name_id').text('Edit Category');
        $('.add_new_cat').show();
        $('.add_new_cat_open').css('opacity' , '1');
    });
    $('.item_cat_close').click(function() {
        $('.add_new_cat').hide();
        $('.add_new_cat_open').css('opacity' , '1');
        $('#cat_name_id').text('Add a New Category');
    });
    
    // ENABLE SUBMIT BUTTON
    
    $('.item_cat_modal input , .item_cat_modal textarea , .add_new_cat input').keyup(function() {
        var dInput = this.value;
        if (dInput) {
            $('.item_cat_modal button').removeAttr('disabled');
            $('.add_new_cat button').removeAttr('disabled');
            if ($(this).val() == '') { // check if value changed
                $('.item_cat_modal button').attr('disabled','disabled');
                $('.add_new_cat button').attr('disabled','disabled');
            }
        } else {
            $('.item_cat_modal button').attr('disabled','disabled');
            $('.add_new_cat button').attr('disabled','disabled');
        }
    });

    /*
    ===================================
       ITEM SIZE
    ===================================
    */
   $('.item_6_datatable').DataTable({
        order: [[1, 'asc']],
        columnDefs: [{ orderable: false, targets: [0 , 6] }],
        paging: false,
        select: true,       
        bInfo : false,
        searching: false,
    });
    /*
    ===================================
       FOR ITEM ALL ITEM
    ===================================
    */
    $('.item_color_btn').click(function() {
        var text = $('.item_change_title').text();
        text = text.replace("Edit", "Add");
        // updated_text = $('.item_change_title').text(text);
        // console.log(updated_text);
        $('.add_new_item_color').show();
        $('.item_color_btn').hide();

    });
    $('.item_setting_edit').click(function() {
        var text = $('.item_change_title').text();
        text = text.replace("Add", "Edit");
        // updated_text = $('.item_change_title').text(text);
        $('.add_new_item_color').show();
        $('.item_color_btn').hide();
    });
    $('.close_item_color').click(function() {
        $('.add_new_item_color').hide();
        $('.item_color_btn').show();
    });


    // ITEM OTHERS DATA TABLE
    $('.item_5_datatable').DataTable({
        order: [[1, 'asc']],
        columnDefs: [{ orderable: false, targets: [0 , 5] }],
        paging: false,
        select: true,       
        bInfo : false,
        searching: false,
    });

    $('.item_4_datatable').DataTable({
        order: [[1, 'asc']],
        columnDefs: [{ orderable: false, targets: [0 , 4] }],
        paging: false,
        select: true,       
        bInfo : false,
        searching: false,
    });

    /*
    ===================================
      VENDOR SIZE CHART IMAGE UPLOAD
    ===================================
    */
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr("src",e.target.result);
                // $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                $('#imagePreview').hide();
                $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imageUpload").change(function() {
        readURL(this);
    });

    // var sizeEditor = CKEDITOR.replace( '#size_chart_editor' );

});