<?php
session_write_close();
?>
<html>
    <head>
        <script src="/plugin/profile/jquery-3.1.1.min.js"></script>
        <script src="/plugin/profile/plotly-latest.min.js"></script>
    </head>
    <body>
        <div><label>Initial # Cases:</label><input id="cases"/> [cases]</div>
        <div><label>Cases creation rate:</label><input /> [cases/hour]</div>
        <div><label>Delegations rate:</label><input /> [delegations/hour]</div>
        <div id="02bba7e8-e46f-48c9-ae1b-f130e026112f" style="width: 100%; height: 80%;" class="plotly-graph-div"></div>
        <table border="1">
            <tbody id="datatable">
                <tr><th>t</th><th>cases</th><th>search all</th><th>search by process</th></tr>
            </tbody>
        </table>
        <script type="text/javascript">
            var QueryString = function () {
              // This function is anonymous, is executed immediately and
              // the return value is assigned to QueryString!
              var query_string = {};
              var query = window.location.search.substring(1);
              var vars = query.split("&");
              for (var i=0;i<vars.length;i++) {
                var pair = vars[i].split("=");
                    // If first entry with this name
                if (typeof query_string[pair[0]] === "undefined") {
                  query_string[pair[0]] = decodeURIComponent(pair[1]);
                    // If second entry with this name
                } else if (typeof query_string[pair[0]] === "string") {
                  var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
                  query_string[pair[0]] = arr;
                    // If third or later entry with this name
                } else {
                  query_string[pair[0]].push(decodeURIComponent(pair[1]));
                }
              }
              return query_string;
            }();
            (function () {
                window.PLOTLYENV = {'BASE_URL': 'https://plot.ly'};

                var gd = document.getElementById('02bba7e8-e46f-48c9-ae1b-f130e026112f')
                var resizeDebounce = null;

                function resizePlot() {
                    var bb = gd.getBoundingClientRect();
                    Plotly.relayout(gd, {
                        width: bb.width,
                        height: bb.height
                    });
                }


                window.addEventListener('resize', function () {
                    if (resizeDebounce) {
                        window.clearTimeout(resizeDebounce);
                    }
                    resizeDebounce = window.setTimeout(resizePlot, 100);
                });

                var max = 100;
                var WINDOW_SIZE = 60;
                var T0=new Date().getTime()/1000;

                Plotly.plot(gd, {
                    data: [
                        {
                            "autobinx": true, "uid": "444f60", "ysrc": "caleeli:0:44fc7a", "xsrc": "caleeli:0:7db282",
                            "name": "search all [ms]", "transforms": [],
                            "xbins": {"start": -0.5, "end": 5.5, "size": 2},
                            "y": [],
                            "x": [],
                            "autobiny": true, "type": "scatter", "zauto": true, "mode": "lines"
                        },
                        {
                            "name": "search by process [ms]",
                            "xbins": {"start": -0.5, "end": 5.5, "size": 2},
                            "y": [],
                            "x": [],
                            "autobiny": true, "type": "scatter", "zauto": true, "mode": "lines"
                        },
                    ],
                    layout: {"autosize": true, "boxmode": "group", 
                        "yaxis": {"range": [0, max], "type": "linear", "autorange": true, "title": ""},
                        "breakpoints": [],
                        "xaxis": {"range": [1, 4], "type": "linear", "autorange": true, "title": "time"},
                        "hovermode": "closest"
                    },
                    frames: [],
                    config: {}
                });

                setInterval(function () {
                    $.ajax({
                        url: 'history',
                        method: 'GET',
                        dataType: 'json',
                        success: function (value) {
                            var data = document.getElementById('02bba7e8-e46f-48c9-ae1b-f130e026112f').data[0];
                            if($("#cases").val()==='') {
                                $("#cases").val(value.count);
                            }
                            if(data.y.length>=WINDOW_SIZE) data.y.shift();
                            data.y.push(value.search1);
                            if(data.x.length>=WINDOW_SIZE) data.x.shift();
                            var tt = new Date().getTime()/1000;
                            var t=tt-T0;
                            data.x.push(t);

                            data = document.getElementById('02bba7e8-e46f-48c9-ae1b-f130e026112f').data[1];
                            if(data.y.length>=WINDOW_SIZE) data.y.shift();
                            data.y.push(value.search2);
                            if(data.x.length>=WINDOW_SIZE) data.x.shift();
                            data.x.push(t);

                            Plotly.redraw(document.getElementById('02bba7e8-e46f-48c9-ae1b-f130e026112f'));
                            $("#datatable").append("<tr><td>"+t+"</td><td>"+value.count+"</td><td>"+value.search1+"</td><td>"+value.search2+"</td></tr>");
                        }
                    })
                }, (typeof QueryString.t==='undefined'?2000:QueryString.t)*1);

            }());
        </script>
    </body>
</html>
