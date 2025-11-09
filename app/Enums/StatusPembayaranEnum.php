<?php

namespace App\Enums;

enum StatusPembayaranEnum: string
{
    case MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    case DITERIMA = 'diterima';
    case DITOLAK = 'ditolak';
}