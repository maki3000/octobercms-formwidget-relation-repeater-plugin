<?php namespace Maki3000\Project;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public function boot()
    {
        if (!\App::runningInBackend()) {
            return;
        }

        \Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {
            if ($controller instanceof \Maki3000\Project\Controllers\Projects && is_array($params)) {
                $controller->addCss('/plugins/maki3000/project/assets/css/project.css');
                $controller->addJs('/plugins/maki3000/project/assets/js/project.js');
            }
        });
    }

    public function registerComponents()
    {
        return [
            \Maki3000\Project\Components\Project::class => 'project',
        ];
    }

    public function registerSettings()
    {
    }

    public function registerFormWidgets()
    {
        return [
            \Maki3000\Project\FormWidgets\ProjectColor::class => 'projectcolor',
        ];
    }

}
