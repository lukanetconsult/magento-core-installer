<?php
/**
 * Magento Core Installer for Composer
 */

namespace Luka\Composer\MagentoCore;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\IO\IOInterface;
use Composer\Util\Filesystem;
use Composer\Composer;

class CoreInstaller extends LibraryInstaller
{
    protected $type = 'magento-core';
    protected $magentoRootDir;

    /**
     * Initializes Magento Core installer.
     *
     * @param IOInterface $io
     * @param Composer    $composer
     * @param string      $type
     * @param Filesystem  $filesystem
     */
    public function __construct(IOInterface $io, Composer $composer, $type = 'library', Filesystem $filesystem = null)
    {
        parent::__construct($io, $composer, $type, $filesystem);

        $extra = $composer->getPackage()->getExtra();

        if(isset($extra['magento-root-dir'])) {
            $magentoRootDir = $extra['magento-root-dir'];
            $this->magentoRootDir = $magentoRootDir;
        }
    }

    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
//        parent::install($repo, $package);
//
//        $installPath = $this->getInstallPath($package);

        exit;

        var_dump($installPath);
        var_dump($this->magentoRootDir);
    }

    public function supports($packageType)
    {

        var_dump($packageType === $this->type);exit;
        return $packageType === $this->type;
    }
}
