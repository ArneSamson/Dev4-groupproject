<?php
//laad alle benodigde bestanden ipv ze allemaal apart te includen

    //laad alle klassen
    spl_autoload_register(function($class) {
        require_once 'config/' . $class . '.php';
    });

    //start de sessie
    session_start();