$(document).ready(function(){

    $("div.date-selector").bind("update",function(){
        var s = $(this),
            d = s.find('select.day').val(),
            m = s.find('select.month').val(),
            y = s.find('select.year').val(),
            i = s.find('input.date'),
            c = s.find('span.clear');
        if (d != '-' && m != '-' && y != '-') {
            c.show();
            i.val(y + '-' + m + '-' + d + ' 12:00:00');
        }
        else {
            i.val('');
            c.hide();
        }
    });
    $("div.date-selector").bind("clear", function(){
        var s = $(this);
        s.find('select.day').val('-');
        s.find('select.month').val('-');
        s.find('select.year').val('-');
        s.trigger('update');
    });
    $('div.date-selector').each(function(){
        var s = $(this);
        s.find('select').change(function(){
            s.trigger('update');
        });
        s.find('a.clear').click(function(e){
            e.preventDefault();
            s.trigger('clear');
        });
        s.trigger('update');
    });

    $("#toggle").click(function() {
    $(this).toggleClass("on");
    $("#menu").slideToggle();
    });

    $('button.status').click(function(){
        var f =$(this).closest('form');
        f.find('input[name="fields[resultStatus]"]').val($(this).data('status'));
        f.submit();
    });

    // menu
    $('.menu-tab').click(function(){
        $('.menu-hide').toggleClass('show');
        $('.menu-tab').toggleClass('active');
    });
    $('a').click(function(){
        $('.menu-hide').removeClass('show');
        $('.menu-tab').removeClass('active');
    });
    // login form
    $.fn.extend( jQuery.easing, {
        eioe: function( ø, t, b, c, d ) {
            if(t==0) return b;
            if(t==d) return b+c;
            if( (t /= d/2) < 1 ) return c/2 * Math.pow( 2, 10 * (t - 1) ) + b;
            return c/2 * ( -Math.pow( 2, -10 * --t ) + 2 ) + b;
        }
    });
    $.fn.toggleAttr = function(a, v1, v2) {
        return this.each(function() {
            var $t = $(this),
                v  = $t.attr(a) === v1 ? v2 : v1;
            $t.attr(a, v)
        });
    };
    // toggle login/password reset form
    $('#login-combined .toggle').click(function(){
        if($('#form-login').hasClass("hide")) {
            $('#form-login').removeClass("hide");
        }
        else {
            $('#form-login').addClass("hide");
        }
        $('#form-password').slideToggle({
            easing: 'eioe',
            duration: 850
        });
        return false;
    });
    // floating labels
    var onClass = "on";
    var showClass = "show";
    $("input").bind("checkval",function(){
        var label = $(this).prev("label.float");
        if(this.value !== ""){
            label.addClass(showClass);
        } else {
            label.removeClass(showClass);
        }
    }).on("keyup",function(){
        $(this).trigger("checkval");
    }).on("focus",function(){
        $(this).prev("label").addClass(onClass);
    }).on("blur",function(){
        $(this).prev("label").removeClass(onClass);
    }).trigger("checkval");
    // password reset form
    $(".confirm-password-showhide .trigger-password, .password-showhide .trigger-password").click(function() {
        var c = $(this).parent().attr("class").replace("-showhide", "");
        var obj = $("#" + (c.indexOf("confirm") > -1 ? "confirmPassword" : "password"));
        obj.attr("type", obj.attr("type") == "text" ? "password" : "text");
        $(this).text($(this).text() == "Hide" ? "Show" : "Hide");
    });
    $('#showResetPasswordPanel').click(function(event) {
        if ($('.container-reset-password').hasClass('dismiss-reset-password')) {
            $('.container-reset-password').removeClass('dismiss-reset-password').addClass('selected-reset-password').show();
        }
        event.preventDefault();
    });
    $('#closeResetPasswordPanel').click(function(event) {
        if ($('.container-reset-password').hasClass('selected-reset-password')) {
            $('.container-reset-password').removeClass('selected-reset-password').addClass('dismiss-reset-password');
        }
        event.preventDefault();
    });
    // cpd navigation
    $('nav.cpd li.nav-closed a[href="#"]').click(function(e){
        e.preventDefault();
        var li = $(this).closest('li'),
            ul = $(this).siblings('ul:eq(0)');
        if (li.hasClass('nav-closed')) {
            ul.slideDown(function(){li.attr('class', 'nav-open')});
            return;
        }
        ul.slideUp(function(){li.attr('class', 'nav-closed')});
    });
    // toggle accordion
    $('[data-target]').click(function(e){
        e.preventDefault();
        var t = $($(this).data('target'));
        if (t.hasClass('closed')) {
            t.slideDown(function(){t.removeClass('closed')});
            return;
        }
        t.slideUp(function(){t.addClass('closed')});
    });
    // set datefield values
    $('div.dateField').each(function(){
        var dateField = $(this),
            hidden = dateField.find('input[type="hidden"].date');
        dateField.find('input').change(function(){
            var day = dateField.find('input.day').val(),
                month = dateField.find('input.month').val(),
                year = dateField.find('input.year').val();
            if (day && month && year) {
                hidden.val(day + '/' + month + '/' + year);
            }
        })
    });
    // set select values
    $('select[data-value]').each(function(){
        $(this).val($(this).data('value'));
    });
    // make relation select name single not array if empty
    $('select[data-relation]').on('change', function(){
        $(this).attr('name', $(this).data('relation') + ($(this).val() ? '[]' : ''))
    });
    $('select[data-relation]').change();
    // entry action links
    $('a.action').on('click', function(e){
        e.preventDefault();
        var action = $(this).data('action'),
            row = $(this).closest('.item'),
            deleteRow = false,
            reload = false;
        if (action == 'lantra/entries/resetResult') {
            if ( ! confirm('Are you sure you want to unlink all attempts?')) {
                return false;
            }
            var data = {entryId: $(this).data('id')};
            deleteRow = true;
        }
        if (action == 'lantra/entries/deleteEntry') {
            if ( ! confirm('Are you sure you want to delete this entry?')) {
                return false;
            }
            var data = {entryId: $(this).data('id'), ref: $(this).data('ref')};
            deleteRow = true;
        }
        if (action == 'lantra/entries/endorseEvidence') {
            if ( ! confirm('Are you sure you want to endorse this result?')) {
                return false;
            }
            var data = {entryId: $(this).data('id'), ref: $(this).data('ref')};
        }
        if (action == 'lantra/categories/deleteCategory') {
            if ( ! confirm('Are you sure you want to delete this category?')) {
                return false;
            }
            var data = {categoryId: $(this).data('id')};
            deleteRow = true;
        }
        if (action == 'lantra/users/deleteUser') {
            if ( ! confirm('Are you sure you want to suspend this user?')) {
                return false;
            }
            var data = {userId: $(this).data('id')};
            reload = true;
        }
        if (action == 'lantra/users/restoreUser') {
            if ( ! confirm('Are you sure you want to restore this user?')) {
                return false;
            }
            var data = {userId: $(this).data('id')};
            reload = true;
        }
        if (action == 'lantra/reports/delete') {
            if ( ! confirm('Are you sure you want to delete this report?')) {
                return false;
            }
            var data = {entryId: $(this).data('id')};
            reload = true;
        }
        if (action == 'lantra/reports/run') {
            if ( ! confirm('Are you sure you want to run this report?')) {
                return false;
            }
            var data = {entryId: $(this).data('id')};
        }
        if (action == 'lantra/entries/pendingResult') {
            if ( ! confirm('Are you sure you want to request endorsement?')) {
                return false;
            }
            var data = {id: $(this).data('id'), ref: $(this).data('ref'), userId: $(this).data('userid')};
        }
        data[window.csrfTokenName] = window.csrfTokenValue;
        $('body').addClass('loading');
        $.post("/actions/" + action, data, function(response) {
            if (response.redirect) {
                window.location.replace(response.redirect);
            }
            else if (response.success) {
                $('body').removeClass('loading');
                if (deleteRow) {
                   row.fadeOut().remove();
                }
                if (reload) {
                    window.location = window.location;
                }
                alert(response.message);
            }
            else {
                $('body').removeClass('loading');
                if (response.message) {
                    alert(response.message);
                }
                else {
                    console.log(response);
                    alert('Undefined server error, check the logs.');
                }
            }
        }).fail(function(error) {
            $('body').removeClass('loading');
            console.log(error);
            alert('Server error, check the console.');
        });
    });

    $('form:not(.no-loading)').submit(function(){
        $('body').addClass('loading');
    });

    // submit select filter
    $('form.filter select').on('change', function(){
        // $(this).closest('form').submit();
    });
    // filter teams by selected companies
    $('input.scopeCompany').on('change', function () {
        var c = $('div#reportScopeCompanies').find('div.checkboxes'),
            t = $('div#reportScopeTeams').find('div.checkboxes');
        c.find('input.scopeCompany').each(function(){
            var companyId = $(this).data('id'),
                teamInputs = t.find('label.company-' + companyId);
            if($(this).prop('checked')) {
                teamInputs.show();
            }
            else {
                teamInputs.each(function() {
                    $(this).find('input').prop('checked', false);
                    $(this).hide();
                });
                $('#roles-all').prop('checked', false);
            }
        });
    });
    $('input.scopeCompany').eq(0).change();
    // select all
    $('input.scopeAll').on('change', function () {
       var c = $(this).closest('p').siblings('div.checkboxes'),
           checked =  $(this).prop('checked') === true;
        c.find('input').each(function() {
            $(this).prop('checked', checked).change();
        });
    });
    // show hide module specific report fields
    $('select#reportType').change(function(){
        if ($(this).val() == 'results') {
            $('div#modulesResultsFields').show();
        }
        else {
            $('div#modulesResultsFields').hide();
        }
    });
    $('select#reportType').change();
    // filter modules by job role
    $('input.scopeRole').on('change', function () {
        var r = $('div#reportScopeRoles').find('div.checkboxes'),
            m = $('div#reportScopeModules').find('div.checkboxes');
        r.find('input.scopeRole').each(function() {
            var roleId = $(this).data('id'),
                moduleInputs = m.find('label.role-' + roleId);
            if ($(this).prop('checked')) {
                moduleInputs.show();
            }
            else {
                moduleInputs.each(function () {
                    $(this).find('input').prop('checked', false);
                    $(this).hide();
                })
            }
        });
    });

    $('#reportAutomated').hide();

    // show automated report inputs
    $('#buttonAutomated').click(function (e) {
        e.preventDefault();
        $('#buttonAutomated').hide();
        $('#reportAutomated').show();
        $('input[name="automated"]').val(1);
        $('#buttonSubmit').text('Save Report');
    })

    $('#reportType').change(function(){
        var type = $(this).val();
        $('form#reports').find('div.fields').hide();
        $('form#reports').find('div.fields-' + type).show();
    });

    $('#reportType').change();

    $('#reportResultType').change(function(){
        var type = $(this).val();
        if (type != 'unitResult') {
            $('#reportUnitsDropdown').hide();
        }
        else {
            $('#reportUnitsDropdown').show();
        }
    });

    $('#reportResultType').change();

    $('#resultUnit').change(function(){
        var unitId = $(this).val();
        if (unitId) {
            $('#resultTitle').hide();
        }
        else {
            $('#resultTitle').show();
        }
    });

    $('#resultUnit').change();

    // delete user photo
    $("#deleteUserPhotoAction").on('click', function(e) {
        e.preventDefault();
        $("input[name='deleteUserPhoto']").val('zap');
        $("#form-photo").submit();
    });

    // expand / collapse account details
    $(".link-toggle-expand").click(function()
    {
    $('.link-toggle-expand').removeClass("show");
    $('.link-toggle-expand').addClass("hide");
    $('.link-toggle-collapse').removeClass("hide");
    $('.link-toggle-collapse').addClass("show");
    $('.link-expand').removeClass("show");
    $('.link-expand').addClass("hide");
    $('.link-collapse').removeClass("hide");
    $('.link-collapse').addClass("show");
    $("#account-details-expand").slideToggle(400);
    });
    $(".link-toggle-collapse").click(function()
    {
    $('.link-toggle-collapse').removeClass("show");
    $('.link-toggle-collapse').addClass("hide");
    $('.link-toggle-expand').removeClass("hide");
    $('.link-toggle-expand').addClass("show");
    $('.link-expand').removeClass("hide");
    $('.link-expand').addClass("show");
    $('.link-collapse').removeClass("show");
    $('.link-collapse').addClass("hide");
    $("#account-details-expand").slideToggle(400);
    });

    /* cpd module groups and tabs */
    $('.tabgroup > div').hide();
    $('.module-group-tabs').hide();

    $('.tabs a').click(function(e){
        e.preventDefault();
        var $this = $(this),
        tabgroup = '#'+$this.parents('.tabs').data('tabgroup'),
        others = $this.closest('ul').find('a'),
        target = $this.attr('href');
        others.removeClass('active');
        $this.addClass('active');
        $(tabgroup).children('div').hide();
        $(target).show();
        // make sure first unit group is shown
        if (target.match("^#tab")) {
            $(target).find('ul.tabs:first-of-type').find('li:first-of-type a').click();
        }
    });

    // add on load module click
    var cpdWrapper = $('#cpd-wrapper');
    if ( cpdWrapper.data('ref') ) {
        var moduleLink = $('.tabs a[href="#' + cpdWrapper.data('ref') + '"]'),
            moduleGroupLinkId = moduleLink.closest('div.groups-tab-group').attr('id');
        $('a[href="#' + moduleGroupLinkId + '"]').click();
        moduleLink.click();
    }
    else {
       $('.tabs:first-of-type li:first-of-type a').click();
    }

    $('.module-group-tabs').show();

    $('input[name=companyManagers]').change(function(){
        if ($(this).attr('id') == 'userManager' && $(this).is(':checked')){
            return $('div#manager-fields').removeClass('hide');
        }
        $('div#manager-fields').addClass('hide');
    }).change();

    $('input#teamManagers').click(function(){
        if ($(this).is(':checked')){
            return $('div#team-fields').removeClass('hide');
        }
        $('div#team-fields').addClass('hide');
    });
});
