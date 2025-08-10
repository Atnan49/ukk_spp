<?php
/**
 * LoginController handles user login functionality.
 */
class LoginController extends Controller
{
    public function index()
    {
        checkIsLogin();

        if (isset($_SESSION['LOGIN']) && $_SESSION['LOGIN'] === true) {
            header("location: http://localhost/ukk_spp/");
            exit();
        }

        $this->view("login");
    }
    public function login()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $data = $this->model('user')->getByUsername($username);
        $remember = $_POST['remember'] ?? null;

        
        //memeriksa data ada di database
        if (!empty($data)){
            if (password_verify($password, $data['password'])) {
                $_SESSION ['LOGIN'] = true;
                $_SESSION ['username'] = $data['user_name'];
                $_SESSION ['level'] = $data['level'];
                header("location: http://localhost/ukk_spp/");
                
                if ($remember) {
                    createCookie($username, $data);
                } else {
                    // Hapus cookie jika tidak diingat
                    if (isset($_COOKIE['key'])) {
                        setcookie('key', '', time() - 3600, '/');
                    }
                    
                }
                // periksa password
                echo "Login successful";
            } else {
                echo "Username atau password salah";
            }
        } else {
              echo "Username tidak terdaftar";
        }
    }
    public function logout()
    {
        session_destroy();
        session_unset();
        header("location: http://localhost/ukk_spp/login");
    }
}
    