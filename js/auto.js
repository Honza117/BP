$(function() {
    $("#input_from,#input_where,#input_track").on('keydown', function(event){
        var my_id = event.target.id;
        var t = "no"; //Pokud se hleda trat, nebo stanice
        var track = document.getElementById('input_track').value;

        console.log(track);

        if (my_id == "input_track"){
          t = "yes"
        }

        $(this).autocomplete({
            source: function(request, response){
                var q = document.getElementById(my_id).value;
                var dataString = 'q='+q+'&t='+t+'&track='+track;
                console.log(dataString);
                var jsonData = $.ajax({
                    type: "post",
                    url: "php/whisperer.php",
                    data: dataString,
                    dataType: "text",
                    cache: false,
                    async: false,
                }).responseText;

                jsonData = JSON.parse(jsonData);
                console.log(jsonData);
                response(jsonData);
            },
        });
    });
});
