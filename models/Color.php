<?php namespace Maki3000\Project\Models;

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

    public $belongsToMany = [
        'project' => \Maki3000\Project\Models\Project::class,
        'table' => 'maki3000_project_project_color',
        'key' => 'color_id',
        'otherKey' => 'project_id'
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        "name"          => "string|required",
        "color_value"   => "string|required",
    ];
}
