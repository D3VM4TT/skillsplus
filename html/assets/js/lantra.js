$(document).ready(function(){

    $('select.package-module-group').change(function(){
        var moduleGroupId = $(this).val(),
            revision = $(this).find('option[value="' + moduleGroupId + '"]').data('revision');
        $('div.taskbook-optional').hide();
        $('div.taskbook-optional.taskbook-optional-' + moduleGroupId).show();
        $('input#package-revision').val(revision);
    }).change();

    $('select.package-level').change(function(){
        var level = $(this).val(),
            optionalSelectInputs = $('div.taskbook-optional').find('select.optional-level');
        if (level == 5) {
            optionalSelectInputs.val(level).prop('disabled', true).css('opacity', 0.5);
        }
        else {
            optionalSelectInputs.prop('disabled', false).css('opacity', 1);
        }
    }).change();

    $('select.optional-level').change(function(){
        updateOptional();
    });

    updateOptional = function() {
        var n = 1;
        $('form#taskbooks').find('.optional-hidden').remove();
        $('tr.optional-module').each(function(){
            var c = $(this).find('input[type=checkbox]'),
                l = $(this).find('select').val(),
                t = $('#input-template').clone().html();
            if (c.is(':checked')) {
                t = t.replace(/{n}/g, n).replace(/{optionalModuleGroupId}/g, c.val()).replace(/{optionalLevel}/g, l);
                $('form#taskbooks').prepend($(t));
                n ++;
            }
        });
    };


    $('input.optional').change(function(){
        updateOptional();
    }).change();

    $('.btn-toggle-small').on('click', function() {
        $('.sidebar').toggleClass('is-collapsed');
        $('.sidebar-not-sticky').toggleClass('hide');
        $('.content').toggleClass('is-full-width');
    });

    $("#btn-toggle").click(function() {
    $(".arrow").toggleClass("btn-right");
    $(".btn-close").toggleClass("is-hidden");
    $(".btn-open").toggleClass("is-visible");
    });

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

    $('form.result').submit(function(){
        $('body').addClass('loading');
        var f = $(this),
            v = true,
            s = f.find('input[name="fields[resultStatus]"]').val(),
            message = $('<span>').addClass('error').text('This field is required!');

        // draft status ignores required
        if (s === 'draft') {
            return true;
        }

        f.find('div.field--wrapper').removeClass('error');
        f.find('span.error').remove();

        f.find('div.field--wrapper').each(function(){
            var t = $(this).data('type'),
                r = $(this).data('required'),
                e = false;

            // not required
            if (r == 0) {
                return;
            }
            // make sure input
            if (t == 'text' || t == 'number' || t == 'date') {
                if (!$(this).find(':input').eq(0).val()) {
                    e = true;
                }
            }
            if (t == 'asset' && !$(this).find('div.files').find('input').length) {
                e = true;
            }
            // add error message and class
            if (e) {
                $(this).append(message.clone());
                $(this).addClass('error');
                v = false;
            }
        });
        // scroll to first error field
        if (!v) {
            $('html,body').animate({
                scrollTop: f.find('div.field--wrapper.error').eq(0).offset().top - 300
            }, 500);

            $('body').removeClass('loading');
        }
        return v;
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
        if (action == 'entries/reset-result') {
            if ( ! confirm('Are you sure you want to unlink all attempts?')) {
                return false;
            }
            var data = {entryId: $(this).data('id')};
            deleteRow = true;
        }
        else if (action == 'entries/delete-entry') {
            if ( ! confirm('Are you sure you want to delete this entry?')) {
                return false;
            }
            var data = {entryId: $(this).data('id'), ref: $(this).data('ref')};
            deleteRow = true;
        }
        else if (action == 'entries/endorse-evidence') {
            if ( ! confirm('Are you sure you want to endorse this result?')) {
                return false;
            }
            var data = {entryId: $(this).data('id'), ref: $(this).data('ref')};
        }
        else if (action == 'categories/delete-category') {
            if ( ! confirm('Are you sure you want to delete this category?')) {
                return false;
            }
            var data = {categoryId: $(this).data('id')};
            deleteRow = true;
        }
        else if (action == 'users/suspend-user') {
            if ( ! confirm('Are you sure you want to suspend this user?')) {
                return false;
            }
            var data = {userId: $(this).data('id')};
            reload = true;
        }
        else if (action == 'users/delete-user') {
            if ( ! confirm('Are you sure you want to delete this user?')) {
                return false;
            }
            var data = {userId: $(this).data('id')};
            deleteRow = true;
        }
        else if (action == 'users/restore-user') {
            if ( ! confirm('Are you sure you want to restore this user?')) {
                return false;
            }
            var data = {userId: $(this).data('id')};
            reload = true;
        }
        else if (action == 'reports/delete-report') {
            if ( ! confirm('Are you sure you want to delete this report?')) {
                return false;
            }
            var data = {entryId: $(this).data('id')};
            deleteRow = true;
        }
        else if (action == 'reports/run-report') {
            if ( ! confirm('Are you sure you want to run this report?')) {
                return false;
            }
            var data = {entryId: $(this).data('id')};
        }
        else if (action == 'entries/pending-result') {
            if ( ! confirm('Are you sure you want to request endorsement?')) {
                return false;
            }
            var data = {id: $(this).data('id'), ref: $(this).data('ref'), userId: $(this).data('userid')};
            reload = true;
        }
        else if (action == 'packages/request-assessment') {
            if ( ! confirm('Are you sure you want to request assessment?')) {
                return false;
            }
            var data = {id: $(this).data('id'), userId: $(this).data('userid')};
            reload = true;
        }
        else {
            alert('Invalid action ' + action);
        }
        data[window.csrfTokenName] = window.csrfTokenValue;
        $('body').addClass('loading');
        $.post("/sp/" + action, data, function(response) {
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

    // select package assessor
    $('select.assessor').on('change', function(e){
        $('body').addClass('loading');
        var data = {packageId: $(this).data('id'), userId: $(this).val()};
        data[window.csrfTokenName] = window.csrfTokenValue;
        $.post("/sp/users/package-assessor", data, function(response) {
            $('body').removeClass('loading');
            alert(response.message);
        }).fail(function(error) {
            $('body').removeClass('loading');
            console.log(error);
            alert('Server error, check the console.');
        });
    });

    // update report managers based on selected companies
    $('select#reportCompanies').on('change', function () {
        var data = {companyIds: $(this).val()};
        data[window.csrfTokenName] = window.csrfTokenValue;
        $('#reportRecipientsLabel span').show();
        $.post("/sp/users/company-managers", data, function(response) {
            $('#reportRecipientsLabel span').hide();
            var selectedIds = $("select#reportRecipients").val();
            // first get rid of non selected
            $('select#reportRecipients option').not(':selected').remove();
            $('select#reportRecipients').trigger('change');
            $.each(response, function (id, name) {
                if (!$('select#reportRecipients').find("option[value='" + id + "']").length) {
                    var newOption = new Option(name, id);
                    $('select#reportRecipients').append(newOption).trigger('change');
                }
            });
        }).fail(function(error) {

        });
    });

    $('select#reportCompanies').change();

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
        var a = $(this),
            ul = a.closest('ul'),
            select = $('#module-select-' + ul.data('module-group')),
            tabgroup = '#' + a.parents('.tabs').data('tabgroup'),
            others = ul.find('a'),
            target = a.attr('href');

        others.removeClass('active');
        a.addClass('active');
        $(tabgroup).children('div').hide();
        $(target).show();
        // make sure first unit group is shown
        if (target.match("^#tab")) {
            $(target).find('ul.tabs').eq(0).find('a').eq(0).click();
        }
        // update select if present
        if(select) {
            select.val(target);
        }
    });

    $('.module-group-tabs ul.tabs li:first-child a').click();

    $('select.module-menu').change(function(){
        $('a[href="' + $(this).val() + '"]').click();
    });

    // add on load module click
    var cpdWrapper = $('#cpd-wrapper');
    if (cpdWrapper.data('ref')) {
        console.log('test');
        var moduleLink = $('.tabs a[href="#' + cpdWrapper.data('ref') + '"]'),
            moduleGroupLink = $('a[href="#'  + moduleLink.closest('div.groups-tab-group').attr('id') + '"]'),
            tabContainer = moduleGroupLink.closest('div.tab-container');
        if (tabContainer.length) {
            tabContainer.find('a.jobroleEndorseExpand').click();
            moduleGroupLink.click();
            moduleLink.click();
            $('html, body').animate({
                scrollTop: tabContainer.offset().top - 200
            }, 500, function () {
            });
        }
    }

    $('.module-group-tabs').show();

    $('input[name="fields[userType]"]').change(function(){
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

    // add choices select
    $("form.test").find("ul.choices").each(function(){
      var ul = $(this),
          choices = ul.find('input[type=checkbox]'),
          input = ul.find('input[type=hidden]').eq(0);
        choices.change(function(){
            input.val(ul.find('input[type=checkbox]:checked').map(function(){return $(this).val()}).get().join());
        });
    });

    if ($("#taskbook-payment").length) {
        var tp = $("#taskbook-payment");
        $('body').addClass('loading');
        var loop = 0,
            data = {'packageId': tp.data('id')},
            paymentRedirect = tp.data('redirect'),
            checkPayment = function(){
                if (loop == 5) {
                    alert('Payment not confirmed.  Contact support.');
                    $('body').removeClass('loading');
                    return;
                }
                data[window.csrfTokenName] = window.csrfTokenValue;
                $.post("/sp/packages/payments", data, function(response) {
                    if (!response.success) {
                        alert(response.message);
                        $('body').removeClass('loading');
                        return;
                    }
                    if (response.message == '1') {
                        window.location.replace(paymentRedirect);
                        return;
                    }
                    console.log(response);
                    loop++;
                    setTimeout(function(){checkPayment();}, 2000);
                });
            }
        checkPayment();
    }

    $('a.endorse').each(function(){
        $(this).closest('tr').addClass('endorse');
        var groupId = $(this).closest('.groups-tab-group').attr('id');
        $('a[href="#' + groupId + '"]').closest('li').addClass('endorse');
    });
});
