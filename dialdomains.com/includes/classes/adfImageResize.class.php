<?php
class adfImageResize {
    private $_quality = 90;

    public function process_resize($max_height, $max_width, $src_image, $dst_image) {
        try {
            # make sure the src_image is there & readable
            if (!file_exists($src_image) || !is_readable($src_image)) {
                throw new Exception("The image '$src_image' does not exist.");
            }

            # get the current height & width
            $cur_size = $this->get_image_size($src_image);

            if (!is_array($cur_size)) {
                throw new Exception("Could not retrieve the height & width of '$src_image'.");
            }

            # pull the height and width out of the array
            list($cur_width, $cur_height) = $cur_size;

            # if the image is smaller than the maximum height & width we leave the values
            # alone unless $allow_enlarge is true
            if ($cur_width < $max_width && $cur_height < $max_height) {
                $new_width  = $cur_width;
                $new_height = $cur_height;
            } else {
                # calculate new dimensions
                list($new_width, $new_height) = $this->calculate_new_size($cur_height, $cur_width, $max_height, $max_width);
            }

            if (!$this->resample($cur_width, $cur_height, $new_width, $new_height, $src_image, $dst_image)) {
                throw new Exception("An error occurred trying to resample the image.");
            }

            return array($new_width, $new_height);
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
            return false;
        }
    }

    public function resample($cur_width, $cur_height, $new_width, $new_height, $src_image, $dst_image) {
        # create a blank true color image..
        $new_image = imagecreatetruecolor($new_width, $new_height);

        # create a new image based on the uploaded image..
        if (!$old_image = $this->new_image_by_type($src_image)) {
            return false;
        }

        # resample and resize the image..
        if (!imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $cur_width, $cur_height)) {
            return false;
        }

        # save image as a jpg and optimize the file size..
        return imagejpeg($new_image, $dst_image, 100);
    }

    public function new_image_by_type($src_image) {
    	extract(pathinfo(strtolower($src_image)));

        switch($extension) {
            case "jpg":
                return imagecreatefromjpeg($src_image);
                break;
            case "jpeg":
                return imagecreatefromjpeg($src_image);
                break;
            case "gif":
            	return imagecreatefromgif($src_image);
                break;
            case "png":
                return imagecreatefrompng($src_image);
                break;
            default:
                return false;
        }
    }

    public function calculate_new_size($cur_height, $cur_width, $max_height, $max_width) {
        # initial resize
        if ($cur_width > $cur_height) {
            $new_width  = $max_width;
            $new_height = floor(($new_width * $cur_height) / $cur_width);
        } else {
            $new_height = $max_height;
            $new_width  = floor(($new_height * $cur_width) / $cur_height);
        }

        # secondary resize
        if ($new_width > $max_width) {
            $new_width  = $max_width;
            $new_height = floor(($new_width * $cur_height) / $cur_width);
        }

        if ($new_height > $max_height) {
            $new_height = $max_height;
            $new_width  = floor(($new_height * $cur_width) / $cur_height);
        }

        return array($new_width, $new_height);
    }

    public function get_image_size($src_image) {
        return getimagesize($src_image);
    }
}