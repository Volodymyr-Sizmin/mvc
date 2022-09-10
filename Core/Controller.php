<?php

namespace Core;

abstract class Controller
{
    public function before(string $action)
    {
        return true;
    }

    public function after(string $action){}

}