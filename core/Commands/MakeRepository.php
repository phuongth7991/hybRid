<?php

namespace Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Core\AssistCommand;
use Core\CreateFile;

class MakeRepository extends GeneratorCommand
{
    use AssistCommand;

    public $signature = 'make:repository
        {name : The name of the repository }
        {--other : If not put, it will create an eloquent repository}?
        {--admin : Create a admin along with the repository}?';

    public $description = 'Create a new repository class';

    /**
     * Handle the command
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle()
    {
        $name = str_replace(config("core.repository_interface_suffix"), "", $this->argument("name"));
        $name = Str::studly($name);

        $other = $this->option("other");

        $className = Str::studly($name);
        $arr       = explode("/", $className);
        $className = end($arr);

        $this->checkIfRequiredDirectoriesExist($className);

        // First we create the repoisitory interface in the interfaces directory
        // This will be implemented by the interface class
        $this->createRepositoryInterface($className);


        // Second we create the repoisitory directory
        // This will be implement by the interface class
        $this->createRepository($className, !$other);

        if ($this->option('admin')) {
            $this->createAdmin();
        }
    }

    /**
     * Create service for the repository
     *
     * @return void
     */
    private function createAdmin(): void
    {
        $name = str_replace(config("core.repository_interface_suffix"), "", $this->argument("name"));
        $name = Str::studly($name);

        $this->call("make:admin", [
            "name" => $name,
        ]);
    }

    /**
     * Create the repository interface
     *
     * @param string $className
     * @return string
     */
    public function createRepositoryInterface(string $className): string
    {
        $repositoryInterfaceNamespace = config("core.repository_namespace") . "\\" . $className;
        $repositoryInterfaceName      = $className . config("core.repository_interface_suffix");
        $stubProperties               = [
            "{namespace}"               => $repositoryInterfaceNamespace,
            "{repositoryInterfaceName}" => $repositoryInterfaceName,
        ];

        $repositoryInterfacePath = $this->getRepositoryInterfacePath($className);

        new CreateFile(
            $stubProperties,
            $repositoryInterfacePath,
            __DIR__ . "/stubs/repository-interface.stub"
        );

        $this->components->info("<info>Created $className repository interface:</info> " . $repositoryInterfaceName);

        return $repositoryInterfaceNamespace . "\\" . $className;
    }

    /**
     * Create repository
     *
     * @param string $className
     * @param bool $isDefault
     * @return string
     */
    public function createRepository(string $className, $isDefault = true)
    {
        $repositoryNamespace = config("core.repository_namespace") . "\\" . $className;

        $repositoryName = $className . config("core.repository_suffix");
        $stubProperties = [
            "{namespace}"               => $repositoryNamespace,
            "{repositoryName}"          => $repositoryName,
            "{repositoryInterfaceName}" => $className . config("core.repository_interface_suffix"),
            "{ModelName}"               => $className
        ];

        $stubName       = $isDefault ? "repository.stub" : "custom-repository.stub";
        $repositoryPath = $this->getRepositoryPath($className, $isDefault);
        new CreateFile(
            $stubProperties,
            $repositoryPath,
            __DIR__ . "/stubs/$stubName"
        );
        $this->components->info("<info>Created $className repository implement:</info> " . $repositoryName);

        return $repositoryNamespace . "\\" . $className;
    }

    /**
     * Get repository interface namespace
     *
     * @return string
     */
    private function getRepositoryInterfaceNamespace(string $className)
    {
        return config("core.repository_namespace") . "\\" . $className;
    }

    /**
     * Get repository interface path
     *
     * @return string
     */
    private function getRepositoryInterfacePath($className)
    {
        return $this->appPath() . "/" .
            config("core.repository_directory") .
            "/$className/$className" . config("core.repository_interface_suffix") . ".php";
    }

    /**
     * Get repository path
     *
     * @return string
     */
    private function getRepositoryPath($className, $isDefault)
    {
        $path = $isDefault
            ? "/" . $className . "/$className" . config("core.repository_suffix") . ".php"
            : "/Other/$className" . config("core.repository_suffix") . ".php";

        return $this->appPath() . "/" .
            config("core.repository_directory") . $path;
    }

    /**
     * Check to make sure if all required directories are available
     *
     * @param string $className
     * @return void
     * @throws BindingResolutionException
     */
    private function checkIfRequiredDirectoriesExist(string $className): void
    {
        $this->ensureDirectoryExists(config("core.repository_directory"));
        $this->ensureDirectoryExists(config("core.repository_directory") . "/" . $className);
        $this->ensureDirectoryExists(config("core.repository_directory") . "/" . $className);
    }

    protected function getStub()
    {
        // TODO: Implement getStub() method.
    }
}
