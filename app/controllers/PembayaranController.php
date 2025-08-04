<?php

/**
 * 
 */
class PembayaranController extends Controller
{
    public function __construct() 
    {
        checkIsNotLogin();
    }
    public function index()
    {
        $this->view('pembayaran/home');
    }
}
