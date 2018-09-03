$(document).ready(function(){

    // navigation
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
    $('[data-target]').click(function(){
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
            deleteRow = false;

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
            var data = {entryId: $(this).data('id')};
            deleteRow = true;
        }

        if (action == 'lantra/categories/deleteCategory') {
            if ( ! confirm('Are you sure you want to delete this category?')) {
                return false;
            }
            var data = {categoryId: $(this).data('id')};
            deleteRow = true;
        }

        if (action == 'lantra/users/deleteUser') {
            if ( ! confirm('Are you sure you want to delete this user?')) {
                return false;
            }
            var data = {userId: $(this).data('id')};
            deleteRow = true;
        }

        if (action == 'lantra/entries/runReport') {
            if ( ! confirm('Are you sure you want to run this report?')) {
                return false;
            }
            var data = {entryId: $(this).data('id')};
        }

        data[window.csrfTokenName] = window.csrfTokenValue;

        $.post("/actions/" + action, data, function(response) {

            if (response.redirect) {
                window.location.replace(response.redirect);
            }
            else if (response.success) {
                if (deleteRow) {
                   row.fadeOut().remove();
                }
                alert(response.message);
            }
            else {
                if (response.message) {
                    alert(response.message);
                }
                else {
                    console.log(response);
                    alert('Undefined server error, check the logs.');
                }
            }
        });
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
});
