<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Docusign Host
     |--------------------------------------------------------------------------
     |
     | Change this to production before going live
     |
     */

    'host' => env('DOCUSIGN_HOST'),

    /*
     |--------------------------------------------------------------------------
     | Docusign Default Credentials
     |--------------------------------------------------------------------------
     |
     | These are the credentials that will be used if none are specified
     |
     */

    'username' => env('DOCUSIGN_USERNAME'),

    'password' => env('DOCUSIGN_PASSWORD'),

    'integrator_key' => env('DOCUSIGN_INTEGRATOR_KEY')


];
