<?php

namespace DevSolux\Generator\Generators\Scaffold;

use Illuminate\Support\Str;
use DevSolux\Generator\Generators\BaseGenerator;

class RoutesGenerator extends BaseGenerator
{
    public function __construct()
    {
        parent::__construct();

        $this->path = $this->config->paths->routes;
    }

    public function generate()
    {
        $routeContents = g_filesystem()->getFile($this->path);

        $routes = view('laravel-generator::scaffold.routes')->render();

        if (Str::contains($routeContents, $routes)) {
            $this->config->commandInfo(app_nl().'Route '.$this->config->modelNames->dashedPlural.' already exists, Skipping Adjustment.');

            return;
        }

        $routeContents .= app_nl().$routes;

        g_filesystem()->createFile($this->path, $routeContents);
        $this->config->commandComment(app_nl().$this->config->modelNames->dashedPlural.' routes added.');
    }

    public function rollback()
    {
        $routeContents = g_filesystem()->getFile($this->path);

        $routes = view('laravel-generator::scaffold.routes')->render();

        if (Str::contains($routeContents, $routes)) {
            $routeContents = str_replace($routes, '', $routeContents);
            g_filesystem()->createFile($this->path, $routeContents);
            $this->config->commandComment('scaffold routes deleted');
        }
    }
}
