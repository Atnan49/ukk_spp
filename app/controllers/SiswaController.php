<?php
/**
 * 
 */
class SiswaController extends Controller
{
    public function index()
    {
        $this->view('siswa/home', ['data' => 'ini data data']);
    }
    public function edit($id)
    {
        return 'ini adalah method edit() di dalam class SiswaController dengan parameter: ' . $id;
    }
}