<?php

return [
    'navigation' => [
        'group' => 'システム',
        'label' => '.Env エディター',
    ],

    'page' => [
        'title' => '.Env エディター',
    ],
    'tabs' => [
        'current-env' => [
            'title' => '現状の.env',
        ],
        'backups' => [
            'title' => 'バックアップ',
        ],
    ],
    'actions' => [
        'add' => [
            'title' => '新規エントリーの追加',
            'modalHeading' => '新規エントリーの追加',
            'success' => [
                'title' => 'キー ":Name", が正常に書き込まれました',
            ],
            'form' => [
                'fields' => [
                    'key' => 'キー',
                    'value' => '値',
                    'index' => '既存のキーの後に挿入（オプション）',
                ],
                'helpText' => [
                    'index' => '既存のエントリーの後に新しいエントリーを追加する必要がある場合は、既存のキーの1つを選択できます',
                ],
            ],
        ],
        'edit' => [
            'tooltip' => 'エントリー　":name"　の編集',
            'modal' => [
                'text' => 'エントリーの編集',
            ],
        ],
        'delete' => [
            'tooltip' => '":name" エントリーの削除',
            'confirm' => [
                'title' => '":name" を完全に削除します。本当に削除されますか？',
            ],
        ],
        'clear-cache' => [
            'title' => 'キャッシュのクリア',
            'tooltip' => '時々、Laravel は ENV 変数をキャッシュするため、.env の変更を再読み込みするためにすべてのキャッシュをクリアする必要があります',
        ],

        'backup' => [
            'title' => '新しいバックアップの作成',
            'success' => [
                'title' => 'バックアップが正常に作成されました',
            ],
        ],
        'download' => [
            'title' => 'バックアップのダウンロード',
            'tooltip' => '":name" バックアップファイルをダウンロード',
        ],
        'upload-backup' => [
            'title' => 'バックアップファイルのアップロード',
        ],
        'show-content' => [
            'modalHeading' => '":name" の詳細を確認',
            'tooltip' => '詳細を表示',
        ],
        'restore-backup' => [
            'confirm' => [
                'title' => '":name" を現在の ENV として復元しようとしています。本当に復元しますか？',
            ],
            'modalSubmit' => '復元',
            'tooltip' => '":name" を現在の ENV として復元',
        ],
        'delete-backup' => [
            'tooltip' => '":name" バックアップファイルの削除',
            'confirm' => [
                'title' => '":name" バックアップファイルを完全に削除します。本当に削除しますか？',
            ],
        ],
    ],
];
