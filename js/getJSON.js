function getJSON(dataString, drawn) {
    var jsonData = $.ajax({
        type: "post",
        url: "php/getDataPlotly.php",
        data: dataString,
        dataType: "json",
        cache: false,
        async: false,
        success: function(html){
            $("#msg").html(html);
        }
    }).responseJSON;
    return jsonData;
}
