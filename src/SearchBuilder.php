<?php 

namespace Laraket\Simples;

class SearchBuilder 
{
    protected $params = [
        'index' => '',
        'type'  => '',
        'body'  => [
            'query' => [
                'bool' => [
                    'filter' => [],
                    'must'   => [],
                ],
            ],
            'from' => 0,
            'size' => 1,
        ],
    ];

    public function __construct($model, $searchData = [], $filterData = [])
    {
        $this->params['index'] = $model->esIndex();
        $this->params['type'] = $model->esType();

        if (count($searchData) > 0) {
            $this->params['body']['query']['bool']['must'][] = $searchData;
        }

        if (count($filterData) >0) {
            $this->params['body']['query']['bool']['filter'][] = $filterData;
        }
    }

    public function from($from)
    {
        $this->params['body']['from'] = $from;
        return $this;
    }

    public function size($size)
    {
        $this->params['body']['size'] = $size;
        return $this;
    }
    
    public function random()
    {
        $this->params['body']['sort'] = [
            "_script" => [
                "script" => "Math.random()",
                "type" => "number",
                "order" => "asc"
            ],
        ];

        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function search()
    {
        return app('es')->search($this->params);
    }

}