$(function() {
    $("#input_from,#input_where").on('keydown', function(event){
        var my_id = event.target.id;
        $(this).autocomplete({
            source: function(request, response){
                var q = document.getElementById(my_id).value;
                var dataString = 'q='+q;
                var jsonData = $.ajax({
                    type: "post",
                    url: "php/whisperer.php",
                    data: dataString,
                    dataType: "text",
                    cache: false,
                    async: false,
                }).responseText;

                jsonData = JSON.parse(jsonData);
                response(jsonData);
            },
        });
    });
});