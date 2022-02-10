$(document).ready(function() {

    /*
    ===============================================
        ACCORDION 
    ===============================================
    */

    $(document).on('click', '[data-toggle="accordion"]', function() {
        $($(this).toggleClass('open_acc'));
        let target = $($(this).data('target'));
        let classes = $(this).data('class');
        target.slideToggle();
    });

    $(document).on('click', '[data-toggle="accordion_noslide"]', function() {
        // var $target1 = $($(this).siblings().removeClass('open_acc'));
        let $target2 = $($(this).toggleClass('open_acc'));
        let $target = $($(this).data('target'));
        let classes = $(this).data('class');
        $target.toggleClass(classes);
        $target.toggleClass(classes).toggle();
    });


    /*
    ===============================================
        MODAL
    ===============================================
    */


    $(function() {
        //----- OPEN
        $('[data-modal-open]').on('click', function(e) {
            var targeted_modal_class = jQuery(this).attr('data-modal-open');
            $('[data-modal="' + targeted_modal_class + '"]').addClass('open_modal');

            e.preventDefault();
        });

        //----- CLOSE
        $('[data-modal-close] , .modal_overlay').on('click', function(e) {
            var targeted_modal_class = jQuery(this).attr('data-modal-close');
            $('[data-modal="' + targeted_modal_class + '"]').removeClass('open_modal');

            e.preventDefault();
        });
    });


    /*
    =================================================
        SEARCH AUTOCOMPLETE
    =================================================
    */

    $(function() {
        var availableTags = [
            "ActionScript",
            "AppleScript",
            "Asp",
            "BASIC",
            "C",
            "C++",
            "Clojure",
            "COBOL",
            "ColdFusion",
            "Erlang",
            "Fortran",
            "Groovy",
            "Haskell",
            "Java",
            "JavaScript",
            "Lisp",
            "Perl",
            "PHP",
            "Python",
            "Ruby",
            "Scala",
            "Scheme"
        ];
        $("#tags").autocomplete({
            source: availableTags
        });
    });

    /*
    =================================================
        DATE PICKER
    =================================================
    */

    $('.datepicker').each(function() {
        $(this).datepicker({
            minDate: 0
        });
    });
    $('.datepicker2_col').each(function() {
        $(this).datepicker({
            numberOfMonths: [1, 2]
        });
    });

    /*
    =================================================
        ITEM INVENTORY SORTING
    =================================================
    */

    $(function() {
        $(".sortable").sortable({
            containment: 'parent',
            tolerance: 'pointer',
            cursor: 'move',
        });
    });

    /*
    =================================================
        TAB 
    =================================================
    */

    $('.tabs li , .tabs a').click(function() {
        // e.preventDefault();
        $(this).siblings().removeClass("active");
        $(this).addClass("active");
        $(".tab_content").removeClass("show");
        $($(this).attr("href")).addClass("show");
    });


    /*
    =================================================
        SHOW DROPDOWN
    =================================================
    */
    $('.dropdown button').click(function() {
        $(this).parent().children('.dropdown_menu').toggle();
    });

    $(document).mouseup(function(e) {
        $('.dropdown').each(function() {
            var dropdown = $(this);
            if (!dropdown.is(e.target) && dropdown.has(e.target).length === 0) {
                dropdown.find('.dropdown_menu').hide();
            }
        });
        // if the target of the click isn't the container nor a descendant of the container

    });

});