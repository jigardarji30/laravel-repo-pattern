<?php

namespace Jigard\LaravelRepoPattern\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepoPattern extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
    make:repo_structure {name}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate repository structure';
    protected $softDelete = true;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->init();
        $this->name = $this->argument('name');
        $this->fs = new Filesystem();

        $this->httpResource();
        $this->rootRepoFolder($this->name);
        $this->rootServiceFolder($this->name);

        $this->line("");
        $this->info("Repo Pattern Generated successfully.");
    }

    public function httpResource()
    {
        if (!file_exists(app_path('Http/Resources'))) {
            File::makeDirectory(app_path('Http/Resources'));
            $pathProviders = $this->generateFile('Http/Resources/BaseResource.php', $this->httpResourceFile());
            $this->line("Created File: Http/Resources/BaseResource.php");
        } else {
            if (!file_exists(app_path('Http/Resources/BaseResource.php'))) {
                $pathProviders = $this->generateFile('Http/Resources/BaseResource.php', $this->httpResourceFile());
                $this->line("Created File: Http/Resources/BaseResource.php");
            }
        }
    }

    public function rootServiceFolder($name)
    {
        if (!file_exists(app_path('Services'))) {
            File::makeDirectory(app_path('Services'));
            if (!file_exists(app_path('Services') . $name)) {
                File::makeDirectory(app_path('Services/') . $name);
                if (!file_exists(app_path('Services') . $name . '/Providers')) {
                    File::makeDirectory(app_path('Services/') . $name . '/Providers');
                    $pathProviders = $this->generateFile('Services/' . $name . '/Providers/' . $name . 'ServicesProvider.php', $this->servicesProviderFile());
                    $file = $this->replaceTextFile($pathProviders, $name);
                    $this->line("Created File: " . 'Services/' . $name . '/Providers/' . $name . 'ServicesProvider.php');
                }
                $pathServices = $this->generateFile('Services/' . $name . '/' . $name . 'Services.php', $this->servicesFile());
                $file = $this->replaceTextFileAgain($pathServices, lcfirst($name));
                $file = $this->replaceTextFile($pathServices, $name);
                $this->line("Created File: " . 'Services/' . $name . '/' . $name . 'Services.php');
            }
            // echo 'Not exist';
            return true;
        } else {
            if (!file_exists(app_path('Services') . $name)) {
                File::makeDirectory(app_path('Services/') . $name);
                if (!file_exists(app_path('Services') . $name . '/Providers')) {
                    File::makeDirectory(app_path('Services/') . $name . '/Providers');
                    $pathProviders = $this->generateFile('Services/' . $name . '/Providers/' . $name . 'ServicesProvider.php', $this->servicesProviderFile());
                    $file = $this->replaceTextFile($pathProviders, $name);
                    $this->line("Created File: " . 'Services/' . $name . '/Providers/' . $name . 'ServicesProvider.php');
                }
                $pathServices = $this->generateFile('Services/' . $name . '/' . $name . 'Services.php', $this->servicesFile());
                $file = $this->replaceTextFileAgain($pathServices, lcfirst($name));
                $file = $this->replaceTextFile($pathServices, $name);
                $this->line("Created File: " . 'Services/' . $name . '/' . $name . 'Services.php');
            }
        }
        return true;
    }

    public function rootRepoFolder($name)
    {
        if (!file_exists(app_path('Repositories'))) {
            File::makeDirectory(app_path('Repositories'));
            if (!file_exists(app_path('Repositories') . $name)) {
                File::makeDirectory(app_path('Repositories/') . $name);
                $pathInterface = $this->generateFile('Repositories/' . $name . '/' . $name . 'Interface.php', $this->interfaceFile());
                $file = $this->replaceTextFile($pathInterface, $name);
                $this->line("Created File: " . 'Repositories/' . $name . '/' . $name . 'Interface.php');
                $pathRepository = $this->generateFile('Repositories/' . $name . '/' . $name . 'Repository.php', $this->repositoryFile());
                $file = $this->replaceTextFile($pathRepository, $name);
                $this->line("Created File: " . 'Repositories/' . $name . '/' . $name . 'Repository.php');
            }
            $this->rootRepoProviderGenerate($name);
            // echo 'Not exist';
            return true;
        } else {
            if (!file_exists(app_path('Repositories') . $name)) {
                File::makeDirectory(app_path('Repositories/') . $name);
                $pathInterface = $this->generateFile('Repositories/' . $name . '/' . $name . 'Interface.php', $this->interfaceFile());
                $file = $this->replaceTextFile($pathInterface, $name);
                $this->line("Created File: " . 'Repositories/' . $name . '/' . $name . 'Interface.php');

                $pathRepository = $this->generateFile('Repositories/' . $name . '/' . $name . 'Repository.php', $this->repositoryFile());
                $file = $this->replaceTextFile($pathRepository, $name);
                $this->line("Created File: " . 'Repositories/' . $name . '/' . $name . 'Repository.php');
            }
        }
    }

    protected function getStubContent($path)
    {
        if ($this->fs->exists(resource_path('crud-stubs/' .  $path . '.stub'))) {
            return $this->fs->get(resource_path('crud-stubs/' .  $path . '.stub'));
        } else {
            return $this->fs->get(__DIR__ . '/../../stubs/' . $path . '.stub');
        }
    }

    public function httpResourceFile()
    {
        return $this->getStubContent("BaseResource.php");
        // return File::get(app_path('Console/Commands/BaseResource.php'));
    }

    public function servicesProviderFile()
    {
        return $this->getStubContent("NameHereServicesProvider.php");
        // return File::get(app_path('Console/Commands/NameHereServicesProvider.php'));
    }

    public function servicesFile()
    {
        return $this->getStubContent("NameHereServices.php");
        // return File::get(app_path('Console/Commands/NameHereServices.php'));
    }

    public function interfaceFile()
    {
        return $this->getStubContent("NameHereInterface.php");
        // return File::get(app_path('Console/Commands/NameHereInterface.php'));
    }

    public function repositoryFile()
    {
        return $this->getStubContent("NameHereRepository.php");
        // return File::get(app_path('Console/Commands/NameHereRepository.php'));
    }

    public function rootRepoProviderGenerate($name)
    {
        $path = $this->generateFile('Repositories/RepositoryServiceProvider.php', $this->rootRepoProviderFile());
        $file = $this->replaceTextFile($path, $name);
        return true;
    }

    public function generateFile($path, $file)
    {
        $path = app_path($path);
        $fh = fopen($path, 'w') or die("can't open file");
        $stringData = $file;
        fwrite($fh, $stringData);
        fclose($fh);
        return $path;
    }

    public function replaceTextFile($path, $name)
    {
        $str = file_get_contents($path);
        $str = str_replace('NameHere', $name, $str);
        file_put_contents($path, $str);
    }

    public function replaceTextFileAgain($path, $name)
    {
        $str = file_get_contents($path);
        $str = str_replace('smallNameHere', $name, $str);
        file_put_contents($path, $str);
    }

    public function rootRepoProviderFile()
    {
        return $this->getStubContent("rootRepoProvider.php");
        // return File::get(app_path('Console/Commands/rootRepoProvider.php'));
    }
}
