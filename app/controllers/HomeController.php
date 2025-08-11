<?php
/**
 * 
*/

class HomeController extends Controller
{
    public function __construct() 
    {
        checkIsNotLogin();
    }
    public function index()
    {
        $data = [
            'username' => $_SESSION['username'] ?? null,
            'level' => $_SESSION['level'] ?? null,
        ];
        $this->view('home', $data);
    }
}