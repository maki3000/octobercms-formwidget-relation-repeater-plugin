<?php namespace Maki3000\Project\Components;

use Maki3000\Project\Models\Project as ProjectModel;

class Project extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Project',
            'description' => 'Displays a collection of projects.'
        ];
    }

    public function onRun()
    {
        $this->addJs("components/assets/js/project.js");

        $this->page['projects'] = $this->projects();
    }

    public function projects()
    {
        $projects = ProjectModel::get();
        $projectsReturnArray = [];

        if (isset($projects) && $projects->count() > 0) {
            $projectIndex = 0;
            foreach ($projects as $projectKey => $project) {
                if (isset($project["basics"]) && count($project["basics"]) > 0) {
                    $basicsOnlyColorsValues = [];
                    $basics = $project["basics"];
                    $newBasics = [];

                    $basicIndex = 0;
                    foreach ($basics as $basicKey => $basic) {
                        if (isset($basic["colors"]) && count($basic["colors"]) > 0) {
                            $basicsOnlyValues = array_filter($basic['colors'], function($value) {
                                return $value !== '0';
                            });
                        }
                        $newBasics[$basicIndex] = array(
                            ...$basic,
                            "colors" => $basicsOnlyValues,
                        );
                        $basicIndex++;
                    }
                    $projectsReturnArray[$projectIndex] = array(
                        ...$project->toArray(),
                        'basics' => $newBasics,
                        "images" => $project["images"]
                    );
                } else {
                    $projectsReturnArray[$projectIndex] = array(
                        ...$project->toArray(),
                        "images" => $project["images"]
                    );
                }
                $projectIndex++;
            }
        }

        return $projectsReturnArray;
    }
}
