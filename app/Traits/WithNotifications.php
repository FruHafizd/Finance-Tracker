<?php

namespace App\Traits;

trait WithNotifications
{
    /**
     * Dispatch event notifikasi untuk ditangkap oleh toast AlpineJS di app.blade.php
     *
     * @param string $title Judul notifikasi
     * @param string $message Pesan detail notifikasi
     * @param string $type Tipe notifikasi: 'success', 'danger', 'warning'
     */
    public function notify(string $title, string $message, string $type = 'success'): void
    {
        $this->dispatch('notify',
            type:    $type,
            title:   $title,
            message: $message,
        );
    }
}
