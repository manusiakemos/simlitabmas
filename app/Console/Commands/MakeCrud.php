<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Crud';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $className = $this->ask('Input Classname?');
        $componentType = $this->ask('Component Type');

        Artisan::call('make:controller ' . $className . 'Controller -r');
        Artisan::call('make:resource ' . $className . 'Resource');
        Artisan::call('make:model Models/' . $className);

        File::append(base_path("routes/api.php"), "\n " . "Route::resource('" . strtolower($className) . "', '" . $className . "Controller');");

        $this->component($className, $componentType);
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    protected function component($className, $componentType)
    {
        /*$modelTemplate = str_replace(
            ['{{modelName}}'],
            [$name],
            $this->getStub('Component')
        );*/
        if ($componentType == 'dt') {
            $modelTemplate = $this->getStub('CrudDt');
        } else {
            $modelTemplate = $this->getStub('Crud');
        }
        $path = Str::kebab($className);
        mkdir(resource_path('/js/admin/views/' . $path));
        file_put_contents(resource_path("/js/admin/views/{$path}/Index.vue"), $modelTemplate);
    }
}
