# Magento Core Installer

This composer plugin allows to install magento core as downloaded from http://www.magento.com/
All you need to do is adding a composer.json to the zip and add a repository (e.g. artifact, satis, etc ...).

## Example composer.json for magento core packages

```json
{
    "name" : "magento/core",
    "version": "1.8.1.0",
    "description" : "Magento Core",
    "type" : "magento-core",
    "license" : "OSL-3.0",
    "keywords" : [
        "magento"
    ],
    "require" : {
        "luka/magento-core-installer": "~1"
    },
    "extra": {
        "magento-root-dir": "www.production/",
        "magento-separate-writable": true,
        "magento-writable-dir": "writable/",
        "magento-deploystrategy": "copy"
    }
}
```

## Extras

* **magento-root-dir** [string]: Magento installation target path (document root)
* **magento-separate-writable** [bool]: Should use separate writable folder for var and media directories
* **magento-writable-dir** [string]: The directory where to find the var and media directory (symlink target).
