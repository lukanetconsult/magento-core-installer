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
    const COMPOSER_TYPE = 'magento-core';

    /**
     * Default Magento installation path.
     *
     * @var string
     */
    protected $magentoRootDir = './';

    /**
     * Magento location in the package.
     *
     * @var string
     */
    protected $magentoLocationInPackage = 'magento';

    /**
     * Default location for Magento writable folders var, media etc.
     * Symlinks will be created.
     *
     * @var string
     */
    protected $magentoWritableDir = 'writable/';

    /**
     * Folders that need to be placed in writable folder.
     *
     * @var array
     */
    protected $writableFolders = array('var', 'media');

    /**
     * Default value. Use this if the 'magento-separate-writable' is not set in composer.json.
     *
     * @var bool
     */
    protected $separateWritable = false;

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
        $this->separateWritable = (isset($extra['magento-separate-writable']))? (bool)$extra['magento-separate-writable'] : false;

        // Override default Magento root folder
        if(isset($extra['magento-root-dir'])) {
            $this->magentoRootDir = $extra['magento-root-dir'];
        }

        // Magento writable folder
        if($this->separateWritable && isset($extra['magento-writable-dir'])) {
            $this->magentoWritableDir = $extra['magento-writable-dir'];
        }
    }

    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);

        if (!file_exists($this->magentoRootDir) && !is_dir($this->magentoRootDir)) {
            mkdir($this->magentoRootDir);
        }

        if ($this->separateWritable) {
            if (!file_exists($this->magentoWritableDir) && !is_dir($this->magentoWritableDir)) {
                mkdir($this->magentoWritableDir);
            }
        }

        $installPath = $this->getInstallPath($package);
        $this->recursiveMove($installPath . '/' . $this->magentoLocationInPackage, $this->magentoRootDir);

        if($this->separateWritable) {
            foreach($this->writableFolders as $writableDir) {
                $this->recursiveMove(rtrim($this->magentoRootDir, '/') . '/' . $writableDir, rtrim($this->magentoWritableDir, '/') . '/' . $writableDir);
                symlink(realpath(rtrim($this->magentoWritableDir, '/') . '/' . $writableDir), rtrim($this->magentoRootDir, '/') . '/' . $writableDir);
            }
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
    protected function recursiveMove($src, $dest) {

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
                rename($f->getRealPath(), $targetPath);
            } else if(!$f->isDot() && $f->isDir()) {
                $this->recursiveMove($f->getRealPath(), $targetPath);
            }
        }

        $this->filesystem->remove($src);
        return true;
    }
}
