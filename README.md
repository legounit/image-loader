# image-loader

## Install 
```
composer require legounit/image-loader "@dev"
```

## Example usage
```php
use \legounit\ImageLoader\ImageLoader;

(new ImageLoader())
  ->loadFrom('http://s.4pda.to/F9drz0IkaUupuz0aGp8g76ecd0fMM6og35nQH9.jpg')
  ->saveTo('temp');
```
