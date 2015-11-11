<?php namespace BootstrapHunter\Support\Components;

use Cms\Classes\ComponentBase;

class UpdateTicket extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name'        => 'Update Ticket',
      'description' => 'Form to post new message in existing ticket.'
    ];
  }


}