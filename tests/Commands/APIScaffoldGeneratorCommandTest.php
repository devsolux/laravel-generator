<?php

use DevSolux\Generator\Commands\APIScaffoldGeneratorCommand;
use DevSolux\Generator\Facades\FileUtils;
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
use Mockery as m;

use function Pest\Laravel\artisan;

afterEach(function () {
    m::close();
});

it('generates all files for api_scaffold from console', function () {
    FileUtils::fake();

    $shouldHaveCalledGenerators = [
        MigrationGenerator::class,
        ModelGenerator::class,
        RepositoryGenerator::class,
        APIRequestGenerator::class,
        APIControllerGenerator::class,
        APIRoutesGenerator::class,
        RequestGenerator::class,
        ControllerGenerator::class,
        ViewGenerator::class,
        RoutesGenerator::class,
        MenuGenerator::class,
        SeederGenerator::class,
    ];

    mockShouldHaveCalledGenerateMethod($shouldHaveCalledGenerators);

    $shouldNotHaveCalledGenerator = [
        RepositoryTestGenerator::class,
        APITestGenerator::class,
        FactoryGenerator::class,
    ];

    mockShouldNotHaveCalledGenerateMethod($shouldNotHaveCalledGenerator);

    config()->set('laravel_generator.options.seeder', true);

    artisan(APIScaffoldGeneratorCommand::class, ['model' => 'Post'])
        ->expectsQuestion('Field: (name db_type html_type options)', 'title body text')
        ->expectsQuestion('Enter validations: ', 'required')
        ->expectsQuestion('Field: (name db_type html_type options)', 'exit')
        ->expectsQuestion(PHP_EOL.'Do you want to migrate database? [y|N]', false)
        ->assertSuccessful();
});

it('generates all files for api_scaffold from fields file', function () {
    $fileUtils = FileUtils::fake([
        'createFile'                => true,
        'createDirectoryIfNotExist' => true,
        'deleteFile'                => true,
    ]);

    $shouldHaveCalledGenerators = [
        MigrationGenerator::class,
        ModelGenerator::class,
        RepositoryGenerator::class,
        APIRequestGenerator::class,
        APIControllerGenerator::class,
        APIRoutesGenerator::class,
        RequestGenerator::class,
        ControllerGenerator::class,
        ViewGenerator::class,
        RoutesGenerator::class,
        MenuGenerator::class,
        RepositoryTestGenerator::class,
        APITestGenerator::class,
        FactoryGenerator::class,
    ];

    mockShouldHaveCalledGenerateMethod($shouldHaveCalledGenerators);

    $shouldNotHaveCalledGenerator = [
        SeederGenerator::class,
    ];

    mockShouldNotHaveCalledGenerateMethod($shouldNotHaveCalledGenerator);

    config()->set('laravel_generator.options.tests', true);

    $modelSchemaFile = __DIR__.'/../fixtures/model_schema/Post.json';

    $fileUtils->shouldReceive('getFile')
        ->withArgs([$modelSchemaFile])
        ->andReturn(file_get_contents($modelSchemaFile));
    $fileUtils->shouldReceive('getFile')
        ->andReturn('');

    artisan(APIScaffoldGeneratorCommand::class, ['model' => 'Post', '--fieldsFile' => $modelSchemaFile])
        ->expectsQuestion(PHP_EOL.'Do you want to migrate database? [y|N]', false)
        ->assertSuccessful();
});
