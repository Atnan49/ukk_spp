<?php
/**
 * 
 */
class SiswaController extends Controller
{
    public function __construct() 
    {
        checkIsNotLogin();
    }
    public function index()
    {
        // Validasi session dan kirim data user ke view
        $data = [
            'username' => $_SESSION['username'] ?? null,
            'level' => $_SESSION['level'] ?? null,
        ];
        $this->view('siswa/home', $data);
    }
    public function edit($id)
    {
        return 'ini adalah method edit() di dalam class SiswaController dengan parameter: ' . $id;
    }
}