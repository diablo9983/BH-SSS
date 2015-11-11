<?php namespace BootstrapHunter\Support\Controllers;

use Flash;
use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use ApplicationException;
use BootstrapHunter\Support\Models\Ticket as TicketModel;
use BootstrapHunter\Support\Models\Message as MessageModel;

class Tickets extends Controller
{
  public $implement = ['Backend.Behaviors.ListController'];

	public $requiredPermissions = ['bootstraphunter.support.*'];

  public $listConfig = 'config_list.yaml';

  public $bodyClass = 'compact-container';

	public function __construct()
	{
		parent::__construct();
		
		BackendMenu::setContext('BootstrapHunter.Support', 'support', 'tickets');
	}

	public function index()
	{
    $this->asExtension('ListController')->index();
	}

	public function view($id = 0)
	{
    $users = ['backend' => ['0' => ['id' => '0', 'first_name' => '(No','last_name' => 'one)']], 'frontend' => []];
    $id = intval($id);
    if($id < 1) {
      Flash::error('Invalid Ticked ID.');
      return redirect(Backend::url('bootstraphunter/support/tickets'));
    }

    $e = TicketModel::find($id);

    if(!$e) {
      Flash::error('Invalid Ticked ID.');
      return redirect(Backend::url('bootstraphunter/support/tickets'));
    }

    if(!isset($users['frontend'][$e->created_by]))
    {
      $users['frontend'][$e->created_by] = $e->createdby;
    }

    if($e->assigned_to > 0)
    {
      if($e->assigned_to == $this->user->id)
      {
        $users['backend'][$e->assigned_to] = $this->user;
      }
      if(!isset($users['backend'][$e->assigned_to]))
      {
        $users['backend'][$e->assigned_to] = $e->assignedto;
      }
    }

    $this->vars['createdby'] = $users['frontend'][$e->created_by];
    $this->vars['assignedto'] = $users['backend'][$e->assigned_to];

    $e->messages->each(function($message) use($users)
    {
      if($message->backend) {
        if(!isset($users['backend'][$message->user_id])) {
          if($message->user_id == $this->user->id) {
            $users['backend'][$message->user_id] = $this->user;
          } else
          {
            $users['backend'][$message->user_id] = \Backend\Models\User::where('id','=',$message->user_id)
                                                                    ->select('first_name','last_name')
                                                                    ->get()->first()->toArray();
          }
        }
        $message->user = $users['backend'][$message->user_id];
      } else {
        if(!isset($users['frontend'][$message->user_id])) {
          $users['frontend'][$message->user_id];
        }
        $message->user = $users['frontend'][$message->user_id];
      }
    });

    foreach($e->toArray() as $key => $value) {
      if($key == 'messages') {
        continue;
      }
      $this->vars[$key] = $value;
    }

    $this->vars['messages'] = $e->toArray()['messages'];
		
    $this->addCss('/plugins/bootstraphunter/support/assets/css/support.ticket.css');
	}
}