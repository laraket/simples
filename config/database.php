<?php 

return [
    'elasticsearch' => [
        'hosts' => explode(',', env('ES_HOSTS', '127.0.0.1:9200')),
    ]
];