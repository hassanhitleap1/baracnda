<?php
return [
    'sourcePath' => '@app', // المسار الأساسي للمصادر
    'destinationPath' => '@webroot/assets', // المكان الذي سيتم وضع الأصول فيه
    'bundles' => [
        'app\assets\AppAsset',
    ],
    'assetManager' => [
        'appendTimestamp' => true,
    ],
];
