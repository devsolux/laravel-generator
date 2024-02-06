<?php

namespace DevSolux\Generator\Commands\Common;

use DevSolux\Generator\Commands\BaseCommand;
use DevSolux\Generator\Generators\RepositoryGenerator;

class RepositoryGeneratorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'devsolux:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create repository command';

    public function handle()
    {
        parent::handle();

        /** @var RepositoryGenerator $repositoryGenerator */
        $repositoryGenerator = app(RepositoryGenerator::class);
        $repositoryGenerator->generate();

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
