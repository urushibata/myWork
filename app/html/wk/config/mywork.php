<?php

return [
    'img_url' => env('IMG_URL'),
    'aws' => [
        'default_region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        's3_image_save_path' => [
            'faces' => env('AWS_S3_IMAGE_SAVE_PATH_FACES'),
            'text' => env('AWS_S3_IMAGE_SAVE_PATH_TEXT'),
            'labels' => env('AWS_S3_IMAGE_SAVE_PATH_LABELS'),
        ],
        's3_pdf_save_path' => env('AWS_S3_PDF_SAVE_PATH'),
        'dynamodb_endpoint' => env('DYNAMODB_ENDPOINT'),
        'sns_pdf_sort_topic' => env('AWS_SNS_PDF_SORT_TOPIC'),
        'cognito_app_client_id' => env('AWS_COGNITO_APP_CLIENT_ID'),
        'cognito_user_pool_id' => env('AWS_COGNITO_USER_POOL_ID'),
        'cognito_version' => env('AWS_COGNITO_VERSION'),
    ],
];
