<?php
/**
 * 
 */
class Controller
{
    public function view($path, $data = [])
    {
        extract($data);
        include '../app/controllers/view/' . $path . '.php';
    }
    
}