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
    }
}
```
