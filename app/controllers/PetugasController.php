<?php

/**
 * 
 */
class PetugasController extends Controller
{
    public function __construct() 
    {
        checkIsNotLogin();
    }
    public function index()
    {
        $this->view('petugas/home');
    }
}
