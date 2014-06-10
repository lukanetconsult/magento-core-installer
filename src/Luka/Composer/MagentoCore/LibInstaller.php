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

class LibInstaller extends LibraryInstaller
{
    const COMPOSER_TYPE = 'library';

    /**
     * Default Magento installation path.
     *
     * @var string
     */
    protected $magentoRootDir = './';

    /**
     * Default Magento lib directory.
     *
     * @var string
     */
    protected $magentoLibDir = 'lib';

    /**
     * Default Magento deploy strategy.
     *
     * @var string
     */
    protected $deployStrategy = 'symlink';

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

        $this->_io = $io;

        // Override default Magento root folder
        if(isset($extra['magento-root-dir'])) {
            $this->magentoRootDir = $extra['magento-root-dir'];
        }

        // Override default Magento deploy strategy
        if(isset($extra['magento-deploystrategy'])) {
            $this->deployStrategy = $extra['magento-deploystrategy'];
        }
    }

    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);

        $sourceDir = realpath($this->getInstallPath($package));

        $targetDir = rtrim($this->magentoRootDir, '/') . '/' . trim($this->magentoLibDir, '/') . '/' . $package->getName();
        if (!file_exists(dirname($targetDir)) && !is_dir(dirname($targetDir))) {
            mkdir(dirname($targetDir));
        }

        switch ($this->deployStrategy) {
            case 'none':
                break;
            case 'copy':
                $this->recursiveCopy($sourceDir, $targetDir);
                break;
            case 'symlink':
            case 'link':
            default:
                symlink($sourceDir, $targetDir);
                ;
        }
    }

    public function supports($packageType)
    {
        return $packageType === self::COMPOSER_TYPE;
    }

    /**
     * Do nothing
     *
     * @param InstalledRepositoryInterface $repo
     * @param PackageInterface $package
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {

    }

    /**
     * Do nothing
     *
     * @param InstalledRepositoryInterface $repo
     * @param PackageInterface $initial
     * @param PackageInterface $target
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {

    }

    /**
     * Recursively move files from one directory to another
     *
     * @param String $src - Source of files being moved
     * @param String $dest - Destination of files being moved
     * @return bool
     */
    protected function recursiveCopy($src, $dest) {

        // If source is not a directory stop processing
        if(!is_dir($src)) {
            return false;
        }

        // If the destination directory does not exist create it
        if(!is_dir($dest) && !mkdir($dest)) {
            // If the destination directory could not be created stop processing
            return false;
        }

        // Open the source directory to read in files
        $i = new \DirectoryIterator($src);
        foreach($i as $f) {
            $targetPath = rtrim($dest, '/') . "/" . $f->getFilename();

            if($f->isFile() && !file_exists($targetPath)) {
                copy($f->getRealPath(), $targetPath);
            } else if(!$f->isDot() && $f->isDir()) {
                $this->recursiveCopy($f->getRealPath(), $targetPath);
            }
        }

        return true;
    }
}
