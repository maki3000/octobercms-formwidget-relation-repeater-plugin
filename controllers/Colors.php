<?php namespace Maki3000\Project\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Colors extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'project' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Maki3000.Project', 'main-menu-item', 'side-menu-item');
    }
}
