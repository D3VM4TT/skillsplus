$(document).ready(function(){

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
    })

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

        data[window.csrfTokenName] = window.csrfTokenValue;

        $.post("/actions/" + action, data, function(response) {

            if (response.redirect) {
                window.location.replace(response.redirect);
            }
            else if (response.success) {
                if (deleteRow) {
                   row.fadeOut().remove();
                }
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
        window.location.href = '/' + $(this).data('base') + '/' +  $(this).val();
    });
});
