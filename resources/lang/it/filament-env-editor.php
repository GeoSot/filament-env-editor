<?php

return [
    'navigation' => [
        'group' => 'System',
        'label' => 'Modifica .env',
    ],

    'page' => [
        'title' => 'Modifica .env',
    ],
    'tabs' => [
        'current-env' => [
            'title' => '.env Attivo',
        ],
        'backups' => [
            'title' => 'Backups',
        ],
    ],
    'actions' => [
        'add' => [
            'title' => 'Aggiungi una nuova chiave',
            'modalHeading' => 'Aggiungi una nuova chiave',
            'success' => [
                'title' => 'Chiave ":Name", è stata aggiunta con successo',
            ],
            'form' => [
                'fields' => [
                    'key' => 'Chiave',
                    'value' => 'Valore',
                    'index' => 'Inserisci dopo la chiave (opzionale)',
                ],
                'helpText' => [
                    'index' => 'Se vuoi inserire questa nuova voce, dopo una esistente, puoi scegliere una delle chiavi esistenti',
                ],
            ],
        ],
        'edit' => [
            'tooltip' => 'Modifica Chiave ":name"',
            'modal' => [
                'text' => 'Modifica la chiave ":name"',
            ],
        ],
        'delete' => [
            'tooltip' => 'Rimuovi Chiave ":name"',
            'confirm' => [
                'title' => 'Sei sicuro di voler rimuovere la chiave ":name"?',
            ],
        ],
        'clear-cache' => [
            'title' => 'Pulisici Cache',
            'tooltip' => 'A volte laravel memorizza in cache le variabili ENV, quindi è necessario cancellare tutte le cache ("artisan optimize:clear"), per rileggere le modifice .env',
        ],

        'backup' => [
            'title' => 'Crea un backup',
            'success' => [
                'title' => 'Backup salvato con successo',
            ],
        ],
        'download' => [
            'title' => 'Scarica il .env attuale',
            'tooltip' => 'Scarica ":name" file di backup',
        ],
        'upload-backup' => [
            'title' => 'Carica un backup',
        ],
        'show-content' => [
            'modalHeading' => 'Contenuto del backup ":name"',
            'tooltip' => 'Mostra il contenuto del backup',
        ],
        'restore-backup' => [
            'confirm' => [
                'title' => 'Stai per ripristinare ":name", al posto del file ".env" attuale. Confermi la tua scelta?',
            ],
            'modalSubmit' => 'Ripristina',
            'tooltip' => 'Ripristina ":name", come ENV corrente',
        ],
        'delete-backup' => [
            'tooltip' => 'Rimuovi il backup ":name"',
            'confirm' => [
                'title' => 'Stai per rimuovere definitivamente il backup ":name". Sei sicuro di voler procedere con questa rimozione?',
            ],
        ],
    ],
];
