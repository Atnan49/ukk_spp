<?php

/**
 * 
 */
class ProfilController extends Controller
{
    public function __construct() 
    {
        checkIsNotLogin();
    }
    public function index()
    {
        $this->view('profil/home');
    }
}
