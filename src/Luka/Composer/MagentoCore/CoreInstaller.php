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
    protected $magentoRootDir;

    protected $magentoLocationInPackage = 'magento';

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
        parent::install($repo, $package);

        $installPath = $this->getInstallPath($package);

        $this->recursiveMove($installPath . '/' . $this->magentoLocationInPackage, $this->magentoRootDir);
    }

    public function supports($packageType)
    {
        return $packageType === 'magento-core';
    }

    /**
     * Recursively move files from one directory to another
     *
     * @param String $src - Source of files being moved
     * @param String $dest - Destination of files being moved
     * @return bool
     */
    protected function recursiveMove($src, $dest) {

        // If source is not a directory stop processing
        if(!is_dir($src)) return false;

        // If the destination directory does not exist create it
        if(!is_dir($dest)) {
            if(!mkdir($dest)) {
                // If the destination directory could not be created stop processing
                return false;
            }
        }

        // Open the source directory to read in files
        $i = new \DirectoryIterator($src);
        foreach($i as $f) {
            if($f->isFile()) {
                rename($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if(!$f->isDot() && $f->isDir()) {
                $this->recursiveMove($f->getRealPath(), "$dest/$f");
            }
        }

        unlink($src);

        return true;
    }
}
