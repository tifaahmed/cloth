<?php

namespace App\Enums;

use Illuminate\Support\Facades\Auth;
use Spatie\Enum\Enum;
use ReflectionClass;

final class JobTypeEnums extends Enum
{
    public static function all(): array
    {
        return [
            3 => [
                'id' => 3,
                'name' => 'Driver',
                'role' => 'driver',
                'is_able_view_all_orders' => 0,
                'view_only_order_statuses' => [
                    // delivered
                    3 => [
                        'change_status' => []
                    ],
                    // done
                    6 => [
                        'change_status' => [
                            7  // in_delivery,
                        ]
                    ],
                    // in_delivery,
                    7 => [
                        'change_status' => [
                            8, // rejected
                            3 // delivered
                        ]
                    ],
                ]
            ],
            5 => [
                'id' => 5,
                'name' => 'Cashier',
                'role' => 'cashier',
                'view_only_order_statuses' => [
                    // pending
                    1 => [
                        'change_status' => [
                            5, //confirmed
                            8 //rejected
                        ]
                    ],
                    // processing
                    2 => [
                        'change_status' => [
                            8 //rejected
                        ],
                    ],
                    // delivered
                    3 => [
                        'change_status' => [],
                    ],
                    // cancelled
                    4 => [
                        'change_status' => [],
                    ],
                    // confirmed
                    5 => [
                        'change_status' => [
                            8 //rejected
                        ],
                    ],
                    // done
                    6 => [
                        'change_status' => [
                            8 //rejected
                        ]
                    ],
                    // in_delivery,
                    7 => [
                        'change_status' => [
                            8, // rejected
                        ]
                    ],
                ]
            ],
            6 => [
                'id' => 6,
                'name' => 'Shife',
                'role' => 'shife',
                'view_only_order_statuses' => [

                    // processing
                    2 => [
                        'change_status' => [
                            6, //done
                            8, //rejected
                        ],
                    ],
                    // confirmed
                    5 => [
                        'change_status' => [
                            2, // processing
                            8 //rejected
                        ],
                    ],
                    // done
                    6 => [
                        'change_status' => []
                    ],
                ]
            ],
            7 => [
                'id' => 7,
                'name' => 'Waiter',
                'role' => 'waiter',
                'view_only_order_statuses' => [
                    // pending
                    1 => [
                        'change_status' => [
                            8 //rejected
                        ]
                    ],
                    // processing
                    2 => [
                        'change_status' => [
                            8 //rejected
                        ],
                    ],
                    // delivered
                    3 => [
                        'change_status' => [],
                    ],
                    // cancelled
                    4 => [
                        'change_status' => [],
                    ],
                    // confirmed
                    5 => [
                        'change_status' => [
                            8 //rejected
                        ],
                    ],
                    // done
                    6 => [
                        'change_status' => [
                            3 //delivered
                        ]
                    ],
                ]
            ],
            8 => [
                'id' => 8,
                'name' => 'call center',
                'role' => 'call center',
                'view_only_order_statuses' => [
                    // pending
                    1 => [
                        'change_status' => [
                            5, //confirmed
                            8 //rejected
                        ]
                    ],
                    // processing
                    2 => [
                        'change_status' => [
                            8 //rejected
                        ],
                    ],
                    // delivered
                    3 => [
                        'change_status' => [],
                    ],
                    // cancelled
                    4 => [
                        'change_status' => [],
                    ],
                    // confirmed
                    5 => [
                        'change_status' => [
                            8 //rejected
                        ],
                    ],
                    // done
                    6 => [
                        'change_status' => [
                            8 //rejected
                        ]
                    ],
                    // in_delivery,
                    7 => [
                        'change_status' => [
                            8, // rejected
                        ]
                    ],
                ]
            ],
            9 => [
                'id' => 9,
                'name' => 'branch admin',
                'role' => 'branch admin',
                'view_only_order_statuses' => [
                    // pending
                    1 => [
                        'change_status' => [
                            5, //confirmed
                            8 //rejected
                        ]
                    ],
                    // processing
                    2 => [
                        'change_status' => [
                            8 //rejected
                        ],
                    ],
                    // delivered
                    3 => [
                        'change_status' => [],
                    ],
                    // cancelled
                    4 => [
                        'change_status' => [],
                    ],
                    // confirmed
                    5 => [
                        'change_status' => [
                            8 //rejected
                        ],
                    ],
                    // done
                    6 => [
                        'change_status' => [
                            8 //rejected
                        ]
                    ],
                    // in_delivery,
                    7 => [
                        'change_status' => [
                            8, // rejected
                        ]
                    ],
                ]
            ],
        ];
    }

    public static function getRoleById($key)
    {
        $array = static::all();
        if (isset($array[$key])) {
            return $array[$key]['role'];
        }
    }
}
