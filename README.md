<p align="center">
    <img width="200" alt="Laravel Logo" src="https://raw.githubusercontent.com/webtimal/vite-php/main/vite-php.svg">
</p>

<p align="center">
    <a href="https://php.net"><img alt="PHP 8.2+" src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php"></a>
</p>

## About

**vite-php** is a lightweight library for integrating Vite built assets with any PHP application.

By parsing Vite’s `manifest.json`, it resolves asset URLs and generates the required `<script>` and `<link>` tags.

HMR is supported through automatic `hot` file detection, enabling a seamless development workflow.

## Installation
```bash
composer require webtimal/vite-php
```

## Usage

### 1. Initialize a new Vite instance
```php
<?php

    use Webtimal\Vite\Vite;
    
    $vite = new Vite(

        // Path to the manifest.json file
        manifest: __DIR__ .'/dist/manifest.json',
        
        // Public base path or URL
        base: '/dist',
        
        // Optional path to the hot file, containting the dev server URL
        hotfile: __DIR__ .'/hot'
        
    );
```

### 2. Use it to render the HTML tags
```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $vite(['css/style.css', 'js/script.js']); ?>
        ...
```


## License
This library is open-sourced software licensed under the [MIT License](https://opensource.org/license/MIT).