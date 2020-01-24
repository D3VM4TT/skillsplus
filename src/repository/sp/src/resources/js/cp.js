$(document).ready(function(){
    $('div#system-name h2').text(Craft.schemeName);
    $('title').html($('title').html().replace('Skills Plus', Craft.schemeName));
});
