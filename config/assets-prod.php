<?php
return [
    'sourcePath' => '@app',
    'destinationPath' => '@webroot/assets',
    'bundles' => [
        'app\assets\AppAsset',
    ],
    'assetManager' => [
        'appendTimestamp' => true,
        'forceCopy' => true,
    ],
];
