<?php
/**
 * Magento Core Installer for Composer
 */

namespace Luka\Composer\MagentoCore;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Composer Magento Core Installer
 */
class Installer implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        var_dump($composer);
        var_dump($io);
    }
}
