<?php namespace BootstrapHunter\Support\Components;

use DB;
use Auth;
use Cms\Classes\ComponentBase;
use BootstrapHunter\Support\Models\Ticket as TicketModel;

class Tickets extends ComponentBase
{

  public $tickets;

  public function componentDetails()
  {
    return [
      'name'        => 'Tickets',
      'description' => 'List user support tickets and their status.'
    ];
  }

  protected function tickets()
  {
    return TicketModel::where('user_id',Auth::getUser()->id)
                        ->orderBy('updated_at','desc')
                        ->get();
  }

  public function onRun()
  {
    $tickets = $this->tickets()->toArray();
    $this->page['tickets'] = $tickets;

    /*
    $tickets->each(function($ticket) {
      $this->tickets[] = $ticket; 
    });*/
  }

}