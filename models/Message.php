<?php namespace BootstrapHunter\Support\Models;

use Model;

class Message extends Model
{
  //use \October\Rain\Database\Traits\Validation;

  public $belongsTo = [
    'ticket' => ['BootstrapHunter\Support\Models\Ticket', 'key' => 'ticker_id'],
    /*'user' => ['RainLab\User\Models\User', 'key' => 'user_id']*/
  ];

  protected $table = 'bootstraphunter_support_messages';

  protected $dates = ['created_by'];
}
