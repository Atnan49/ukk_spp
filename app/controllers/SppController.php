<?php
/**
 * 
 */
class SppController extends Controller
{
    public function __construct() 
    {
        checkIsNotLogin();
    }
    public function index()
    {
        $this->view('spp/home');
    }
}