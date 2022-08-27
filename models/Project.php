<?php namespace Maki3000\Project\Models;

use Model;
use ApplicationException;

/**
 * Model
 */
class Project extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    private static $projectColorsToSave = [];

    private function setProjectColorsToSave($projectColors) {
        self::$projectColorsToSave = $projectColors;
    }

    private function getProjectColorsToSave() {
        return self::$projectColorsToSave;
    }

    protected $jsonable = ['basics'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'maki3000_project_project';

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        "name"          => "string|required",
        "slug"          => "string|required",
    ];

    public function getFontFamilyOptions()
    {
        return [
            'Helvetica, sans-serif' => 'Helvetica, sans-serif',
            'Arial, sans-serif' => 'Arial, sans-serif',
            'Arial Black, sans-serif' => 'Arial Black, sans-serif',
            'Verdana, sans-serif' => 'Verdana, sans-serif',
            'Tahoma, sans-serif' => 'Tahoma, sans-serif',
            'Trebuchet MS, sans-serif' => 'Trebuchet MS, sans-serif',
            'Impact, sans-serif' => 'Impact, sans-serif',
            'Gill Sans, sans-serif' => 'Gill Sans, sans-serif',
            'Times New Roman, serif' => 'Times New Roman, serif',
            'Georgia, serif' => 'Georgia, serif',
            'Palatino, serif' => 'Palatino, serif',
            'Baskerville, serif' => 'Baskerville, serif',
            'Andalé Mono, monospace' => 'Andalé Mono, monospace',
            'Courier, monospace' => 'Courier, monospace',
            'Lucida, monospace',
            'Lucida, monospace' => 'Monaco, monospace',
            'Bradley Hand, cursive' => 'Bradley Hand, cursive',
            'Brush Script MT, cursive' => 'Brush Script MT, cursive',
            'Luminari, fantasy' => 'Luminari, fantasy',
            'Comic Sans MS, cursive' => 'Comic Sans MS, cursive',
        ];
    }

    public function beforeValidate()
    {
        $newBasics = $this->basics;
        if (isset($newBasics) && count($newBasics) > 0) {
            foreach ($newBasics as $basicKey => $basic) {
                unset($basic["_project_color"]);
                // silly validation for repeater
                if (isset($basic['name']) && strlen($basic['name']) === 0) {
                    throw new \ValidationException(['basics.'.$basicKey.'.name' => 'Basics name is required.']);
                }
                if (isset($basic['url']) && strlen($basic['url']) === 0) {
                    throw new \ValidationException(['basics.'.$basicKey.'.url' => 'Basics url is required.']);
                }
            }
        }
    }

    public function beforeSave()
    {
        // check if create
        $update = false;
        $existingProjectEntry = $this->where('id', $this->id)->first();
        if (isset($existingProjectEntry) && $existingProjectEntry->count() > 0) {
            $update = true;
        }
        // clean out _project_color from basics repeater data
        $newBasics = [];
        if (isset($this->basics) && count($this->basics) > 0) {
            $basicsArrayIndex = 0;
            foreach ($this->basics as $key => $basicsArray) {
                if (isset($basicsArray["name"])) {
                    $newBasics[$basicsArrayIndex]["name"] = $basicsArray["name"];
                }
                if (isset($basicsArray["url"])) {
                    $newBasics[$basicsArrayIndex]["url"] = $basicsArray["url"];
                }
                if (isset($basicsArray["new_window"])) {
                    $newBasics[$basicsArrayIndex]["new_window"] = $basicsArray["new_window"];
                }
                if (isset($basicsArray["pop_up_counter"])) {
                    $newBasics[$basicsArrayIndex]["pop_up_counter"] = $basicsArray["pop_up_counter"];
                }
                if (isset($basicsArray["font_size"])) {
                    $newBasics[$basicsArrayIndex]["font_size"] = $basicsArray["font_size"];
                }
                if (isset($basicsArray["font_family"])) {
                    $newBasics[$basicsArrayIndex]["font_family"] = $basicsArray["font_family"];
                }
                // set _project_color to save it in afterSave
                if (isset($basicsArray["_project_color"])) {
                    $this->setProjectColorsToSave($basicsArray["_project_color"]);
                    $projectColors = $basicsArray["_project_color"];
                }
                // count index up
                $basicsArrayIndex++;
            }
        }
        // clean array keys (because second position in basics are project color values)
        $cleanBasics = [];
        $basicsCleanArrayIndex = 0;
        foreach($newBasics as $cleanBasic) {
            $cleanBasics[$basicsCleanArrayIndex] = $cleanBasic;
            if (isset($projectColors)) {
                if (!isset($projectColors[$basicsCleanArrayIndex])) {
                    throw new ApplicationException('Project colors not set properly. Most probably because of JS not setting save values right.');
                    return [];
                }
                $cleanBasics[$basicsCleanArrayIndex]['colors'] = $projectColors[$basicsCleanArrayIndex];
            }
            $basicsCleanArrayIndex++;
        }

        $this->basics = $cleanBasics;
    }

}
