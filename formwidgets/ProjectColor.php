<?php namespace Maki3000\Project\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Illuminate\Support\Str;

use Maki3000\Project\Models\Project as ProjectModel;
use Maki3000\Project\Models\Color as ColorModel;

class ProjectColor extends FormWidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'projectcolor';

    public function init()
    {
        $this->addJs("js/projectcolor.js");
    }

    public function render()
    {
        if (isset($this->model->id) && $this->model->id > 0) {
            $nameDataNumber = $this->model->id;
            $projects = ProjectModel::where("id", $this->model->id)
                ->select("basics")
                ->first()
                ->toArray();
        } else {
            $nameDataNumber = 0;
        }

        $colors = ColorModel::get();
        $colorValues = $colors->pluck("color_value")->toArray();
        $renderColorsArray = [];

        $update = false;
        if (isset($this->model->id)) {
            $update = true;
        }

        if (isset($colors) && $colors->count() > 0) {
            $colorForeachIndex = 0;
            foreach ($colors as $index => $colorEntry) {
                $color_field_name = preg_split("/(\[[0-9]+\])/", $this->getFieldName());
                $colorEntryId = $colorEntry->id;

                $renderColorsArray[$colorEntryId]["index"] = $index;
                $renderColorsArray[$colorEntryId]["id"] = $colorEntryId;
                $renderColorsArray[$colorEntryId]["name"] = $colorEntry->name;
                $renderColorsArray[$colorEntryId]["color_value"] = $colorEntry->color_value;
                $renderColorsArray[$colorEntryId]["field_name"] = $color_field_name[0];
                $renderColorsArray[$colorEntryId]["field_name"] .= "[" . $nameDataNumber . "]";
                $renderColorsArray[$colorEntryId]["field_name"] .= $color_field_name[1];
                $renderColorsArray[$colorEntryId]["field_name"] .= "[0]";
                $renderColorsArray[$colorEntryId]["field_name"] .= "[" . $colorEntryId . "]";

                // count index up
                $colorForeachIndex++;
            }
        }

        $renderColorValuesArray = [];

        if (isset($projects) && count($projects) > 0) {
            $uniqueId = (string)Str::uuid();
            $projectColorForeachIndex = 0;

            if (isset($projects["basics"])) {
                foreach ($projects["basics"] as $projectIndex => $basic) {
                    if (isset($basic) && isset($basic["colors"]) && count($basic["colors"]) > 0) {
                        $projectColorForeachInnerIndex = 1;
                        $renderColorValuesArray[$uniqueId][$projectIndex][$projectColorForeachInnerIndex]["value"] = null;
                        foreach ($basic["colors"] as $projectColorIndex => $color) {
                            $renderColorValuesArray[$uniqueId][$projectIndex][$projectColorForeachInnerIndex]["value"] = null;
                            if (isset($color) && strlen($color) > 1) {
                                $renderColorValuesArray[$uniqueId][$projectIndex][$projectColorForeachInnerIndex]["value"] = $color;
                            }
                            $projectColorForeachInnerIndex++;
                        }
                    }
                    $projectColorForeachIndex++;
                }
            }
        }

        $this->vars['renderColorsArray'] = $renderColorsArray;
        $this->vars["update"] = $update;
        $this->vars['renderColorValuesArray'] = $renderColorValuesArray;

        return $this->makePartial("projectcolor");
    }

    public function getSaveValue($value)
    {
        return $value;
    }

}
