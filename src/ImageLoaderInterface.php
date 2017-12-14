<?php


namespace legounit\ImageLoader;


interface ImageLoaderInterface
{
    public function loadFrom($image_url);

    public function saveTo($path);
}