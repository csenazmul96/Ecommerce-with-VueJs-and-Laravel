$(function() {
    var time = 500;
    var idx = idx2 = 0;
    var slide_width = $(".slider_bnr").width();
    var slide_count = $(".slider_bnr li").length;
    var secs = new Date().getSeconds();
    var initialAd = secs % slide_count;
    if (slide_count >= 1) {
        idx = initialAd;
        $(".slider_bnr li:eq(" + idx + ")").addClass('selected');
        $(".bigAd_tabs .tab li:eq(" + idx + ")").addClass('selected');
        $(".bigAd_items .lst_pdt").hide();
        $(".bigAd_items .lst_pdt:eq(" + idx + ")").show();
    }
    $(".section .btn_prev").click(function() {
        if (idx == 0) {
            idx2 = idx;
            idx = 5;
            $(".slider_bnr li:eq(" + idx2 + ")").removeClass('selected');
            $(".slider_bnr li:eq(" + idx + ")").addClass('selected');
            $(".bigAd_tabs .tab li:eq(" + idx2 + ")").removeClass('selected');
            $(".bigAd_tabs .tab li:eq(" + idx + ")").addClass('selected');
            $(".bigAd_items .lst_pdt").hide();
            $(".bigAd_items .lst_pdt:eq(" + idx + ")").show();
        } else {
            idx2 = idx;
            idx = idx - 1;
            $(".slider_bnr li:eq(" + idx2 + ")").removeClass('selected');
            $(".slider_bnr li:eq(" + idx + ")").addClass('selected');
            $(".bigAd_tabs .tab li:eq(" + idx2 + ")").removeClass('selected');
            $(".bigAd_tabs .tab li:eq(" + idx + ")").addClass('selected');
            $(".bigAd_items .lst_pdt").hide();
            $(".bigAd_items .lst_pdt:eq(" + idx + ")").show();
        }
        clearInterval(slider);
        slider = setInterval(slide, 5000);
        changeBigBanner(idx);
    });
    $(".section .btn_next").click(function() {
        if (idx == slide_count - 1) {
            idx2 = idx;
            idx = 0;
            $(".slider_bnr li:eq(" + idx2 + ")").removeClass('selected');
            $(".slider_bnr li:eq(" + idx + ")").addClass('selected');
            $(".bigAd_tabs .tab li:eq(" + idx2 + ")").removeClass('selected');
            $(".bigAd_tabs .tab li:eq(" + idx + ")").addClass('selected');
            $(".bigAd_items .lst_pdt").hide();
            $(".bigAd_items .lst_pdt:eq(" + idx + ")").show();
        } else {
            idx2 = idx;
            idx = idx + 1;
            $(".slider_bnr li:eq(" + idx2 + ")").removeClass('selected');
            $(".slider_bnr li:eq(" + idx + ")").addClass('selected');
            $(".bigAd_tabs .tab li:eq(" + idx2 + ")").removeClass('selected');
            $(".bigAd_tabs .tab li:eq(" + idx + ")").addClass('selected');
            $(".bigAd_items .lst_pdt").hide();
            $(".bigAd_items .lst_pdt:eq(" + idx + ")").show();
        }
        clearInterval(slider);
        slider = setInterval(slide, 5000);
        changeBigBanner(idx);
    });
    $(".slider_bnr .point").click(function() {
        idx2 = idx;
        idx = this.id * 1;
        $(".slider_bnr li:eq(" + idx2 + ")").removeClass('selected');
        $(".slider_bnr li:eq(" + idx + ")").addClass('selected');
        $(".bigAd_tabs .tab li:eq(" + idx2 + ")").removeClass('selected');
        $(".bigAd_tabs .tab li:eq(" + idx + ")").addClass('selected');
        $(".bigAd_items .lst_pdt").hide();
        $(".bigAd_items .lst_pdt:eq(" + idx + ")").show();
        clearInterval(slider);
        slider = setInterval(slide, 5000);
        changeBigBanner(idx);
    });
    $(".bigAd_tabs .tab li").hover(function() {
        clearInterval(slider);
        idx2 = idx;
        idx = this.id * 1;
        $(".slider_bnr li:eq(" + idx2 + ")").removeClass('selected');
        $(".slider_bnr li:eq(" + idx + ")").addClass('selected');
        $(".bigAd_tabs .tab li:eq(" + idx2 + ")").removeClass('selected');
        $(".bigAd_tabs .tab li:eq(" + idx + ")").addClass('selected');
        $(".bigAd_items .lst_pdt").hide();
        $(".bigAd_items .lst_pdt:eq(" + idx + ")").show();
        changeBigBanner(idx);
    }, function() {
        slider = setInterval(slide, 5000);
    });
    $(".bigAd_items .lst_pdt").hover(function() {
        clearInterval(slider);
    }, function() {
        slider = setInterval(slide, 5000);
    });
    var slider = setInterval(slide, 5000);
    function slide() {
        if (idx == (slide_count - 1)) {
            idx2 = idx;
            idx = 0;
            $(".slider_bnr li:eq(" + idx2 + ")").removeClass('selected');
            $(".slider_bnr li:eq(" + idx + ")").addClass('selected');
            $(".bigAd_tabs .tab li:eq(" + idx2 + ")").removeClass('selected');
            $(".bigAd_tabs .tab li:eq(" + idx + ")").addClass('selected');
            $(".bigAd_items .lst_pdt").hide();
            $(".bigAd_items .lst_pdt:eq(" + idx + ")").show();
        } else {
            idx2 = idx;
            idx = idx + 1;
            $(".slider_bnr li:eq(" + idx2 + ")").removeClass('selected');
            $(".slider_bnr li:eq(" + idx + ")").addClass('selected');
            $(".bigAd_tabs .tab li:eq(" + idx2 + ")").removeClass('selected');
            $(".bigAd_tabs .tab li:eq(" + idx + ")").addClass('selected');
            $(".bigAd_items .lst_pdt").hide();
            $(".bigAd_items .lst_pdt:eq(" + idx + ")").show();
        }

        changeBigBanner(idx);
    }
    function abortTimer() {
        clearInterval(slider);
    }

    changeBigBanner(idx);

    function changeBigBanner(index) {
        setTimeout(function() {
            try{
                gtag.mainAjaxEvent('changeBigBanner', index);
            } catch(e) {}
        })
    }
});
$(function() {
    var time = 500;
    var idx = idx2 = 0;
    var slide_width = $(".deal .tdList").width();
    var slide_count = $(".deal li").length;
    var secs = new Date().getSeconds();
    var initialAd = secs % slide_count;
    if (slide_count >= 1) {
        idx = initialAd;
        $(".deal li:eq(" + idx + ")").addClass('selected')
    }
    var tdSlider = setInterval(tdSlide, 5000);
    function tdSlide() {
        if (idx == (slide_count - 1)) {
            idx2 = idx;
            idx = 0;
            $(".deal li:eq(" + idx2 + ")").removeClass('selected');
            $(".deal li:eq(" + idx + ")").addClass('selected');
        } else {
            idx2 = idx;
            idx = idx + 1;
            $(".deal li:eq(" + idx2 + ")").removeClass('selected');
            $(".deal li:eq(" + idx + ")").addClass('selected');
        }
    }
    function abortTimer() {
        clearInterval(tdSlider);
    }
});

/*start of trend banner*/

/*$(function() {
	var time = 500;
	var idx = idx2 = 0;
	var slide_width = $(".trAd_bnr").width();
	var slide_count = $(".trAd_bnr li").size();
	var secs = new Date().getSeconds();
	var initialAd = secs % slide_count;
	if (slide_count >= 1) {
		idx = initialAd;
		$(".trAd_bnr .selected .pic").fadeOut(0);
		$(".trAd_bnr li:eq(" + idx + ")").addClass('selected');
		$(".trAd_bnr .selected .pic").fadeIn('4000');
	}
	$(".trAd_bnr .btn_prev").click(function() {
		if (idx == 0) {
			idx2 = idx;
			idx = 2;
			$(".trAd_bnr .selected .pic").fadeOut('3000');
			$(".trAd_bnr li:eq(" + idx2 + ")").removeClass('selected');
			$(".trAd_bnr li:eq(" + idx + ")").addClass('selected');
			$(".trAd_bnr .selected .pic").fadeOut(0);
			$(".trAd_bnr .selected .pic").fadeIn('4000');
		} else {
			idx2 = idx;
			idx = idx - 1;
			$(".trAd_bnr .selected .pic").fadeOut('3000');
			$(".trAd_bnr li:eq(" + idx2 + ")").removeClass('selected');
			$(".trAd_bnr li:eq(" + idx + ")").addClass('selected');
			$(".trAd_bnr .selected .pic").fadeOut(0);
			$(".trAd_bnr .selected .pic").fadeIn('4000');
		}
		clearInterval(slider);
		slider = setInterval(slide, 5000);
	});
	$(".trAd_bnr .btn_next").click(function() {
		if (idx == slide_count - 1) {
			idx2 = idx;
			idx = 0;
			$(".trAd_bnr .selected .pic").fadeOut('3000');
			$(".trAd_bnr li:eq(" + idx2 + ")").removeClass('selected');
			$(".trAd_bnr li:eq(" + idx + ")").addClass('selected');
			$(".trAd_bnr .selected .pic").fadeOut(0);
			$(".trAd_bnr .selected .pic").fadeIn('4000');
		} else {
			idx2 = idx;
			idx = idx + 1;
			$(".trAd_bnr .selected .pic").fadeOut('3000');
			$(".trAd_bnr li:eq(" + idx2 + ")").removeClass('selected');
			$(".trAd_bnr li:eq(" + idx + ")").addClass('selected');
			$(".trAd_bnr .selected .pic").fadeOut(0);
			$(".trAd_bnr .selected .pic").fadeIn('4000');
		}
		clearInterval(slider);
		slider = setInterval(slide, 5000);
	});
	$(".trAd_bnr .point").click(function() {
		idx2 = idx;
		idx = this.id * 1;
		$(".trAd_bnr .selected .pic").fadeOut('3000');
		$(".trAd_bnr li:eq(" + idx2 + ")").removeClass('selected');
		$(".trAd_bnr li:eq(" + idx + ")").addClass('selected');
		$(".trAd_bnr .selected .pic").fadeOut(0);
		$(".trAd_bnr .selected .pic").fadeIn('4000');
		clearInterval(slider);
		slider = setInterval(slide, 5000);
	});

	var slider = setInterval(slide, 5000);
	function slide() {
		if (idx == (slide_count - 1)) {
			idx2 = idx;
			idx = 0;
			$(".trAd_bnr .selected .pic").fadeOut('3000');
			$(".trAd_bnr li:eq(" + idx2 + ")").removeClass('selected');
			$(".trAd_bnr li:eq(" + idx + ")").addClass('selected');
			$(".trAd_bnr .selected .pic").fadeOut(0);
			$(".trAd_bnr .selected .pic").fadeIn('4000');
		} else {
			idx2 = idx;
			idx = idx + 1;
			$(".trAd_bnr .selected .pic").fadeOut('3000');
			$(".trAd_bnr li:eq(" + idx2 + ")").removeClass('selected');
			$(".trAd_bnr li:eq(" + idx + ")").addClass('selected');
			$(".trAd_bnr .selected .pic").fadeOut(0);
			$(".trAd_bnr .selected .pic").fadeIn('4000');

		}
	}
	function abortTimer() {
		clearInterval(slider);
	}
})*/

/*end of trend banner*/


$(function() {
    $(".section_rcmd .btn_arrow").click(function() {
        if ($(".section_rcmd").hasClass("open")) {
            $('.cont_rcmd').slideUp('2000');
            $(".section_rcmd").removeClass('open');
        } else {
            $('.cont_rcmd').slideDown('2000');
            $(".section_rcmd").addClass('open');
        }
    })
});
$(function() {
    var time = 500;
    var secs = new Date().getSeconds();
    var idx = idx2 = 0;
    var slide_width = $(".hot_vd").width();
    var slide_count = $(".hot_vd .tab li").length;
    var initialAd = secs % slide_count;
    if (slide_count >= 1) {
        idx = initialAd;
        $(".hot_vd .tab li:eq(" + idx + ")").addClass('selected');
        $(".hot_vd .bnr_style").hide();
        $(".hot_vd .bnr_style:eq(" + idx + ")").show();
        $(".hot_vd .lst_pdt").hide();
        $(".hot_vd .lst_pdt:eq(" + idx + ")").show();
    }
    $(".vendor_slider .btn_prev").click(function() {
        if (idx == 0) {
            idx2 = idx;
            idx = 4;
            $(".hot_vd .tab li:eq(" + idx2 + ")").removeClass('selected');
            $(".hot_vd .tab li:eq(" + idx + ")").addClass('selected');
            $(".hot_vd .bnr_style").hide();
            $(".hot_vd .bnr_style:eq(" + idx + ")").show();
            $(".hot_vd .lst_pdt").hide();
            $(".hot_vd .lst_pdt:eq(" + idx + ")").show();

        } else {
            idx2 = idx;
            idx = idx - 1;
            $(".hot_vd .tab li:eq(" + idx2 + ")").removeClass('selected');
            $(".hot_vd .tab li:eq(" + idx + ")").addClass('selected');
            $(".hot_vd .bnr_style").hide();
            $(".hot_vd .bnr_style:eq(" + idx + ")").show();
            $(".hot_vd .lst_pdt").hide();
            $(".hot_vd .lst_pdt:eq(" + idx + ")").show();

        }
        clearInterval(smallSlider);
        smallSlider  = setInterval(smallSlide, 5000);

        changeVendorSpotlight(idx);
    });

    $(".vendor_slider .btn_next").click(function() {
        if (idx == slide_count - 1) {
            idx2 = idx;
            idx = 0;
            $(".hot_vd .tab li:eq(" + idx2 + ")").removeClass('selected');
            $(".hot_vd .tab li:eq(" + idx + ")").addClass('selected');
            $(".hot_vd .bnr_style").hide();
            $(".hot_vd .bnr_style:eq(" + idx + ")").show();
            $(".hot_vd .lst_pdt").hide();
            $(".hot_vd .lst_pdt:eq(" + idx + ")").show();

        } else {
            idx2 = idx;
            idx = idx + 1;
            $(".hot_vd .tab li:eq(" + idx2 + ")").removeClass('selected');
            $(".hot_vd .tab li:eq(" + idx + ")").addClass('selected');
            $(".hot_vd .bnr_style").hide();
            $(".hot_vd .bnr_style:eq(" + idx + ")").show();
            $(".hot_vd .lst_pdt").hide();
            $(".hot_vd .lst_pdt:eq(" + idx + ")").show();

        }
        clearInterval(smallSlider);
        smallSlider  = setInterval(smallSlide, 5000);

        changeVendorSpotlight(idx);
    });
    $(".hot_vd .tab li").hover(function() {
        clearInterval(smallSlider);
        idx2 = idx;
        idx = this.id * 1;
        $(".hot_vd .tab li:eq(" + idx2 + ")").removeClass('selected');
        $(".hot_vd .tab li:eq(" + idx + ")").addClass('selected');
        $(".hot_vd .bnr_style").hide();
        $(".hot_vd .bnr_style:eq(" + idx + ")").show();
        $(".hot_vd .lst_pdt").hide();
        $(".hot_vd .lst_pdt:eq(" + idx + ")").show();

        changeVendorSpotlight(idx);
    }, function() {
        smallSlider = setInterval(smallSlide, 5000);
    });

    $(".hot_vd .lst_pdt").hover(function() {
        clearInterval(smallSlider);
    }, function() {
        smallSlider = setInterval(smallSlide, 5000);
    });

    var smallSlider = setInterval(smallSlide, 5000);
    function smallSlide() {
        if (idx == slide_count - 1) {
            idx2 = idx;
            idx = 0;
            $(".hot_vd .tab li:eq(" + idx2 + ")").removeClass('selected');
            $(".hot_vd .tab li:eq(" + idx + ")").addClass('selected');
            $(".hot_vd .bnr_style").hide();
            $(".hot_vd .bnr_style:eq(" + idx + ")").show();
            $(".hot_vd .lst_pdt").hide();
            $(".hot_vd .lst_pdt:eq(" + idx + ")").show();
        } else {
            idx2 = idx;
            idx = idx + 1;
            $(".hot_vd .tab li:eq(" + idx2 + ")").removeClass('selected');
            $(".hot_vd .tab li:eq(" + idx + ")").addClass('selected');
            $(".hot_vd .bnr_style").hide();
            $(".hot_vd .bnr_style:eq(" + idx + ")").show();
            $(".hot_vd .lst_pdt").hide();
            $(".hot_vd .lst_pdt:eq(" + idx + ")").show();
        }

        changeVendorSpotlight(idx);
    }
    function abortTimer() {
        clearInterval(smallSlider);
    }

    changeVendorSpotlight(idx);

    function changeVendorSpotlight(index) {
        setTimeout(function() {
            try {
                gtag.mainAjaxEvent('changeVendorSpotlight', index);
            } catch(e) {}
        });
    }
});
$(function() {
    var bssize = $(".bside li").length;
    var idx = Math.floor((Math.random() * bssize));
    var inVal = $(".bside li:eq(" + idx + ")").val();

    $(".bside li").click(
        function() {
            idx = this.id;
            venId = this.value;
            getNewVendorJson (venId);
        });

    function getNewVendorJson (clickvalue){
        $('.ly_loading').css('display', 'block');
        $.ajax({
            type : "GET",
            url : "/NewVendorList.json?wid=" + clickvalue + "&vcnt="
            + $(".bside li").length + "&pageId=1",
            dataType : "json",
            data : "",
            success : function(jsonData) {
                if (jsonData.success == true) {
                    nVendorList = jsonData.data;
                    $('.ly_loading').css('display', 'none');
                    newVendorProducts(nVendorList);
                    try {
                        gtag.mainAjaxEvent('newVendorItems', nVendorList);
                    } catch(err) {}
                } else {
                    $('.ly_loading').css('display', 'none');
                    var info = "Failed to get New VendorList. Please refresh page.";
                    $(this).showInfo(info);
                }
            }
        })
    }
    function newVendorProducts(nVendorList) {
        var month = new Array();
        month[0] = "Jan";
        month[1] = "Feb";
        month[2] = "Mar";
        month[3] = "Apr";
        month[4] = "May";
        month[5] = "Jun";
        month[6] = "Jul";
        month[7] = "Aug";
        month[8] = "Sep";
        month[9] = "Oct";
        month[10] = "Nov";
        month[11] = "Dec";
        date = new Date(nVendorList.ActualOpenDate);
        content = "<div class=\"hx\">" + "<h2><a class=\"nclick\" nclick-name=\"main.newvendor.vtitle\" nclick-id=\""+nVendorList.vendorId+"\" href=\"/"
            + nVendorList.DirName + "\">" + nVendorList.CompanyName
            + "</a>";
        content +="<span>" + (nVendorList.BusinessCategory||"") + "</span>";
        content += "</h2>"
            + "<span class=\"date mainpg\">Opened : "
            + month[date.getMonth()]+" "+date.getDate()+", "+date.getFullYear()+ "</span></div>"
        content += "<ul class=\"lst_pdt\">";
        content += "<p class=\"more mainpg nclick\" nclick-name=\"main.newvendor.viewmore\" nclick-id=\""+nVendorList.vendorId+"\"><a href=\"/" + nVendorList.DirName	+ "\">View More</a></p>";
        for (i = 0; i < nVendorList.items.length; i++) {
            content += "<li><div class=\"pic nclick\" nclick-name=\"main.newvendor.item\" nclick-id=\""+nVendorList.vendorId+"\">" + "<a href=\""
                + nVendorList.items[i].url + "\">" + "<img src=\""
                + nVendorList.items[i].imageUrlRoot + "/Vendors/"
                + nVendorList.items[i].dirName + "/ProductImage/list/"
                + nVendorList.items[i].pictureGeneral + "\" alt=\""
                + nVendorList.items[i].productName + "\">"
                + "</a></div></li>"
        }
        content += "</ul>";
        $('#newVendorContent').html(content).bindNclick();
        $(".bside li").removeClass('selected');
        $(".bside li:eq(" + idx + ")").addClass('selected');
    }
});