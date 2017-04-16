$( document ).ready(function() {

    var track = 0;

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
            var option = document.createElement('option');
            option.value = element;
            data_from.appendChild(option);
            var option = document.createElement('option');
            option.value = element;
            data_where.appendChild(option);
        }, this);

    });
});
