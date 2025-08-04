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
    public function model($model)
    {
        include '../app/models/' . $model . '.php';
        return new $model;
    }
    
}