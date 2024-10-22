<?php
include_once __DIR__ . '/../Greet.php';

trait SecondGreeting
{
    public function greeting()
    {
        echo 'Welcome to ';
        parent::greeting();
    }
}
