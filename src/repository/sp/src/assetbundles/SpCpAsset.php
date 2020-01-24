<?php
namespace lantra\sp\assetbundles;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class SpCpAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = "@lantra/sp/resources";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/cp.js',
        ];

        parent::init();
    }
}
