<?php

namespace Laraket\Simples\Console;

use Exception;
use Illuminate\Console\Command;

class ImportCommand extends Command
{
    protected $signature = 'simples:import {model}';

    protected $description = 'Import model data into es';
    
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

        app($this->argument('model'))->query()
            ->chunkById(
                100,
                function ($models) use ($es) {
                    $this->info(sprintf('Syncing ID from %s to %s', $models->first()->id, $models->last()->id));
                    $req = ['body' => []];
                    foreach ($models as $model) {
                        $data = $model->toESArray();

                        $req['body'][] = [
                            'index' => [
                                '_index' => $model->esIndex(),
                                '_type'  => $model->esType(),
                                '_id'    => $data['id'],
                            ],
                        ];
                        $req['body'][] = $data;
                    }
                    try {
                        $es->bulk($req);
                    } catch (Exception $e) {
                        $this->error($e->getMessage());
                    }
                }
            );
        $this->info('Imported.');
    }
}