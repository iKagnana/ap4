<?php

class ErrorController extends Controller
{
    public function index()
    {
        $this->view("error/prohibited-view");
    }
}