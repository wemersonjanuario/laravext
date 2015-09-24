<!DOCTYPE HTML >
<html>
<head>
    <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
    <title></title>

    {{--<link rel="stylesheet" type="text/css" href="{{URL::to('/resources/css/bootstrapDomPdf.css')}}"/>--}}
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
            <th style="text-align: {{ $column['align'] }}">{{ $column['text'] }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach ($rows->toArray() as $row)
        <tr>
            @for ($i = 0; $i < count($columns) ; $i++)
                @if ($columnsDataType[$columns[$i]['dataIndex']] === 'money')
                    <td style="text-align:right;">
                        R$ {{ number_format($row[$columns[$i]['dataIndex']], 2, ',', '.') }}
                    </td>
                @elseif ($columnsDataType[$columns[$i]['dataIndex']] === 'date')
                    <td style="text-align:center;">
                        {{ Carbon::parse($row[$columns[$i]['dataIndex']])->format('d/m/Y') }}
                    </td>
                @elseif ($columnsDataType[$columns[$i]['dataIndex']] === 'datetime')
                    <td style="text-align:center;">
                        {{ Carbon::parse($row[$columns[$i]['dataIndex']])->format('d/m/Y H:i') }}
                    </td>
                @elseif ($columnsDataType[$columns[$i]['dataIndex']] === 'boolean')
                    <td style="text-align:center;">
                        {{ $row[$columns[$i]['dataIndex']] == 1 ? 'Sim' : 'N&atilde;o'}}
                    </td>
                @else
                    <td style="text-align:left;">
                        {{$row[$columns[$i]['dataIndex']]}}
                    </td>
                @endif
            @endfor

        </tr>
    @endforeach
    </tbody>
</table>
</body>

</html>