<?php namespace BootstrapHunter\Support\Components;

use Cms\Classes\ComponentBase;

class NewTicket extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name'        => 'New Ticket',
      'description' => 'Form to create a new ticket.'
    ];
  }


}