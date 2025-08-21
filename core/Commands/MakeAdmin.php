<?php

namespace Core\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Core\AssistCommand;
use Core\CreateFile;

class MakeAdmin extends GeneratorCommand
{
    use AssistCommand;

    public $signature = 'make:admin
        {name : The name of the admin }
        {--repository : Create a repository along with the admin}?';

    public $description = 'Create a new admin class';

    /**
     * @throws BindingResolutionException|FileNotFoundException
     */
    public function handle(): void
    {
        $name      = str_replace(config('core.admin_interface_suffix'), '', $this->argument('name'));
        $className = Str::studly($name);

        if ($this->checkIfRequiredDirectoriesExist($className)) {
            $this->createAdminInterface($className);

            $this->createAdmin($className);

            $this->createDataTable($className);

            if ($this->option('repository')) {
                $this->createRepository();
            }

            $this->createForm($className);
        } else {
            $this->components->error("The admin $className is exist");
        }
    }

    /**
     * Create the datatable
     *
     * @param string $className
     * @return void
     * @throws BindingResolutionException
     * @throws FileNotFoundException
     */
    public function createDataTable(string $className): void
    {
        $nameOfAdmin = $this->getAdminName($className);
        $adminName   = $nameOfAdmin . config('core.admin_suffix');

        $namespace      = $this->getNameSpace($className);
        $stubProperties = [
            '{namespace}'                    => $namespace,
            '{adminName}'                    => $adminName
        ];

        $stubPath = __DIR__ . '/stubs/datatable.stub';

        // create file
        new CreateFile(
            $stubProperties,
            $this->getDatatablePath($className, $nameOfAdmin),
            $stubPath
        );
        $this->components->info("Created $className data table: {$adminName}");
    }

    public function createForm($className)
    {
        $nameOfAdmin = $this->getAdminName($className);
        $adminName   = $nameOfAdmin . config('core.admin_suffix');

        $namespace      = $this->getNameSpace($className);
        $stubProperties = [
            '{namespace}'                    => $namespace,
            '{adminName}'                    => $adminName
        ];

        $stubPath = __DIR__ . '/stubs/form.stub';

        // create file
        new CreateFile(
            $stubProperties,
            $this->getFormPath($className, $nameOfAdmin),
            $stubPath
        );
        $this->components->info("Created $className form: {$adminName}Form");
    }

    /**
     * Create the admin
     *
     * @param string $className
     * @return void
     * @throws BindingResolutionException
     * @throws FileNotFoundException
     */
    public function createAdmin(string $className): void
    {
        $nameOfAdmin = $this->getAdminName($className);
        $adminName   = $nameOfAdmin . config('core.admin_suffix');

        $namespace      = $this->getNameSpace($className);
        $stubProperties = [
            '{namespace}'                    => $namespace,
            '{adminName}'                    => $adminName,
            '{adminRouter}'                  => strtolower($adminName),
            '{adminInterface}'               => $nameOfAdmin . config('core.admin_interface_suffix'),
            '{repositoryInterfaceName}'      => $this->getRepositoryInterfaceName($nameOfAdmin),
            '{repositoryInterfaceNamespace}' => $this->getRepositoryInterfaceNamespace($nameOfAdmin),
        ];

        $stubPath = __DIR__ . '/stubs/admin.stub';

        // create file
        new CreateFile(
            $stubProperties,
            $this->getAdminPath($className, $nameOfAdmin),
            $stubPath
        );
        $this->components->info("Created $className admin implement {$adminName}");
    }

    /**
     * Create the admin interface
     *
     * @param string $className
     * @return void
     */
    public function createAdminInterface(string $className): void
    {
        $nameOfAdmin = $this->getAdminName($className);
        $adminName   = $nameOfAdmin . config('core.admin_interface_suffix');

        $namespace      = $this->getNameSpace($className);
        $stubProperties = [
            '{namespace}'      => $namespace,
            '{adminInterface}' => $adminName,
        ];

        // create file
        new CreateFile(
            $stubProperties,
            $this->getAdminInterfacePath($className, $adminName),
            __DIR__ . '/stubs/admin-interface.stub'
        );
        $this->components->info("Created $className admin interface: {$adminName}");
    }

    /**
     * Get admin path
     *
     * @param $className
     * @param $adminName
     * @return string
     */
    private function getAdminPath($className, $adminName): string
    {
        return $this->appPath() . '/' .
            config('core.admin_directory') .
            '/' . $className . '/' . $adminName . config('core.admin_suffix') . '.php';
    }

    /**
     * Get admin path
     *
     * @param $className
     * @param $adminName
     * @return string
     */
    private function getFormPath($className, $adminName): string
    {
        return $this->appPath() . '/' .
            config('core.admin_directory') .
            '/' . $className . '/' . $adminName . config('core.form_suffix') . '.php';
    }

    /**
     * Get admin path
     *
     * @param $className
     * @param $adminName
     * @return string
     */
    private function getDatatablePath($className, $adminName): string
    {
        return $this->appPath() . '/' .
            config('core.admin_directory') .
            '/' . $className . '/' . $adminName . config('core.datatable_suffix') . '.php';
    }

    /**
     * Get admin interface path
     *
     * @param $className
     * @param $adminName
     * @return string
     */
    private function getAdminInterfacePath($className, $adminName): string
    {
        return $this->appPath() . '/' .
            config('core.admin_directory') .
            '/' . $className . '/' . $adminName . '.php';
    }

    /**
     * Get repository interface namespace
     *
     * @param string $className
     * @return string
     */
    private function getRepositoryInterfaceNamespace(string $className): string
    {
        return config('core.repository_namespace') . '\\' . $className;
    }

    /**
     * Get repository interface name
     *
     * @param string $className
     * @return string
     */
    private function getRepositoryInterfaceName(string $className): string
    {
        return $className . config('core.repository_interface_suffix');
    }

    /**
     * get repository name
     * @param string $className
     * @return string
     */
    private function getRepositoryName(string $className): string
    {
        return $className . config('core.repository_suffix');
    }

    /**
     * Check to make sure if all required directories are available
     *
     * @return void
     * @throws BindingResolutionException
     */
    private function checkIfRequiredDirectoriesExist(string $className): bool
    {
        if (is_dir(config("core.admin_directory") . "/" . $className)) {
            return false;
        }
        $this->ensureDirectoryExists(config('core.admin_directory'));
        $this->ensureDirectoryExists(config("core.admin_directory") . "/" . $className);
        return true;
    }

    /**
     * get admin name
     * @param $className
     * @return string
     */
    private function getAdminName($className): string
    {
        $explode = explode('/', $className);
        return $explode[array_key_last($explode)];
    }

    /**
     * get namespace
     * @param $className
     * @return string
     */
    protected function getNameSpace($className): string
    {
        $explode = explode('/', $className);
        if (count($explode) > 1) {
            $namespace = '';
            for ($i = 0; $i < count($explode) - 1; $i++) {
                $namespace .= '\\' . $explode[$i];
            }
            return config('core.admin_namespace') . $namespace . '\\' . end($explode);
        } else {
            return config('core.admin_namespace') . '\\' . $className;
        }
    }

    /**
     * Create repository for the admin
     *
     * @return void
     */
    private function createRepository(): void
    {
        $name = str_replace(config('core.admin_interface_suffix'), '', $this->argument('name'));
        $name = Str::studly($name);

        $this->call('make:repository', [
            'name' => $name,
        ]);
    }

    protected function getStub()
    {
        // TODO: Implement getStub() method.
    }
}
