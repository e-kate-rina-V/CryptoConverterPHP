<?php

include_once __DIR__ . '/../Greet.php';

trait FirstGreeting
{
    public function greeting()
    {
        echo 'Glad to see you ';
        parent::greeting();
    }
}
