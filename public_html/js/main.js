$(document).ready(function(){

    $('#search').click(search);

    search();


    function search() {
        var phrase = $('#phrase').val().match(/([a-zA-Z0-9\s]+)\b\s(\&|at)\s\b([a-zA-Z0-9\s]+),?\s([a-zA-Z\s]+),?\s?([a-zA-Z]{2,})?/);
        var url = '/answer-sms.php/stops/' + phrase[1] + '/' + phrase[3];

        if(phrase[4] && typeof phrase[4] == 'string') {
            url = url + '/' + phrase[4];
        }

        if(phrase[4] && phrase[5] && typeof phrase[5] == 'string') {
            url = url + '/' + phrase[5];
        }

        if(typeof map.removeAllShapes == 'function') {
            map.removeAllShapes();
        }

        $.ajax({
            type: 'GET',
            url: encodeURI(url),
            dataType: 'json',
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                alert(textStatus);
            },
            success: function(data) {
                console.log(data);
                centerMap(data.source.lat, data.source.lng);
                if(data.locs.length == 0) {
                    alert('Nothing was found');
                    return;
                }
                addPoints(data.locs);

            }
        })
    }

    function centerMap(lat, lng) {
        map.setCenter({lat:lat, lng:lng});
        var source=new MQA.Poi( {lat:lat, lng:lng} );
        source.setRolloverContent('Found intersection.');
        map.addShape(source);
        map.setZoomLevel(19);
    }

    function addPoints(points) {
        for(var i in points) {
            var point=new MQA.Poi( {lat:points[i].stop_lat, lng:points[i].stop_lon} );
            point.setRolloverContent('Stop ID: ' + points[i].stop_id);
            var html = "<dl><dt>Stop ID:</dt><dd>" + points[i].stop_id + "</dd><dt>Name:</dt><dd>" + points[i].stop_name + "</dd><dt>Distance:</dt><dd>" + points[i].distance + "</dd><dt>Bearing:</dt><dd>" + points[i].bearing + "</dd></dl>"
            point.setInfoContentHTML(html);
            map.addShape(point);
        }
    }

});

MQA.EventUtil.observe(window, 'load', function() {

    /*Create an object for options*/
    var options={
        elt:document.getElementById('map'),       /*ID of element on the page where you want the map added*/
        zoom:10,                                  /*initial zoom level of the map*/
        latLng:{lat: 25.774117, lng: -80.193593},  /*center of map in latitude/longitude */
        mtype:'map',                              /*map type (map)*/
        bestFitMargin:0,                          /*margin offset from the map viewport when applying a bestfit on shapes*/
        zoomOnDoubleClick:true                    /*zoom in when double-clicking on map*/
    };

    /*Construct an instance of MQA.TileMap with the options object*/
    window.map = new MQA.TileMap(options);

    MQA.withModule('smallzoom', function() {

        map.addControl(
            new MQA.SmallZoom(),
            new MQA.MapCornerPlacement(MQA.MapCorner.TOP_LEFT, new MQA.Size(5,5))
        );

    });
});
