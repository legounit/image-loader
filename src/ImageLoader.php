<?php


namespace legounit\ImageLoader;

class ImageLoader implements ImageLoaderInterface
{
    private $image_url = [];
    private $path;
    private static $valid_ext = ['jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];
    private $success_save = 0;

    /**
     * @param $image_url mixed string or array
     * @return $this
     * @throws \Exception
     */
    public function loadFrom($image_url)
    {
        if (is_array($image_url)) {
            $this->image_url = array_merge($this->image_url, $image_url);
            return $this;
        } elseif (is_string($image_url)) {
            $this->image_url[] = $image_url;
            return $this;
        } else {
            throw new \Exception('Invalid link format');
        }
    }

    /**
     * @param $path string path to directory
     * @return int number of images saved
     */
    public function saveTo($path)
    {
        if ($this->checkPath($path)) {
            foreach ($this->image_url as $url) {
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    $this->loadImageAndSave($url);
                }
            }
        }
        return $this->success_save;
    }

    private function loadImageAndSave($url)
    {
        $image = pathinfo($url);
        $ext = null;

        if (array_key_exists('extension', $image)) {
            if (array_key_exists($image['extension'], self::$valid_ext)) { //validation ext
                $ext = $image['extension'];
            }
        } elseif (extension_loaded('gd')) {
            $mime_type = getimagesize($url)['mime'];
            $ext = array_search($mime_type, self::$valid_ext);
        }

        if ($ext) {
            $image_path = $this->path . DIRECTORY_SEPARATOR . $image['filename'] . '.' . $ext;
            if (file_put_contents($image_path, file_get_contents($url)) !== false) {
                $this->success_save++;
            }
        }
    }

    //TODO refactor to other class
    protected function checkPath($path)
    {
        $ds = DIRECTORY_SEPARATOR;
        $this->path = rtrim(strtr($path, '/\\', $ds . $ds), $ds);


        if (is_dir($this->path)) {
            if (is_writeable($this->path)) {
                return true;
            }
        } elseif (mkdir($this->path, 0775)) {
            return true;
        }
        throw new \Exception('Directory not exist or not writable');
    }
}