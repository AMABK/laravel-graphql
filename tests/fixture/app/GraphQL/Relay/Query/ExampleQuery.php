<?php

namespace App\GraphQL\Relay\Query;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use GraphQL;

use App\Data;

class ExampleQuery extends Query
{
    protected $attributes = [
        'name' => 'example'
    ];
    
    protected function type()
    {
        return GraphQL::type('ExampleNode');
    }

    protected function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::id())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $node = Data::getById($args['id']);
        return $node;
    }
}
