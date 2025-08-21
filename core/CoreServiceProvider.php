<?php

namespace Core;

use Core\Commands\MakeAdmin;
use Core\Commands\MakeRepository;
use Core\Commands\MakeService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use SplFileInfo;

class CoreServiceProvider extends ServiceProvider
{
    private $files;

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeAdmin::class,
                MakeRepository::class,
                MakeService::class
            ]);
        }
        $this->app->singleton('page', function () {
            return new Page();
        });
        $this->app->singleton('menu', function () {
            return new Menu();
        });

        $this->bindRepositories();
        $this->bindAdmins();
        $this->bindServices();
    }

    /**
     * Loop through the repository interfaces and bind each interface to its
     * Repository inside the implementations
     *
     * @return void
     */
    private function bindServices(): void
    {
        $serviceInterfaces = $this->getServicePath();

        foreach ($serviceInterfaces as $key => $serviceInterface) {
            $serviceInterfaceClass = config("core.service_namespace") . "\\"
                . $serviceInterface . "\\"
                . $serviceInterface
                . config("core.service_interface_suffix");

            $serviceImplementClass = config("core.service_namespace") . "\\"
                . $serviceInterface . "\\"
                . $serviceInterface
                . config("core.service_suffix");
            $this->app->bind($serviceInterfaceClass, $serviceImplementClass);
        }
    }

    /**
     * Get services path
     *
     * @return array
     */
    private function getServicePath(): array
    {
        $folders = [];
        if (file_exists($this->app->basePath() . "/" . config("core.service_directory"))) {
            $dirs = File::directories($this->app->basePath() .
                "/" . config("core.service_directory"));
            foreach ($dirs as $dir) {
                $dir = str_replace('\\', '/', $dir);
                $arr = explode("/", $dir);

                $folders[] = end($arr);
            }
        }

        return $folders;
    }


    private function bindAdmins()
    {
        $adminPath = $this->getAdminPath();
        foreach ($adminPath as $adminName) {
            $splitName = explode("/", $adminName);
            $className = end($splitName);

            $pathService = str_replace("/", "\\", $adminName);

            $adminInterfaceClass = config("core.admin_namespace") . "\\"
                . $pathService . "\\"
                . $className
                . config("core.admin_interface_suffix");

            $adminImplementClass = config("core.admin_namespace") . "\\"
                . $pathService . "\\"
                . $className
                . config("core.admin_suffix");

            $this->app->bind($adminInterfaceClass, $adminImplementClass);
        }
    }

    /**
     * get service path
     * @return array
     * @throws BindingResolutionException
     */
    private function getAdminPath(): array
    {
        $root = $this->app->basePath() .
            "/" . config("core.admin_directory");
        $adminPath = [];
        if (file_exists($root)) {
            $path = $this->searchFile($root);

            foreach ($path as $file) {
                $file_path = strstr($file->getPath(), "Admin");
                $file_path = str_replace('\\', '/', $file_path);
                $adminPath[] = str_replace("Admin/", "", $file_path);
            }
        }

        return array_unique($adminPath);
    }

    /**
     * @throws BindingResolutionException
     */
    private function searchFile($folder, $pattern_array = ["php"]): array
    {
        app()->make(Filesystem::class)->ensureDirectoryExists($folder);

        $return = array();
        $iti = new \RecursiveDirectoryIterator($folder);
        foreach (new \RecursiveIteratorIterator($iti) as $file) {
            $arr = explode('.', $file);
            if (in_array(strtolower(array_pop($arr)), $pattern_array)) {
                $return[] = $file;
            }
        }
        return $return;
    }

    /**
     * Loop through the repository interfaces and bind each interface to its
     * Repository inside the implementations
     *
     * @return void
     */
    private function bindRepositories(): void
    {
        $repositoryInterfaces = $this->getRepositoryPath();

        foreach ($repositoryInterfaces as $key => $repositoryInterface) {
            $repositoryInterfaceClass = config("core.repository_namespace") . "\\"
                . $repositoryInterface . "\\"
                . $repositoryInterface
                . config("core.repository_interface_suffix");

            $repositoryImplementClass = config("core.repository_namespace") . "\\"
                . $repositoryInterface . "\\"
                . $repositoryInterface
                . config("core.repository_suffix");

            $this->app->bind($repositoryInterfaceClass, $repositoryImplementClass);
        }
    }

    /**
     * Check inside the repositories interfaces directory and get all interfaces
     *
     * @return Collection
     */
    public function getRepository(): Collection
    {
        $interfaces = collect([]);
        $directory = $this->getRepositoryPath();
        $files = $this->files->files($directory);
        if (is_array($files)) {
            $interfaces = collect($files)->map(function (SplFileInfo $file) {
                return str_replace(".php", "", $file->getFilename());
            });
        }

        return $interfaces;
    }

    /**
     * Get repositories path
     *
     * @return array
     */
    private function getRepositoryPath(): array
    {
        $folders = [];
        if (file_exists($this->app->basePath() . "/" . config("core.repository_directory"))) {
            $dirs = File::directories($this->app->basePath() .
                "/" . config("core.repository_directory"));
            foreach ($dirs as $dir) {
                $dir = str_replace('\\', '/', $dir);
                $arr = explode("/", $dir);

                $folders[] = end($arr);
            }
        } else {

        }


        return $folders;
    }
}
