<?php

namespace AAP\Data;

class PayloadFactory
{
    /**
     * Returns an array with name of a token, changes to be applied to the
     * default token and whether or not it should be correct.
     */
    private static function getPayloadChanges()
    {
        return    [
            'There is absolutely no cause for alarm token' => [
                [], true
            ],
            'Expired token' => [
                [
                    'iat' => time() - 3600,
                    'exp' => time() - 1
                ], false
            ],
            'No expiration token' => [
                [
                    'exp' => null
                ], false
            ],
            'Invalid expiration token' => [
                [
                    'exp' => (time() + 3600) . 'a'
                ], false
            ],
            'Back to the future token' => [
                [
                    'iat' => time() + 3600,
                    'exp' => time() + 3601
                ], true
            ],
            'No issue time token' => [
                [
                    'iat' => null
                ], false
            ],
            'Invalid issue time token' => [
                [
                    'iat' => (time()) . 'a'
                ], false
            ],
            'Not ready yet token' => [
                [
                    'nbf' => time() + 3600
                ], false
            ],
            'Invalid nbf token' => [
                [
                    'nbf' => (time() - 1) . 'a'
                ], false
            ],
            'Untrusted issuer token' => [
                [
                    'iss' => 'tsi.ebi.ac.uk'
                ], true
            ],
            'No issuer token' => [
                [
                    'iss' => null
                ], true
            ],
            'Unknown audience token' => [
                [
                    'aud' => 'portal.ebi.ac.uk',
                ], false
            ],
            'Known audience token' => [
                [
                    'aud' => 'webapp.ebi.ac.uk'
                ], true
            ],
            'No subject token' => [
                [
                    'sub' => null
                ], false
            ],
            'No email token' => [
                [
                    'email' => null
                ], false
            ],
            'No name token' => [
                [
                    'name' => null
                ], false
            ],
            'No nickname token' => [
                [
                    'nickname' => null
                ], false
            ]
        ];
    }

    public static function generateSamplePayload()
    {
        return ['iat'      => time(),
                'exp'      => time() + 3600,
                'iss'      => 'aap.ebi.ac.uk',
                'sub'      => 'usr-a1d0c6e83f027327d8461063f4ac58a6',
                'email'    => 'subject@ebi.ac.uk',
                'name'     => 'John Doe',
                'nickname' => '73475cb40a568e8da8a045ced110137e159f890ac4da883b6b17dc651b3a8049',
        ];
    }

    public static function changePayload($payload, $changes)
    {
        foreach ($changes as $key => $value) {
            if (is_null($value)) {
                unset($payload[$key]);
            } else {
                $payload[$key] = $value;
            }
        }
        return $payload;
    }

    public static function generatePayloads()
    {
        return array_map(
            function($item) { return $item[0]; },
            self::generatePayloadValidities());
    }

    public static function generatePayloadValidities()
    {
        $payloads = [];
        $changes = self::getPayloadChanges();

        foreach ($changes as $name => list($changes, $valid)) {
            $payloads[$name] = [
                self::changePayload(
                    self::generateSamplePayload(),
                    $changes
                ),
                $valid
            ];
        }

        return $payloads;
    }
}
