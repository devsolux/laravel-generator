<?php

namespace DevSolux\Generator\Commands\Scaffold;

use DevSolux\Generator\Commands\BaseCommand;
use DevSolux\Generator\Generators\Scaffold\ControllerGenerator;

class ControllerGeneratorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'devsolux.scaffold:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create controller command';

    public function handle()
    {
        parent::handle();

        /** @var ControllerGenerator $controllerGenerator */
        $controllerGenerator = app(ControllerGenerator::class);
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
