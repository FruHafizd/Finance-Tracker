<?php

return [
    'version' => '2.0.0',
    'min_compatible_version' => '2.0.0',
    
    'storage' => [
        'disk' => 'local',               // JANGAN 'public'
        'directory' => 'backups/private', // storage/app/backups/private
        'max_files_per_user' => 10,
    ],
    
    'encryption' => [
        'enabled' => true,
        'cipher' => 'aes-256-cbc',       // OpenSSL cipher
    ],
    
    'upload' => [
        'max_size_kb' => 10240,           // 10MB max upload
    ],
    
    'rate_limit' => [
        'max_backups_per_hour' => 5,
        'max_restores_per_hour' => 3,
    ],
    
    // Urutan collect & restore (penting untuk FK dependencies)
    'entities' => [
        'accounts',           // 1st — tidak ada FK ke entity lain
        'categories',         // 2nd — tidak ada FK ke entity lain
        'budgets',            // 3rd — FK: category_id
        'favorite_transactions', // 4th — FK: category_id, account_id
        'transactions',       // 5th — FK: account_id, category_id, to_account_id
        'financial_reminders', // 6th — tidak ada FK ke entity lain (bisa paralel)
        'telegram_accounts',  // 7th — tidak ada FK ke entity lain
    ],
    
    'restore' => [
        'strategy' => 'replace_all',     // 'replace_all' | 'merge'
        'auto_snapshot_before_restore' => true,
    ],
];
