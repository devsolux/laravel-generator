<?php

namespace DevSolux\Generator;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use DevSolux\Generator\Commands\API\APIControllerGeneratorCommand;
use DevSolux\Generator\Commands\API\APIGeneratorCommand;
use DevSolux\Generator\Commands\API\APIRequestsGeneratorCommand;
use DevSolux\Generator\Commands\API\TestsGeneratorCommand;
use DevSolux\Generator\Commands\APIScaffoldGeneratorCommand;
use DevSolux\Generator\Commands\Common\MigrationGeneratorCommand;
use DevSolux\Generator\Commands\Common\ModelGeneratorCommand;
use DevSolux\Generator\Commands\Common\RepositoryGeneratorCommand;
use DevSolux\Generator\Commands\Publish\GeneratorPublishCommand;
use DevSolux\Generator\Commands\Publish\PublishTablesCommand;
use DevSolux\Generator\Commands\Publish\PublishUserCommand;
use DevSolux\Generator\Commands\RollbackGeneratorCommand;
use DevSolux\Generator\Commands\Scaffold\ControllerGeneratorCommand;
use DevSolux\Generator\Commands\Scaffold\RequestsGeneratorCommand;
use DevSolux\Generator\Commands\Scaffold\ScaffoldGeneratorCommand;
use DevSolux\Generator\Commands\Scaffold\ViewsGeneratorCommand;
use DevSolux\Generator\Common\FileSystem;
use DevSolux\Generator\Common\GeneratorConfig;
use DevSolux\Generator\Generators\API\APIControllerGenerator;
use DevSolux\Generator\Generators\API\APIRequestGenerator;
use DevSolux\Generator\Generators\API\APIRoutesGenerator;
use DevSolux\Generator\Generators\API\APITestGenerator;
use DevSolux\Generator\Generators\FactoryGenerator;
use DevSolux\Generator\Generators\MigrationGenerator;
use DevSolux\Generator\Generators\ModelGenerator;
use DevSolux\Generator\Generators\RepositoryGenerator;
use DevSolux\Generator\Generators\RepositoryTestGenerator;
use DevSolux\Generator\Generators\Scaffold\ControllerGenerator;
use DevSolux\Generator\Generators\Scaffold\MenuGenerator;
use DevSolux\Generator\Generators\Scaffold\RequestGenerator;
use DevSolux\Generator\Generators\Scaffold\RoutesGenerator;
use DevSolux\Generator\Generators\Scaffold\ViewGenerator;
use DevSolux\Generator\Generators\SeederGenerator;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $configPath = __DIR__.'/../config/laravel_generator.php';
            $this->publishes([
                $configPath => config_path('laravel_generator.php'),
            ], 'laravel-generator-config');

            $this->publishes([
                __DIR__.'/../views' => resource_path('views/vendor/laravel-generator'),
            ], 'laravel-generator-templates');
        }

        $this->registerCommands();
        $this->loadViewsFrom(__DIR__.'/../views', 'laravel-generator');

        View::composer('*', function ($view) {
            $view->with(['config' => app(GeneratorConfig::class)]);
        });

        Blade::directive('tab', function () {
            return '<?php echo app_tab() ?>';
        });

        Blade::directive('tabs', function ($count) {
            return "<?php echo app_tabs($count) ?>";
        });

        Blade::directive('nl', function () {
            return '<?php echo app_nl() ?>';
        });

        Blade::directive('nls', function ($count) {
            return "<?php echo app_nls($count) ?>";
        });
    }

    private function registerCommands()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            APIScaffoldGeneratorCommand::class,

            APIGeneratorCommand::class,
            APIControllerGeneratorCommand::class,
            APIRequestsGeneratorCommand::class,
            TestsGeneratorCommand::class,

            MigrationGeneratorCommand::class,
            ModelGeneratorCommand::class,
            RepositoryGeneratorCommand::class,

            GeneratorPublishCommand::class,
            PublishTablesCommand::class,
            PublishUserCommand::class,

            ControllerGeneratorCommand::class,
            RequestsGeneratorCommand::class,
            ScaffoldGeneratorCommand::class,
            ViewsGeneratorCommand::class,

            RollbackGeneratorCommand::class,
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel_generator.php', 'laravel_generator');

        $this->app->singleton(GeneratorConfig::class, function () {
            return new GeneratorConfig();
        });

        $this->app->singleton(FileSystem::class, function () {
            return new FileSystem();
        });

        $this->app->singleton(MigrationGenerator::class);
        $this->app->singleton(ModelGenerator::class);
        $this->app->singleton(RepositoryGenerator::class);

        $this->app->singleton(APIRequestGenerator::class);
        $this->app->singleton(APIControllerGenerator::class);
        $this->app->singleton(APIRoutesGenerator::class);

        $this->app->singleton(RequestGenerator::class);
        $this->app->singleton(ControllerGenerator::class);
        $this->app->singleton(ViewGenerator::class);
        $this->app->singleton(RoutesGenerator::class);
        $this->app->singleton(MenuGenerator::class);

        $this->app->singleton(RepositoryTestGenerator::class);
        $this->app->singleton(APITestGenerator::class);

        $this->app->singleton(FactoryGenerator::class);
        $this->app->singleton(SeederGenerator::class);
    }
}
