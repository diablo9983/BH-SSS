<?php namespace BootstrapHunter\Support;

use Event;
use Backend;
use System\Classes\PluginBase;

/**
 * support Plugin Information File
 */
class Plugin extends PluginBase
{

    public $require = ['RainLab.User'];
    
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'BH Support',
            'description' => 'Manage user tickets',
            'author'      => 'Bootstrap Hunter',
            'icon'        => 'icon-support'
        ];
    }

    
    public function registerComponents()
    {
        return [
            'BootstrapHunter\Support\Components\Tickets' => 'tickets',
            'BootstrapHunter\Support\Components\TicketMessages' => 'ticketmessages',
            'BootstrapHunter\Support\Components\NewTicket' => 'newticket',
            'BootstrapHunter\Support\Components\UpdateTicket' => 'updateticket'
        ];
    }

    public function registerPermissions()
    {
        return [
            'bootstraphunter.support.access_tickets' => ['label' => 'View tickets'],
            //'bootstraphunter.support.access_licenses' => ['label' => 'View licenses'],
            //'bootstraphunter.support.access_types' => ['label' => 'View item types']
        ];
    }

    public function registerNavigation()
    {
        return [
            'support' => [
                'label'         => 'Support',
                'url'           => Backend::url('bootstraphunter/support/tickets'),
                'icon'          => 'icon-support',
                //'permissions'   => 'bootstraphunter.products.access_items',
                'order'         => 500,

                
                'sideMenu' => [
                    'tickets' => [
                        'label'       => 'Tickets',
                        'icon'        => 'icon-ticket',
                        'url'         => Backend::url('bootstraphunter/support/tickets'),
                        'permissions' => ['bootstraphunter.support.access_tickets']
                    ],
                    'types' => [
                        'label'       => 'Types',
                        'icon'        => 'icon-list',
                        'url'         => Backend::url('bootstraphunter/support/types'),
                        'permissions' => ['bootstraphunter.products.access_types']
                    ]
                ]
            ]
        ];
    }
}
