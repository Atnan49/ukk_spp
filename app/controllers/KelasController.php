<?php

/**
 * 
 */
class KelasController extends Controller
{
    public function __construct() 
    {
        checkIsNotLogin();
    }
    public function index()
    {
        $this->view('kelas/home');
    }
}
