<!DOCTYPE HTML >
<html>
<head>
    <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
    <title></title>
    @if($stylesheetUrl)
        <link rel="stylesheet" type="text/css" href="{{$stylesheetUrl}}"/>
    @endif
    <style TYPE="text/css">
        body {
            margin: 0px auto;
        }

        td {
            padding: 0px 5px 0px 5px;
            vertical-align: bottom;
        }
    </style>
</head>
<body>
<p style="text-align: center; font-weight: bold; font-size: 15px;"> {{$documentTitle}}</p>
<table cellspacing="0" style="text-align: center; width: 100%; font-size: {{ $documentFontSize }} px;"
       class="table table-bordered table-striped">
    <thead>
    <tr>
        @foreach ($columns as $column)
            <th style="text-align: {{ $column['align'] }}; width:{{ $column['width']}}px;">{{ $column['text'] }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach ($rows->toArray() as $row)
        <tr>
            @for ($i = 0; $i < count($columns) ; $i++)
                <td style="text-align:{{$columns[$i]['align']}};">
                    @if ($columnsDataType[$columns[$i]['dataIndex']] === 'money')
                        R$ {{ number_format($row[$columns[$i]['dataIndex']], 2, ',', '.') }}
                    @elseif ($columnsDataType[$columns[$i]['dataIndex']] === 'date')
                        {{ Carbon::parse($row[$columns[$i]['dataIndex']])->format('d/m/Y') }}
                    @elseif ($columnsDataType[$columns[$i]['dataIndex']] === 'datetime')
                        {{ Carbon::parse($row[$columns[$i]['dataIndex']])->format('d/m/Y H:i') }}
                    @elseif ($columnsDataType[$columns[$i]['dataIndex']] === 'boolean')
                        {{ $row[$columns[$i]['dataIndex']] == 1 ? trans("laravext.true") : trans("laravext.false")}}
                    @else
                        {{$row[$columns[$i]['dataIndex']]}}
                    @endif
                </td>
            @endfor

        </tr>
    @endforeach
    </tbody>
</table>
</body>

</html>