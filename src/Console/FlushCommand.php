<?php

namespace Laraket\Simples\Console;

use Illuminate\Console\Command;

class FlushCommand extends Command
{
    protected $signature = 'simples:flush {model}';

    protected $description = 'Flush the data from es';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handle
     * 
     * @return void
     */
    public function handle()
    {
        $es = app('es');

        $model = app($this->argument('model'));

        $es->deleteByQuery(
            [
                'index' => $model->esIndex(),
                'type'  => $model->esType(),
                'body'  => [
                    'query' => [
                        'match_all' => new \stdClass,
                    ]
                ],
            ]
        );

        $this->info('Flushed.');
    }
}