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

var x, i, j, selElmnt, a, b, c;
/*look for any elements with the class "custom-select":*/
x = document.getElementsByClassName("custom-select");
for (i = 0; i < x.length; i++) {
    selElmnt = x[i].getElementsByTagName("select")[0];
    /*for each element, create a new DIV that will act as the selected item:*/
    a = document.createElement("DIV");
    a.setAttribute("class", "select-selected");
    a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
    x[i].appendChild(a);
    /*for each element, create a new DIV that will contain the option list:*/
    b = document.createElement("DIV");
    b.setAttribute("class", "select-items select-hide");
    for (j = 1; j < selElmnt.length; j++) {
        /*for each option in the original select element,
        create a new DIV that will act as an option item:*/
        c = document.createElement("DIV");
        c.innerHTML = selElmnt.options[j].innerHTML;
        c.addEventListener("click", function(e) {
            /*when an item is clicked, update the original select box,
            and the selected item:*/
            var y, i, k, s, h;
            s = this.parentNode.parentNode.getElementsByTagName("select")[0];
            h = this.parentNode.previousSibling;
            for (i = 0; i < s.length; i++) {
                if (s.options[i].innerHTML == this.innerHTML) {
                    s.selectedIndex = i;
                    h.innerHTML = this.innerHTML;
                    y = this.parentNode.getElementsByClassName("same-as-selected");
                    for (k = 0; k < y.length; k++) {
                        y[k].removeAttribute("class");
                    }
                    this.setAttribute("class", "same-as-selected");
                    break;
                }
            }
            h.click();
        });
        b.appendChild(c);
    }
    x[i].appendChild(b);
    a.addEventListener("click", function(e) {
        /*when the select box is clicked, close any other select boxes,
        and open/close the current select box:*/
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
    });
}
function closeAllSelect(elmnt) {
    /*a function that will close all select boxes in the document,
    except the current select box:*/
    var x, y, i, arrNo = [];
    x = document.getElementsByClassName("select-items");
    y = document.getElementsByClassName("select-selected");
    for (i = 0; i < y.length; i++) {
        if (elmnt == y[i]) {
            arrNo.push(i)
        } else {
            y[i].classList.remove("select-arrow-active");
        }
    }
    for (i = 0; i < x.length; i++) {
        if (arrNo.indexOf(i)) {
            x[i].classList.add("select-hide");
        }
    }
}
/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);
