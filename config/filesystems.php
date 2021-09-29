<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        // 'sftp-facturas' => [
        //     'driver' => 'sftp',
        //     'host' => 'ec2-34-227-90-217.compute-1.amazonaws.com',
        //     'username' => 'ubuntu',
        //     'port' => 22,
        //     'privateKey' => base_path('cz_new.ppk'),
        //     'root' => '/home/ubuntu/s3-bucket1/facturas',
        // ],

        // 'sftp-complementos' => [
        //     'driver' => 'sftp',
        //     'host' => 'ec2-34-227-90-217.compute-1.amazonaws.com',
        //     'username' => 'ubuntu',
        //     'port' => 22,
        //     'privateKey' => base_path('cz_new.ppk'),
        //     'root' => '/home/ubuntu/s3-bucket1/complementos',
        // ],

        // 'sftp-ordenes' => [
        //     'driver' => 'sftp',
        //     'host' => 'ec2-34-227-90-217.compute-1.amazonaws.com',
        //     'username' => 'ubuntu',
        //     'port' => 22,
        //     'privateKey' => base_path('cz_new.ppk'),
        //     'root' => '/home/ubuntu/s3-bucket1/ordenes',
        // ],

        // 'sftp-estados-de-cuentas' => [
        //     'driver' => 'sftp',
        //     'host' => 'ec2-34-227-90-217.compute-1.amazonaws.com',
        //     'username' => 'ubuntu',
        //     'port' => 22,
        //     'privateKey' => base_path('cz_new.ppk'),
        //     'root' => '/home/ubuntu/s3-bucket1/estados_de_cuentas',
        // ],

        // 'sftp-pagos' => [
        //     'driver' => 'sftp',
        //     'host' => 'ec2-34-227-90-217.compute-1.amazonaws.com',
        //     'username' => 'ubuntu',
        //     'port' => 22,
        //     'privateKey' => base_path('cz_new.ppk'),
        //     'root' => '/home/ubuntu/s3-bucket1/pagos',
        // ],

        'sftp-facturas' => [
            'driver' => 'local',
            'root' => '../FTP/facturas',
        ],

        'sftp-complementos' => [
            'driver' => 'local',
            'root' => '../FTP/complementos',
        ],

        'sftp-ordenes' => [
            'driver' => 'local',
            'root' => '../FTP/ordenes',
        ],

        'sftp-estados-de-cuentas' => [
            'driver' => 'local',
            'root' => '../FTP/estados_de_cuentas',
        ],

        'sftp-pagos' => [
            'driver' => 'local',
            'root' => '../FTP/pagos',
        ],

        'sftp-pagos-proveedores' => [
            'driver' => 'local',
            'root' => '../FTP/pagos-proveedores',
        ],

    ],

];
