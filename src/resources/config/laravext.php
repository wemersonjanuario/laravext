<?php

return [
    "wkhtmltopdf_bin" => base_path() . '/vendor/bin/wkhtmltopdf.exe.bat',//For Windows
    "default_document_font_size" => 12,
    "default_document_orientation" => 'Portrait',
    "default_template_view" => "vendor/laravext/laravext-report",
    "pdf_stylesheet_url" => "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css",
    "xls_config" => [
        "template_file" => base_path(). '/resources/templates/report.xls',
        "start_row_index" => 6,
        "sheet_title" => 'Your title here'
    ],
    "extjs" => [
        "client_id_property" => "clientId",
        "proxy" => [
            "filter_param" => "filter",
            "limit_param" => "limit",
            "start_param" => "start",
            "page_param" => "page"
        ],

        "sorter" => [
            "sortProperty" => "sort",
            "directionParam" => "dir"
        ],
        "writer" => [
            "rootProperty" => "data"
        ],
        "reader" => [
            "rootProperty" => "data",
            "total_property" => "total",
            "success_property" => "success",
            "message_property" => "message"
        ]
    ]

];