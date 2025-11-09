<?php

namespace App\Enums;

enum StatusPesananEnum: string
{
    case MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    case MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    case DIPROSES = 'diproses';
    case DIKIRIM = 'dikirim';
    case SELESAI = 'selesai';
    case DIBATALKAN = 'dibatalkan';
}