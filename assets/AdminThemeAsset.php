<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Brand theme layered on top of AdminLTE. Registered after AdminLteAsset
 * so its rules override the vendor styles.
 */
class AdminThemeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/admin-theme.css',
    ];
    public $depends = [
        AdminLteAsset::class,
    ];
}
