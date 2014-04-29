<?php
/**
 * Magento Core Installer for Composer
 */

namespace Luka\Composer\MagentoCore;

use Composer\Package\PackageInterface;
use Composer\Package\InstallerInterface;
use Composer\Installer\LibraryInstaller;

class CoreInstaller extends LibraryInstaller implements InstallerInterface
{
    protected $type = 'magento-core';

    /**
     * Initializes Magento Core installer
     *
     * @param \Composer\IO\IOInterface $io
     * @param \Composer\Composer $composer
     * @param string $type
     * @throws \ErrorException
     */
    public function __construct(IOInterface $io, Composer $composer, $type = 'magento-module')
    {
        parent::__construct($io, $composer, $type);

        $extra = $composer->getPackage()->getExtra();

        echo $extra['magento-root-dir'];exit;
    }
}
