<?php

namespace DevSolux\Generator\Commands\API;

use DevSolux\Generator\Commands\BaseCommand;
use DevSolux\Generator\Generators\API\APIControllerGenerator;

class APIControllerGeneratorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'devsolux.api:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an api controller command';

    public function handle()
    {
        parent::handle();

        /** @var APIControllerGenerator $controllerGenerator */
        $controllerGenerator = app(APIControllerGenerator::class);
        $controllerGenerator->generate();

        $this->performPostActions();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), []);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array_merge(parent::getArguments(), []);
    }
}
