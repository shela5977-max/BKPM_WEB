<?php

class Mahasiswa
{
    private $id;
    private $nim;
    private $nama;
    private $email;
    private $prodiId;
    private $angkatan;
    private $status;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNim()
    {
        return $this->nim;
    }

    public function setNim($nim)
    {
        if (!ctype_digit((string) $nim)) {
            throw new InvalidArgumentException('NIM harus berupa angka.');
        }

        $this->nim = (string) $nim;
    }

    public function getNama()
    {
        return $this->nama;
    }

    public function setNama($nama)
    {
        $nama = trim((string) $nama);
        if ($nama === '') {
            throw new InvalidArgumentException('Nama mahasiswa tidak boleh kosong.');
        }

        $this->nama = $nama;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getProdiId()
    {
        return $this->prodiId;
    }

    public function setProdiId($prodiId)
    {
        $this->prodiId = $prodiId;
    }

    public function getAngkatan()
    {
        return $this->angkatan;
    }

    public function setAngkatan($angkatan)
    {
        $this->angkatan = $angkatan;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}