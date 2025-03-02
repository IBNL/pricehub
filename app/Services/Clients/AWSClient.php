<?php

namespace App\Services\Clients;

use Aws\Sqs\SqsClient;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;


class AWSClient
{
    protected $awsClient;

    public function __construct()
    {
        $credentials = new Credentials(
            env('AWS_ACCESS_KEY_ID'),
            env('AWS_SECRET_ACCESS_KEY')
        );

        $this->awsClient = new \Aws\Sdk([
            'region'   => env('AWS_DEFAULT_REGION'),
            'version'  => 'latest',
            'credentials' => $credentials
        ]);
    }

    public function getSqsClient(): SqsClient
    {
        return $this->awsClient->createSqs();
    }

    public function getS3Client(): S3Client
    {
        return $this->awsClient->createS3();
    }
}
