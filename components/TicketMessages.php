<?php namespace BootstrapHunter\Support\Components;

use Auth;
use Cms\Classes\ComponentBase;
use Backend\Models\User as BackendUserModel;
use BootstrapHunter\Support\Models\Ticket as TicketModel;
use BootstrapHunter\Support\Models\Message as MessageModel;

class TicketMessages extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name'        => 'Tickets',
      'description' => 'List user support tickets and their status.'
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
    $this->page['ticket'] = TicketModel::find($this->property('ticket_id'))->toArray();
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