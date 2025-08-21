<?php

namespace Core\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Core\AssistCommand;
use Core\CreateFile;

class MakeService extends GeneratorCommand
{
    use AssistCommand;

    public $signature = 'make:service
        {name : The name of the service }';

    public $description = 'Create a new service class';

    /**
     * @throws BindingResolutionException|FileNotFoundException
     */
    public function handle(): void
    {
        $name      = str_replace(config('core.service_interface_suffix'), '', $this->argument('name'));
        $className = Str::studly($name);

        if ($this->checkIfRequiredDirectoriesExist($className)) {
            $this->createServiceInterface($className);
            $this->createService($className);
        } else {
            $this->components->error("The service $className is exist");
        }
    }

    /**
     * Create the admin
     *
     * @param string $className
     * @return void
     * @throws BindingResolutionException
     * @throws FileNotFoundException
     */
    public function createService(string $className): void
    {
        $nameOfService = $this->getServiceName($className);
        $serviceName   = $nameOfService . config('core.service_suffix');

        $namespace      = $this->getNameSpace($className);
        $stubProperties = [
            '{namespace}'                    => $namespace,
            '{serviceName}'                    => $serviceName,
            '{serviceInterface}'               => $nameOfService . config('core.service_interface_suffix'),
            '{repositoryInterfaceName}'      => $this->getRepositoryInterfaceName($nameOfService),
            '{repositoryInterfaceNamespace}' => $this->getRepositoryInterfaceNamespace($nameOfService),
        ];

        $stubPath = __DIR__ . '/stubs/service.stub';

        // create file
        new CreateFile(
            $stubProperties,
            $this->getServicePath($className, $nameOfService),
            $stubPath
        );
        $this->components->info("Created $className admin implement {$serviceName}");
    }

    /**
     * Create the admin interface
     *
     * @param string $className
     * @return void
     */
    public function createServiceInterface(string $className): void
    {
        $nameOfService = $this->getServiceName($className);
        $serviceName   = $nameOfService . config('core.service_interface_suffix');

        $namespace      = $this->getNameSpace($className);
        $stubProperties = [
            '{namespace}'      => $namespace,
            '{serviceInterface}' => $serviceName,
        ];

        // create file
        new CreateFile(
            $stubProperties,
            $this->getServiceInterfacePath($className, $serviceName),
            __DIR__ . '/stubs/service-interface.stub'
        );
        $this->components->info("Created $className service interface: {$serviceName}");
    }

    /**
     * Get admin path
     *
     * @param $className
     * @param $serviceName
     * @return string
     */
    private function getServicePath($className, $serviceName): string
    {
        return $this->appPath() . '/' .
            config('core.service_directory') .
            '/' . $className . '/' . $serviceName . config('core.service_suffix') . '.php';
    }

    /**
     * Get admin interface path
     *
     * @param $className
     * @param $serviceName
     * @return string
     */
    private function getServiceInterfacePath($className, $serviceName): string
    {
        return $this->appPath() . '/' .
            config('core.service_directory') .
            '/' . $className . '/' . $serviceName . '.php';
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
        if (is_dir(config("core.service_directory") . "/" . $className)) {
            return false;
        }
        $this->ensureDirectoryExists(config('core.service_directory'));
        $this->ensureDirectoryExists(config("core.service_directory") . "/" . $className);
        return true;
    }

    /**
     * get admin name
     * @param $className
     * @return string
     */
    private function getServiceName($className): string
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
            return config('core.service_namespace') . $namespace . '\\' . end($explode);
        } else {
            return config('core.service_namespace') . '\\' . $className;
        }
    }

    protected function getStub()
    {
        // TODO: Implement getStub() method.
    }
}
