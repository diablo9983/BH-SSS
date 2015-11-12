<?php namespace BootstrapHunter\Support\Components;

use DB;
use Auth;
use Cms\Classes\Page;
use Cms\Classes\Theme;
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

  public function defineProperties()
  {
    return [
      'page' => [
        'title'             => 'Page',
        'description'       => 'Page where ticket details component will be used.',
        'type'              => 'dropdown',
        'required'          => 'true',
        'validationMessage' => 'You must choose page.'
      ]
    ];
  }

  protected function getPageOptions()
  {
    $pages = Page::getNameList();

    return $pages;
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

    $theme = Theme::getEditTheme();
    $pages = Page::listInTheme($theme, true);

    foreach ($pages as $page) {
      if($page->baseFileName == $this->property('page')) {
        $this->page['page'] = $page->url;
      }
    }
    $this->page['tickets'] = $tickets;
  }

}