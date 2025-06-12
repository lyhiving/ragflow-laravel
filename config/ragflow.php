<?php
/*
 * @Author: FutureMeng futuremeng@gmail.com
 * @Date: 2025-01-24 16:33:12
 * @LastEditors: FutureMeng futuremeng@gmail.com
 * @LastEditTime: 2025-01-24 16:53:50
 * @FilePath: /RAGFlow-laravel/config/ragflow.php
 * @Description: 
 * 
 */

return [

    /*
    |--------------------------------------------------------------------------
    | RAGFlow API Key and ENDPOINT
    |--------------------------------------------------------------------------
    |
    | Here you may specify your RAGFlow API Key and ENDPOINT. This will be
    | used to authenticate with the RAGFlow API - you can find your API key
    | on your RAGFlow dashboard, at https://ragflow.com.
    */

    'api_key' => env('RAGFLOW_API_KEY'),
    'api_endpoint' => env('RAGFLOW_ENDPOINT'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout may be used to specify the maximum number of seconds to wait
    | for a response. By default, the client will time out after 30 seconds.
    */

    'request_timeout' => env('RAGFLOW_REQUEST_TIMEOUT', 30),
];
