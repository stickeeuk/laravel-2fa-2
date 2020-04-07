<?php

return [
    'name' => 'Google Authenticator',
    'register' => [
        'heading' => 'Enroll with Google Authenticator',
        'instructions-before' => '
            <strong>
                <ol>
                    <li>Scan the QR code or enter the code into your authenticator app</li>
                </ol>
            </strong>',

        'instructions-after' => '
            <strong>
                <ol>
                    <li>Verify the OTP from Google Authenticator Mobile App</li>
                </ol>
            </strong>',
        'code' => 'Code',
    ],
];
