<?php
include_once __DIR__ . '/greeting_traits/FirstGreeting.php';
include_once __DIR__ . '/greeting_traits/SecondGreeting.php';

class Greeting extends Greet
{
    use FirstGreeting, SecondGreeting {
        SecondGreeting::greeting insteadof FirstGreeting;
    }
}

$greeting = new Greeting();
