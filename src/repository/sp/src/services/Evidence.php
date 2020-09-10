<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\base\Component;
use craft\elements\Asset;
use craft\events\ModelEvent;

use lantra\sp\Plugin as Lantra;

class Evidence extends Component
{
    /**
     * @param ModelEvent $event
     * @param Asset $asset
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function onSaveEvidence(ModelEvent $event, Asset $asset)
    {
        if (isset($asset->location) && $asset->location->coordinates) {
             return;
        }
        if (!$asset->filename) {
            return;
        }
        $volumePath = rtrim(Craft::getAlias($asset->getVolume()->settings['path']), '/') . '/';
        $folderPath = rtrim($asset->getFolder()->path, '/') . '/';
        $assetFilePath = $volumePath . $folderPath . $asset->filename;
        $exifData = Craft::$app->images->getExifData($assetFilePath);
        if (isset($exifData["gps.GPSLatitude"]) && isset($exifData["gps.GPSLongitude"])) {
            $latitude = $this->getGps($exifData["gps.GPSLatitude"], $exifData['gps.GPSLatitudeRef']);
            $longitude = $this->getGps($exifData["gps.GPSLongitude"], $exifData['gps.GPSLongitudeRef']);
            $asset->setFieldValue('location', ['coordinates' => $latitude . ',' . $longitude]);
            Craft::$app->elements->saveElement($asset);
        }
    }

    /**
     * @param $coordinate
     * @param $hemisphere
     * @return float|int
     */
    private function getGps($coordinate, $hemisphere)
    {
        if (is_string($coordinate)) {
            $coordinate = array_map("trim", explode(",", $coordinate));
        }
        for ($i = 0; $i < 3; $i++) {
            $part = explode('/', $coordinate[$i]);
            if (count($part) == 1) {
                $coordinate[$i] = $part[0];
            } else if (count($part) == 2) {
                $coordinate[$i] = floatval($part[0])/floatval($part[1]);
            } else {
                $coordinate[$i] = 0;
            }
        }
        list($degrees, $minutes, $seconds) = $coordinate;
        $sign = ($hemisphere == 'W' || $hemisphere == 'S') ? -1 : 1;
        return $sign * ($degrees + $minutes/60 + $seconds/3600);
    }
}