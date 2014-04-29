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
class Installer extends LibraryInstaller implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $composer->getInstallationManager()->addInstaller($this);
    }
}
