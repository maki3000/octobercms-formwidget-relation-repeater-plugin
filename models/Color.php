<?php namespace Maki3000\Project\Models;

use Maki3000\Project\Models\Project as ProjectModel;

use Model;

/**
 * Model
 */
class Color extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'maki3000_project_color';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name'          => 'string|required',
        'color_value'   => 'string|required',
    ];

    public function beforeDelete()
    {
        // also delete colors in project basics
        $projectModel = new ProjectModel;
        $projects = $projectModel::get();

        if (isset($projects) && count($projects) > 0) {
            foreach($projects as $projectKey => $project) {
                $projectBasics = $project->basics;
                if (isset($projectBasics) && count($projectBasics) > 0) {
                    foreach($projectBasics as $basicKey => $basics) {
                        $projectBasicsSaveArray = [];

                        if (isset($basics['name'])) {
                            $projectBasicsSaveArray['name'] = $basics['name'];
                        }
                        if (isset($basics['url'])) {
                            $projectBasicsSaveArray['url'] = $basics['url'];
                        }
                        if (isset($basics['new_window'])) {
                            $projectBasicsSaveArray['new_window'] = $basics['new_window'];
                        }
                        if (isset($basics['pop_up_counter'])) {
                            $projectBasicsSaveArray['pop_up_counter'] = $basics['pop_up_counter'];
                        }
                        if (isset($basics['font_size'])) {
                            $projectBasicsSaveArray['font_size'] = $basics['font_size'];
                        }
                        if (isset($basics['font_family'])) {
                            $projectBasicsSaveArray['font_family'] = $basics['font_family'];
                        }
                        if (isset($basics['colors']) && count($basics['colors'])) {
                            foreach($basics['colors'] as $colorKey => $color) {
                                if ($colorKey !== $this->id) {
                                    $projectBasicsSaveArray['colors'][$colorKey] = $color;
                                }
                            }
                        }
                    }
                    $projectModel->where('id', $project->id)->update(['basics' => [$projectBasicsSaveArray]]);
                }
            }
        }
    }

}
