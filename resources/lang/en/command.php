<?php

return [
    'rsa:gen' => 'Generate OAUTH2 private.key and public.key',
    'key:generate' => 'Generate unique site key for HASH',
    'storage:link' => 'Create a symbolic link from "public/storage" to "storage/app/public"',

    // clear
    'clear:avatar' => 'Clean up local / COS unused avatars',
    'clear:attachment' => 'Clean up local / COS unused attachments',
    'clear:video' => 'Clean up unposted topic videos',

    // upgrade
    'upgrade:category-permission' => 'Initialize category permission for historical data',
    'upgrade:videoSize' => 'Initialize video size for front display',
    'upgrade:notice' => 'Update iteration. New notification type data format',
];
