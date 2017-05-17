$( document ).ready(function() {

    var track = 0;
    var counter = 0;

    $("#input_track").on('change', function() {
        track = document.getElementById('input_track').value;
        var q = "";
        var dataString = 'q='+q+'&track='+track;
        var jsonData = $.ajax({
                    type: "post",
                    url: "php/whisperer.php",
                    data: dataString,
                    dataType: "text",
                    cache: false,
                    async: false,
                }).responseText;
        jsonData = JSON.parse(jsonData);

        var data_from = document.getElementById('stations_from');
        var data_where = document.getElementById('stations_where');

        jsonData.forEach(function(element) {
            counter++;
            var option = document.createElement('option');
            option.value = element;
            if (counter == 1){
                option.id = "first";
            }
            data_from.appendChild(option);
            var option = document.createElement('option');
            option.value = element;
            if (counter == jsonData.length){
                option.id = "last";
            }
            data_where.appendChild(option);
        }, this);

        document.getElementById('input_from').placeholder = document.getElementById('first').value;
        document.getElementById('input_where').placeholder = document.getElementById('last').value;

    });
});
