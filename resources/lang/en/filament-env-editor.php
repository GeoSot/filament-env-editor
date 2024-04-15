<?php

return [
    'navigation' => [
        'group' => 'System',
        'label' => '.Env Editor',
    ],

    'page' => [
        'title' => '.Env Editor',
    ],
    'tabs' => [
        'current-env' => [
            'title' => 'Current .env',
        ],
        'backups' => [
            'title' => 'Backups',
        ],
    ],
    'actions' => [
        'add' => [
            'title' => 'Add new Entry',
            'modalHeading' => 'Add new Entry',
            'success' => [
                'title' => 'Key ":Name", was successfully written',
            ],
            'form' => [
                'fields' => [
                    'key' => 'key',
                    'value' => 'value',
                    'index' => 'Insert After existing key (optional)',
                ],
                'helpText' => [
                    'index' => 'In case you need to put this new entry, after an existing one, you may pick one of the existing key ',
                ],
            ],
        ],
        'edit' => [
            'tooltip' => 'Edit Entry ":name"',
            'modal' => [
                'text' => 'Edit Entry',
            ],
        ],
        'delete' => [
            'tooltip' => 'Remove the ":name" entry',
            'confirm' => [
                'title' => 'You are going to permanently remove ":name". Are you sure of this removal?',
            ],
        ],
        'clear-cache' => [
            'title' => 'Clear caches',
            'tooltip' => 'Sometimes laravel caches ENV variables, so you need to clear all caches ("artisan optimize:clear"), in order to rer-ead the .env change',
        ],

        'backup' => [
            'title' => 'Create a new Backup',
            'success' => [
                'title' => 'Backup, was successfully created',
            ],
        ],
        'download' => [
            'title' => 'Download current .env',
            'tooltip' => 'Download ":name" backup file',
        ],
        'upload-backup' => [
            'title' => 'Upload a backup file',
        ],
        'show-content' => [
            'modalHeading' => 'Raw content of ":name" backup',
            'tooltip' => 'Show raw content',
        ],
        'restore-backup' => [
            'confirm' => [
                'title' => 'You are going to restore ":name", in place of current ".env" file. Please confirm you choice',
            ],
            'modalSubmit' => 'Restore',
            'tooltip' => 'Restore ":name", as current ENV',
        ],
        'delete-backup' => [
            'tooltip' => 'Remove the ":name" backup file',
            'confirm' => [
                'title' => 'You are going to permanently remove ":name" backup file. Are you sure of this removal?',
            ],
        ],
    ],
];
