<?php
return [
    'active_theme' => isset($_GET['_xtheme']) ? $_GET['_xtheme'] : env('BC_ACTIVE_THEME', defined('BC_INIT_THEME') ? BC_INIT_THEME : 'BC'),
    "media" => [
        "groups" => [
            "default" => [
                "ext" => ["jpg", "jpeg", "png", "gif", "bmp", "docx", "pdf"], // Add "pdf" here
                "mime" => ["image/png", "image/jpeg", "image/gif", "image/bmp", 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf'], // Add 'application/pdf' MIME type if needed
                "max_size" => 20000000, // In Bytes, default is 20MB,
                "max_width" => env('ALLOW_IMAGE_MAX_WIDTH', 4000),
                "max_height" => env('ALLOW_IMAGE_MAX_HEIGHT', 4000)
            ],
            "image" => [
                "ext" => ["jpg", "jpeg", "png", "gif", "bmp","pdf"],
                "mime" => ["image/png", "image/jpeg", "image/gif", "image/bmp"],
                "max_size" => 20000000, // In Bytes, default is 20MB,
                "max_width" => env('ALLOW_IMAGE_MAX_WIDTH', 4000),
                "max_height" => env('ALLOW_IMAGE_MAX_HEIGHT', 4000)
            ],
        ],
        "optimize_image" => env('BC_MEDIA_OPTIMIZE_IMAGE', true),
        "preview_direct" => env("BC_MEDIA_PREVIEW_DIRECT", true)
    ],
    'disable_require_change_pw' => env('DISABLE_REQUIRE_CHANGE_PW', false)
];
