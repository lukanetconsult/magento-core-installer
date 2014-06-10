<?php
/**
 * Magento Core Installer for Composer
 */

namespace Luka\Composer\MagentoCore;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

/**
 * Composer Magento Core Installer
 */
class Installer implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $coreInstaller = new CoreInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($coreInstaller);

        $libInstaller = new LibInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($libInstaller);
    }
}
