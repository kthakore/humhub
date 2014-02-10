<?php

/**
 * HumHub
 * Copyright © 2014 The HumHub Project
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 */

/**
 * ProfileImage is responsible for all profile images.
 *
 * This class handles all tasks related to profile images.
 * Will used for Space or User Profiles.
 *
 * Prefixes:
 *  "" = Resized profile image
 *  "_org" = Orginal uploaded file
 *
 * @package humhub.libs
 * @since 0.5
 * @author Luke
 */
class ProfileImage {

    /**
     * @var String is the guid of user or space
     */
    protected $guid = "";

    /**
     * @var Integer width of the Image
     */
    protected $width = 150;

    /**
     * @var Integer height of the Image
     */
    protected $height = 150;

    /**
     * @var String folder name inside the uploads directory
     */
    protected $folder_images = "profile_image";

    /**
     * @var String name of the default image
     */
    protected $defaultImage;

    /**
     * Constructor of Profile Image
     *
     * UserId is optional, if not given the current user will used
     *
     * @param type $guid
     */
    public function __construct($guid, $defaultImage = 'default') {
        $this->guid = $guid;
        $this->defaultImage = $defaultImage;
    }

    /**
     * Returns the URl of the Modified Profile Image
     *
     * @param String $prefix Prefix of the returned image
     * @return String Url of the profile image
     */
    public function getUrl($prefix = "") {

        $cacheId = 0;
        $path = "";

        // Workaround for absolute urls in console applications (Cron)
        if (Yii::app() instanceOf CConsoleApplication) {
            $path = Yii::app()->request->getBaseUrl();
        } else {
            $path = Yii::app()->getBaseUrl(true);
        }

        $path .= '/uploads/' . $this->folder_images . '/';

        $fileName = $this->getPath($prefix);

        if (file_exists($fileName)) {
            #$cacheId = filemtime($fileName);
            $path .= $this->guid . $prefix;
        } else {
            $path .= $this->defaultImage;
        }
        $path .= '.jpg';

        $path .= '?cacheId=' . $cacheId;
        return $path;
    }

    /**
     * Indicates there is a custom profile image
     *
     * @return Boolean is there a profile image
     */
    public function hasImage() {
        return file_exists($this->getPath("_org"));
    }

    /**
     * Returns the Path of the Modified Profile Image
     *
     * @param String $prefix for the profile image
     * @return String Path to the profile image
     */
    public function getPath($prefix = "") {
        $path = Yii::app()->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $this->folder_images . DIRECTORY_SEPARATOR;

        if (!is_dir($path))
            mkdir($path);

        $path .= $this->guid;
        $path .= $prefix;
        $path .= ".jpg";

        return $path;
    }

    /**
     * Crops the Original Image
     *
     * @param Int $x
     * @param Int $y
     * @param Int $h
     * @param Int $w
     * @return boolean indicates the success
     */
    public function cropOriginal($x, $y, $h, $w) {

        $image = imagecreatefromjpeg($this->getPath('_org'));

        // Create new destination Image
        $destImage = imagecreatetruecolor($this->width, $this->height);

        if (!imagecopyresampled($destImage, $image, 0, 0, $x, $y, $this->width, $this->height, $w, $h)) {
            return false;
        }

        unlink($this->getPath(''));
        imagejpeg($destImage, $this->getPath(''), 100);
    }

    /**
     * Sets a new profile image by given temp file
     *
     * @param String $tempFile
     */
    public function setNew($tempFile) {

        $convertCommand = HSetting::Get('imageMagickPath', 'file');

        $this->delete();

        // Transform Orginal to Jpeg
        $command = $convertCommand . " {$tempFile} {$this->getPath('_org')}";
        $ret = passthru($command);


        // Copy and Transform to $prefix_modified
        $command = $convertCommand . " {$this->getPath('_org')} -gravity center -quality 100 -resize {$this->width}x{$this->height}^ -extent {$this->width}x{$this->height}  {$this->getPath('')}";
        $ret = passthru($command);
    }

    /**
     * Deletes current profile
     */
    public function delete() {
        @unlink($this->getPath());
        @unlink($this->getPath('_org'));
    }

}

?>
