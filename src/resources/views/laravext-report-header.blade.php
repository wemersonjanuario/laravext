<!DOCTYPE HTML >
<html>
<head>
    <script>
        function dynvar() {
            var vars = {};
            var x = document.location.search.substring(1).split('&');

            for (var i in x) {
                var z = x[i].split('=', 2);

                if (!vars[z[0]]) {
                    vars[z[0]] = unescape(z[1]);
                }
            }

            document.getElementById('doctitle').innerHTML = vars.doctitle;
//	    document.getElementById('paginate').innerHTML = 'Page ' + vars.page + ' of ' + vars.topage;
            document.getElementById('paginate').innerHTML = vars.page + '/' + vars.topage;
        }
    </script>
</head>

<body style="margin:0;" onload="dynvar();">
<div style="color:#333;font-family:Lato,sans-serif;font-size:12px;height:45px;position:relative;">
    @if($logoPath)
        <div style="float:left;padding:4px 5px 0 4px;">
            <img alt="app logo" src="{{$logoPath}}" style="width:92px; height: 40px;"/>
        </div>
    @endif
    <div style="float:right;list-style:none;margin:3px 3px 0;text-align:right;">
        <strong id="doctitle">{doctitle}</strong><br/>
        <span id="paginate"
              style="color:#666666;display:inline-block;font-size:11px;font-weight:300;padding-right:1px;">page {page} of {topage}</span>
    </div>
</div>
</body>
</html>
