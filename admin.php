<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
        public function dasboard()
    {
        // Cek apakah pengguna sudah login
        if (!$this->session->userdata('nama')) {
            // Jika belum login, redirect ke halaman login
            redirect('auth');
        }

        // Cek level pengguna
        $level = $this->session->userdata('level');
        if ($level != 'admin') {
            // Jika level bukan userpremium, redirect ke halaman yang sesuai
            if ($level == 'user') {
                redirect('user/index');
            } elseif ($level == 'userpremium') {
                redirect('userpremium/index');
            } elseif ($level == 'penerbit') {
                redirect('penerbit/index');
            }
        }

        // Jika sudah login dan memiliki level userpremium, tampilkan halaman userpremium
        $this->load->view('Dashboard-admin');
    }


    public function buku()
    {
       $this->load->view('managebuku');
    }
    public function bukuadmin()
    {
        $this->load->view('buku-admin');
    }

    public function transaksiadmin()
    {
        $this->load->view('transaksi-admin');
    }
    public function tambahtransaksi()
    {
        $this->load->view('tambah-transaksi');
    }

        public function profil()
    {
        // Pastikan pengguna sudah login
        if (!$this->session->userdata('nama')) {
            redirect('auth');
        }

        // Ambil level pengguna dari session
    
        $level = $this->session->userdata('level');

        // Cek apakah level pengguna adalah "user"
        if ($level != 'admin') {
            // Jika bukan "user", redirect ke halaman yang sesuai
            if ($level == 'user') {
                redirect('user/index');
            } elseif ($level == 'userpremium') {
                redirect('userpremium/index');
            }
        }

        $nama = $this->session->userdata('nama');
        $password = $this->session->userdata('password');
        

        // Kirim informasi ke view
        $data['user'] = $this->db->get_where('user', ['nama' => $nama])->row_array();

        // Tampilkan view profil dengan data pengguna
        $this->load->view('profiladmin', $data);
    }

    public function ganti_password()
    {
        // Pastikan pengguna sudah login
        if (!$this->session->userdata('nama')) {
            redirect('auth');
        }

        // Ambil input dari form
        $password_baru = $this->input->post('password_baru');
        $konfirmasi_password = $this->input->post('konfirmasi_password');

        // Validasi form
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[8]');
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'required|matches[password_baru]');

        if ($this->form_validation->run() == false) {
            // Validasi gagal, tampilkan kembali form dengan pesan kesalahan
            $this->load->view('profiladmin', ['error' => validation_errors()]);
        } else {
            // Validasi sukses, update password
            $nama = $this->session->userdata('nama');
            $data = ['password' => password_hash($password_baru, PASSWORD_DEFAULT)];
            $this->db->where('nama', $nama);
            $this->db->update('user', $data);

            // Tampilkan pesan sukses dan logout
            $this->session->unset_userdata('nama');
            $this->session->unset_userdata('level');
            redirect('auth');
        }
    }
    public function editbuku()
    {
        $this->load->view('buku-edit');
    }
}
