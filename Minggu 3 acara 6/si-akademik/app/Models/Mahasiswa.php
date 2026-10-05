<?php

namespace App\Models;

class Mahasiswa
{
    private $nim;
    private $nama;

    public function __construct($nim, $nama)
    {
        $this->nim = $nim;
        $this->nama = $nama;
    }

    public function getNim()
    {
        return $this->nim;
    }

    public function getNama()
    {
        return $this->nama;
    }

    public function getAngkatan()
    {
        return substr($this->nim, 0, 2);
    }
}