<?php

return array(
    // Bootstrap the configuration file with AWS specific features
    'includes' => array('_aws'),
    'class' => 'Aws\Common\Aws',
    'services' => array(
        // All AWS clients extend from 'default_settings'. Here we are
        // overriding 'default_settings' with our default credentials and
        // providing a default region setting.
        'default_settings' => array(
            'params' => array(
                'key'    => 'AKIAJ6ILL7DCV3Y2UOLA',
                'secret' => '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',
                'region' => 'ap-southeast-1' // Singapore
            )
        ),
        'Ses' => array(
            'extends' => 'default_settings',
            'class'   => 'Aws\Ses\SesClient',
            'params'  => array(
                'region' => 'us-east-1'
            )
        ),
        // 'bar.dynamodb' => array(
        //     'extends' => 'dynamodb',
        //     'params'  => array(
        //         'key'    => 'your-aws-access-key-id-for-bar',
        //         'secret' => 'your-aws-secret-access-key-for-bar',
        //         'region' => 'us-west-2'
        //     )
        // )
    )
);