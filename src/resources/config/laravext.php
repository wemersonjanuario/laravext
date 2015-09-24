<?php

return [
    "wkhtmltopdf_bin" => base_path() . '/vendor/bin/wkhtmltopdf.exe.bat',//For Windows
    "default_document_name" => "My Report",
    "default_document_font_size" => 12,
    "default_document_orientation" => 'Portrait',
    "default_template_view" => "laravext-report",
    "pdf_generator" => "wkhtmltopdf",
    "boolean_true_text" => "Yes",//Lang
    "boolean_false_text" => "No"//Lang
];