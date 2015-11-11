<?php namespace BootstrapHunter\Support\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use ApplicationException;

class Types extends Controller
{
  public $implement = ['Backend.Behaviors.ListController'];

	public $requiredPermissions = ['bootstraphunter.support.*'];

  public $listConfig = 'config_list.yaml';

	public function __construct()
	{
		parent::__construct();
		
		BackendMenu::setContext('BootstrapHunter.Support', 'support', 'tickets');
	}

	public function index()
	{
    $this->asExtension('ListController')->index();
	}
}