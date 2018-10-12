/**
 * Created by abirillo on 21.03.16.
 */

var plansSelected = "";

function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function deleteCookie(name) {
    var date = new Date;
    date.setDate(date.getDate() - 100);
    document.cookie = name + "=false;path=/;expires=" + date.toUTCString();
}

function saveSelectedPlans() {
    var date = new Date;
    date.setDate(date.getDate() + 1);
    document.cookie = getCookie("state") + "PlanIdsSelected=" + plansSelected + ";path=/;expires=" + date.toUTCString();
}

function deleteSelectedPlans() {
    deleteCookie(getCookie("state") + "PlanIdsSelected");
}

$(function () {
    $('a.fancybox').click(function (e) {
        e.preventDefault();
    });
    $('.fancybox:not(.mid-modal):not(.big-modal)').fancybox({
        'width': 570,
        'scrolling': 'visible',
        tpl: {
            closeBtn: '<a class="close-btn" href="javascript:;">Close <i class="fa fa-times-circle"></i></a>'
        }
    });
    $('.fancybox.mid-modal').fancybox({
        'width': 770,
        'scrolling': 'visible',
        tpl: {
            closeBtn: '<a class="close-btn" href="javascript:;">Close <i class="fa fa-times-circle"></i></a>'
        }
    });
    $('.fancybox.big-modal').fancybox({
        'width': 1170,
        'scrolling': 'visible',
        tpl: {
            closeBtn: '<a class="close-btn" href="javascript:;">Close <i class="fa fa-times-circle"></i></a>'
        }
    });

    if (getCookie("needToShowIntro")) {
        deleteCookie("needToShowIntro");
        $('a[href="#yearChanged"]').click();
    }

    $(document).on("click",".year-return",function(e){
        e.preventDefault();
        var year = parseInt(getCookie("year")) === 2018 ? 2019 : 2018;
        var date = new Date;
        date.setDate(date.getDate() + 30);
        document.cookie = "year=" + year + ";path=/;expires=" + date.toUTCString();
        window.location.reload();
    });

    $(document).on("click", ".print-plans", function(){
        planPrint($(this).data("url"),$(this).data("plans"));
    });

    $(document).on("click", ".print-career", function(){
        careerPrint($(this).data("url"));
    });

    $(document).on("change", ".upload-file", function(){
        var fileInput = $(this);
        var maxSize = fileInput.data('max-size');
        if(fileInput.get(0).files.length){
            var fileSize = fileInput.get(0).files[0].size / 1024; // in kbytes
            if(fileSize>maxSize){
                alert('File size is more then ' + maxSize + ' KB!');
                return false;
            }
        }

        var fullPath = document.getElementById('file').value;
        if (fullPath) {
            var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
            var filename = fullPath.substring(startIndex);
            if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                filename = filename.substring(1);
            }
            $(this).closest("div").find("label").text(filename);
        }
    });

    hostname = new RegExp(location.host);
    // Act on each link
    $('a').each(function(){

        // Store current link's url
        var url = $(this).attr("href");
        if (url) {
            if(hostname.test(url) || url.slice(0,1) == "/"){
                $(this).addClass('local');
            }
            else if(url.slice(0, 1) == "#"){
                $(this).addClass('anchor');
            }
            else if(url.slice(0, 7) == "mailto:"){
                $(this).addClass('email');
            }
            else if(url.slice(0, 11) == "javascript:"){
                $(this).addClass('js');
            }
            else if(url.slice(0, 37) == "https://eon-site-pub.s3.amazonaws.com" || url.slice(0, 38) == "https://s3.amazonaws.com/eon-site-pub/"){
                $(this).addClass('amazon');
            }
            else {
                $(this).addClass('external');
            }
        }
    });


    $(document).on("click",".external", function(e) {
        if(!confirm("You have clicked a link that will take you away from the Eon Health Medicare Advantage web pages.  To leave this page, please click OK. To remain on this page, please click Cancel.")) {
            e.preventDefault();
        }
    });

    /** FONT SIZE LOGIC */

    $('ul.butts>li').click(function () {
        if (!$(this).hasClass('active')) {
            $('ul[data-var="' + $(this).closest('ul').data('var') + '"]>li.active').removeClass('active');
            $('ul[data-var="' + $(this).closest('ul').data('var') + '"]>li[data-param="' + $(this).data('param') + '"]').addClass('active');
            $('body').attr('data-' + $(this).closest('ul').data('var'), $(this).data('param'));
            var date = new Date;
            date.setDate(date.getDate() + 30);
            document.cookie = $(this).closest('ul').data('var') + "=" + $(this).data('param') + ";path=/;expires=" + date.toUTCString();
        }
    });

    /** YEARS LOGIC */

    $('ul.year-tabs>.tab').click(function () {
        if (!$(this).hasClass('active')) {
            $('ul[data-var="' + $(this).closest('ul').data('var') + '"]>li.active').removeClass('active');
            $('ul[data-var="' + $(this).closest('ul').data('var') + '"]>li[data-param="' + $(this).data('param') + '"]').addClass('active');
            var date = new Date;
            date.setDate(date.getDate() + 30);
            document.cookie = $(this).closest('ul').data('var') + "=" + $(this).data('param') + ";path=/;expires=" + date.toUTCString();
            document.cookie = "needToShowIntro=true;path=/;expires=" + date.toUTCString();
            window.location.reload();
        }
    });

    var popovers = $("[data-toggle=\"popover\"]"),
        defaultPopover = $(".default[data-toggle=\"popover\"]"),
        defaultShown = false;

    popovers.popover();

    defaultPopover.on("shown.bs.popover", function () {
        if (!defaultShown) {
            $(".popover-title").append("<span class=\"fa fa-times close-popover\"></span>");
            defaultShown = true;
            var date = new Date;
            date.setDate(date.getDate() + 30);
            document.cookie = defaultPopover.data("page") + "DefaultShown=true;path=/;expires=" + date.toUTCString();
        }
    });

    if (!getCookie(defaultPopover.data("page") + "DefaultShown")) {
        defaultPopover.popover("show");
    }

    defaultPopover.on("shown.bs.popover", function () {
        return false;
    });

    setTimeout(function() {
        defaultPopover.popover("hide");
    }, 10000);

    $(document).on("click",".close-popover",function(){
        $("[data-toggle=\"popover\"]").popover("hide");
    });

    /** MAIN PAGE */

    $msInfos = $('.main-section .info');
    $msInfos.click(function () {
        $msInfos.removeClass('active');
        $(this).addClass('active');
    });
    $(function () {
        $(document).click(function (event) {
            if ($(event.target).closest($msInfos).length) return;
            $msInfos.removeClass('active');
            event.stopPropagation();
        });
    });

    /** CONTACT TABS */

    var $tabs = $('#tabs');
    $tabs.on('click', '[data-target]', function () {
        var $this = $(this);
        $tabs.find('.tabs-content > div').removeClass('active');
        $tabs.find('#' + $this.data('target')).addClass('active');
        $this.siblings('[data-target]').removeClass('active');
        $this.addClass('active');
    });

    $('.plans-filter input').change(function () {
        $('.plans-list .card').hide();
        $('.plans-list .card[data-filter="' + $('.plans-filter input:checked').data('filter') + '"]').fadeIn(200);
    });

    /** SPOILERS */

    $('.spoiler .spolier-head').click(function (e) {
        e.preventDefault();
        if ($(this).closest(".spoilers-list") && !$(this).find(".toggle-spoiler").hasClass("active") && $(this).closest(".spoilers-list").find(".toggle-spoiler.active")) {
            $(this).closest(".spoilers-list").find(".toggle-spoiler.active").toggleClass("active").closest('.spoiler').find('.spoiler-body').toggleClass('collapsed');
        }
        $(this).find('.toggle-spoiler').toggleClass('active').closest('.spoiler').find('.spoiler-body#' + $(this).data('toggle')).toggleClass('collapsed');
    });

    $('.spoiler-body .form-group label').click(function () {
        $(this).closest('.form-group').find('label.active').removeClass('active');
        $(this).addClass('active');
    });

    $('.main-section').mouseenter(function () {
        $(this).addClass('active');
    });
    $('.main-section').mouseleave(function () {
        $(this).removeClass('active');
    });
    $('.main-section a').focus(function () {
        $(this).closest('.main-section').addClass('active');
    });
    $('.main-section a').blur(function () {
        $(this).closest('.main-section').removeClass('active');
    });

    /** JOBS WIDGET FIX ON SCROLL*/

    window.onscroll = catchScroll;
    var menuTop = 600;
    var timeOutId = 0;
    var jitterBuffer = 10;

    function catchScroll() {
        if (timeOutId) clearTimeout(timeOutId);
        timeOutId = setTimeout(function () {
            DoStuffOnScrollEvent()
        }, jitterBuffer);
    }

    function DoStuffOnScrollEvent() {
        var scroll_top = $(document).scrollTop();
        var body = $('body');
        if (scroll_top >= menuTop) {
            if (!body.hasClass('fixed')) {
                body.addClass('fixed');
            }
        } else {
            body.removeClass('fixed');
        }
    }

    function compareCount() {
        var compareBtns = $(".compare-btn-group");
        var checked = $(".compare-checkbox:checked");
        var unchecked = $(".compare-checkbox:not(:checked)");
        plansSelected = "";
        if (checked.length < 2) {
            compareBtns.removeClass("active");
        } else if (checked.length >= 2) {
            if (!compareBtns.hasClass("active")) {
                compareBtns.addClass("active");
            }
            if (checked.length == 3) {
                unchecked.attr("disabled", "disabled");
            } else {
                unchecked.removeAttr("disabled");
            }
        }
        checked.each(function () {
            plansSelected += $(this).val() + ",";
        });
        compareBtns.find(".compare").html(checked.length + " plans for comparison<span>(click to compare)</span>").attr("data-plans", plansSelected);
    }

    $(document).on("click", ".compare-checkbox", function () {
        compareCount();
        saveSelectedPlans();
    });

    $(document).on("click", ".compare-btn-group .btn.cancel", function (e) {
        e.preventDefault();
        $(".compare-checkbox:checked").prop("checked", false);
        $(".compare-checkbox[disabled]").removeAttr("disabled");
        compareCount();
        deleteSelectedPlans();
    });

    compareCount();

    $(document).on("click",".spoiler-wrapper .spoiler-toggle", function(e){
        e.preventDefault();
        var spoilerWrapper = $(this).parent();
        var spoilerContent = spoilerWrapper.find(".spoiler-content");
        spoilerWrapper.toggleClass("open");
        if (spoilerWrapper.hasClass("open")) {
            spoilerWrapper.attr("data-initial-height",spoilerWrapper.height() + "px");
            spoilerWrapper.css("height",spoilerContent.height() + "px");
        } else {
            spoilerWrapper.css("height",spoilerWrapper.data("initial-height"));
        }

    });
});

function showQuickFacts(url) {
    var body = $(".modal#quickFacts .modal-body");
    body.html("<p style='font-size:20px' class='text-center'>Please wait...</p>")
    $.ajax({
        url: url,
        success: function (data) {
            body.html(data);
        },
        error: function (xhr, str) {
            alert('Error: ' + xhr.responseCode);
        }
    });
}

function showPlanDetails(url) {
    var body = $(".modal#viewDetails .modal-body");
    body.html("<p style='font-size:20px' class='text-center'>Please wait...</p>")
    $.ajax({
        url: url,
        success: function (data) {
            body.html(data);
        },
        error: function (xhr, str) {
            alert('Error: ' + xhr.responseCode);
        }
    });
}

function showPlanCompare(url) {
    var body = $(".modal#comparePlans .modal-body");
    var data = {
        "plans": plansSelected
    };
    body.html("<p style='font-size:20px' class='text-center'>Please wait...</p>")
    $.ajax({
        type: 'POST',
        data: data,
        url: url,
        success: function (data) {
            body.html(data);
        },
        error: function (xhr, str) {
            alert('Error: ' + xhr.responseCode);
        }
    });
}

function call(form) {
    var btn = form.find('button');
    btn.attr('disabled','disabled');
    var formHeader = $('.modal#formResult .modal-header');
    var formBody = $('.modal#formResult .modal-body');
    $.ajax({
        type: 'POST',
        url: form.data("url"),
        data: new FormData(form[0]),
        cache: false,
        contentType: false,
        processData: false,

    success: function (data) {
            if (data.success) {
                form.find("label.btn-file").text("Browse for File...");
                form[0].reset();
            }
            formHeader.html(data['title']);
            formBody.html(data['message']);
            $('a[href="#formResult"]').click();
            btn.removeAttr('disabled');
        },
        error: function (xhr, str) {
            formHeader.html('Error');
            formBody.html('<div class=\"form-result-image error\"></div><h3>Mailer error!</h3><p>Error: ' + xhr.responseCode + '</p>');
            $('a[href="#formResult"]').click();
            btn.removeAttr('disabled');
        }
    });
}


function planPrint(url, data) {

    var newWin = window.open('', 'Print Page', 'height=700,width=900, scrollbars=yes');

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            "plans": data
        },
        success: function (data) {
            newWin.document.write(data);
            newWin.document.close();
            newWin.focus();
            newWin.print();
        },
        error: function (xhr, str) {
            alrt(xhr.responseCode);
        }
    });
}


function careerPrint(url) {

    var newWin = window.open('', 'Print Page', 'height=700,width=900, scrollbars=yes');

    $.ajax({
        type: 'POST',
        url: url,
        success: function (data) {
            newWin.document.write(data);
            newWin.document.close();
            newWin.focus();
            newWin.print();
        },
        error: function (xhr, str) {
            alrt(xhr.responseCode);
        }
    });
}