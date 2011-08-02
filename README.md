# fwLessphpPlugin

## Warning

This plugin is not designed to be used in production environment ! Its aim is to complete raw lessphp renderer like
the swCombinePlugin Lessphp driver.

## Overview

This plugin allows you to use lessphp stylesheets in your symfony application.
Stylesheets are generated on the fly on each page generation.

## How to use

### Step 1 - Install the plugin

Install the plugin via github (recommended), svn or symfony package manager

Enable it in your ProjectConfiguration class :

    class ProjectConfiguration extends sfProjectConfiguration
    {
      public function setup()
      {
        $this->enablePlugins('fwLessphpPlugin');
      }
    }

Publish assets and clear the cache

    $ ./symfony plugin:publish-assets
    $ ./symfony cache:clear

### Install lessphp

This plugin is not bundled with less php, you must install it on your own.

Example :

    $ git clone https://github.com/leafo/lessphp lib/vendor/lessphp

Or :

    $ git submodule add https://github.com/leafo/lessphp lib/vendor/lessphp

For more informations see : http://leafo.net/lessphp/

### Step 3 - Configure the plugin

All the plugin configuration is based on the fw_lessphp.yml file. Put it in you apps/APP/config directory

    all:
      enabled: false                            # Enabled or not
      substitute_helper: 'get_stylesheets'      # Helper used if not enabled

    dev:
      enabled: true
      source:
        pattern: '#(.*)\.less#'                 # Pattern matched by all your less file
        base_path: /data/less                   # The base directory of your less files, relative to sf_root_dir
      destination:
        pattern: '\1.css'                       # The generated css files name pattern
        base_path: /less/cache                  # The base directory of generated file, relative to sf_web_dir

### Step 4 - Final

Use the fw_include_stylesheets() helper in your layout.php

    <head>
      ...
      <?php fw_include_stylesheets() ?>
      ...
    </head>

And that's all.

## TODO

 * Unit tests !
 * Why not make it useable in production (cache implementation and asset combining)