<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
    // Leave this alone
    'modules' => array(
 
        // This should be the path to this modules userguide pages, without the 'guide/'. Ex: '/guide/modulename/' would be 'modulename'
        'emb-dispatcher' => array(
 
            // Whether this modules userguide pages should be shown
            'enabled' => TRUE,
 
            // The name that should show up on the userguide index page
            'name' => 'Dispatcher',
 
            // A short description of this module, shown on the index page
            'description' => 'Dispatcher module to enable event driven applications.',
 
            // Copyright message, shown in the footer for this module
            'copyright' => '&copy; 2011 Emiliano Burgos',
        )   
    )
);
?>