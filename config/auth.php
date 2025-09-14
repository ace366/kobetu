<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | 既定のガードとパスワードブローカーを指定します。
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | それぞれのガードを定義します。学生用に "student" を追加しています。
    | driver は session、provider は下の "providers" で定義します。
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',     // 管理者/職員など
        ],

        'student' => [
            'driver'   => 'session',
            'provider' => 'students',  // 生徒用
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | 各ガードが参照するユーザープロバイダを定義します。
    | Eloquentモデルを使う場合は "eloquent"、テーブル直参照なら "database"。
    |
    | Supported: "eloquent", "database"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        'students' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Student::class,
        ],

        // "database" 例:
        // 'users' => [
        //     'driver' => 'database',
        //     'table'  => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | パスワードリセット設定。必要に応じて学生用のブローカーも用意しています。
    | テーブルは Laravel10 既定の "password_reset_tokens" を共有で使用可能です。
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,   // 分
            'throttle' => 60,   // 秒
        ],

        // 生徒用のリセットを使う場合のみ有効化
        'students' => [
            'provider' => 'students',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | パスワード再確認のタイムアウト（秒）。既定は3時間。
    |
    */

    'password_timeout' => 10800,

];
