<?php

return [
    'api_key' => env('OPENWEATHERMAP_API_KEY'),
    'services' => [
        'geocoding' => [
            'description' => [
                'baseUrl' => 'https://api.openweathermap.org/geo/1.0/',
                'operations' => [
                    'getDirect' => [
                        'httpMethod' => 'GET',
                        'uri' => 'direct',
                        'responseClass' => 'GeocodeResponse',
                        'parameters' => [
                            'q' => [
                                'type' => 'string',
                                'location' => 'query',
                            ],
                            'limit' => [
                                'type' => 'integer',
                                'location' => 'query',
                            ],
                        ],
                        'additionalParameters' => [
                            'location' => 'query',
                        ],
                    ],
                ],
                'models' => [
                    'GeocodeResponse' => [
                        'type' => 'array',
                        'location' => 'json',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'lat' => [
                                    'location' => 'json',
                                    'type' => 'numeric',
                                ],
                                'lon' => [
                                    'location' => 'json',
                                    'type' => 'numeric',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'weather' => [
            'description' => [
                'baseUrl' => 'https://api.openweathermap.org/data/2.5/',
                'operations' => [
                    'oneCall' => [
                        'httpMethod' => 'GET',
                        'uri' => 'onecall',
                        'responseClass' => 'WeatherResponse',
                        'parameters' => [
                            'lat' => [
                                'type' => 'numeric',
                                'location' => 'query',
                            ],
                            'lon' => [
                                'type' => 'numeric',
                                'location' => 'query',
                            ],
                            'exclude' => [
                                'type' => 'string',
                                'location' => 'query',
                            ],
                            'units' => [
                                'type' => 'string',
                                'location' => 'query',
                            ],
                        ],
                        'additionalParameters' => [
                            'location' => 'query',
                        ],
                    ],
                ],
                'models' => [
                    'WeatherResponse' => [
                        'type' => 'object',
                        'location' => 'json',
                        'additionalProperties' => [
                            'location' => 'json'
                        ],
                    ],
                ],
            ],
        ]
    ],
];
