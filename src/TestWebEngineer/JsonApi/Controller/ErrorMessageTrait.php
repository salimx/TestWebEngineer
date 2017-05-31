<?php
declare(strict_types = 1);
namespace TestWebEngineer\JsonApi\Controller;

trait ErrorMessageTrait
{
    protected $errors = [
        'TNF' => ['Error' => 'Track not found'],
        'UNF' => ['Error' => 'User not found'],
        'FNF' => ['Error' => 'Favorite track not found'],
        'TAF' => ['Error' => 'Track already in user favorite tracks'],
        'CD' =>  ['Error' => 'Corrupt Data received'],
        'SE'  => ['Error' => 'Service Error'],
    ];
}
