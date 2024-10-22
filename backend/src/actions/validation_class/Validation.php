<?php

require_once __DIR__ . '/ValidatorInterface.php';

abstract class Validation implements ValidatorInterface
{
    public function __construct(protected $data) {}

    abstract public function validate(): bool;
}
