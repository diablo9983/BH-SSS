<?php namespace BootstrapHunter\Support\Models;

use Model;

class Ticket extends Model
{
  //use \October\Rain\Database\Traits\Validation;

	public $belongsTo = [
		'createdby' => ['RainLab\User\Models\User','key' => 'created_by'],
		'lastmessage' => ['RainLab\User\Models\User','key' => 'last_message'],
		'assignedto' => ['Backend\Models\User','key' => 'assigned_to']
	];

  public $hasMany = [
    'messages' => ['BootstrapHunter\Support\Models\Message'],
    'messagesCount' => ['BootstrapHunter\Support\Models\Message', 'count' => true]
  ];



  public $table = 'bootstraphunter_support_tickets';

}
