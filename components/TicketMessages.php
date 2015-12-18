<?php namespace BootstrapHunter\Support\Components;

use Auth;
use Flash;
use Cms\Classes\ComponentBase;
use Backend\Models\User as BackendUserModel;
use BootstrapHunter\Support\Models\Ticket as TicketModel;
use BootstrapHunter\Support\Models\Message as MessageModel;

class TicketMessages extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name'        => 'Ticket Details',
      'description' => 'View ticket details with their messages.'
    ];
  }

  public function defineProperties()
  {
    return [
      'ticket_id' => [
        'title'             => 'Ticket ID',
        'description'       => 'Ticket ID for which messages will be retreived.',
        'default'           => '{{ :num }}',
        'type'              => 'string',
        'validationPattern' => '^[0-9]+$',
        'validationMessage' => 'Ticket ID must be provided as intger.'
      ]
    ];
  }

  protected function messages()
  {
    return MessageModel::where('ticket_id',$this->property('ticket_id'))
                        ->orderBy('created_at','asc')
                        ->get();
  }

  public function onRun()
  {
    $ticket = TicketModel::find($this->property('ticket_id'));
    if(is_null($ticket)) {
      Flash::error('Invalid Ticked ID.');
      return redirect('/tickets');
    }

    $this->page['ticket'] = $ticket->toArray();
    $users = ['backend' => [], 'frontend' => []];
    $messages = [];
    $i = 0;
    $msg = $this->messages();
    foreach($msg as $message)
    {
      $messages[$i] = $message->toArray();
      if($message->backend) {
        if(!isset($users['backend'][$message->user_id])) {
          $users['backend'][$message->user_id] = BackendUserModel::where('id','=',$message->user_id)
                                                                    ->select('first_name as name','last_name as surname')
                                                                    ->get()->first()->toArray();
        }
        $messages[$i]['user'] = $users['backend'][$message->user_id];
      } else {
        if($message->user_id == Auth::getUser()->id) {
          $messages[$i]['user']['name'] = Auth::getUser()->name;
          $messages[$i]['user']['surname'] = Auth::getUser()->surname;
        } else {
          if(!isset($users['frontend'][$message->user_id])) {
            $users['frontend'][$message->user_id] = $message->user->select('name','surname')->get()->first()->toArray();
          }
          $messages[$i]['user'] = $users['frontend'][$message->user_id];
        }
      }
      $i++;
    }

    $this->page['messages'] = $messages;
  }

}
