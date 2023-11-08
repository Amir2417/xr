<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-logo">
            <a href="{{ setRoute('admin.dashboard') }}" class="sidebar-main-logo">
                <img src="{{ get_logo($basic_settings) }}" data-white_img="{{ get_logo($basic_settings) }}"
                data-dark_img="{{ get_logo($basic_settings) }}" alt="logo">
            </a>
            <button class="sidebar-menu-bar">
                <i class="fas fa-exchange-alt"></i>
            </button>
        </div>
        <div class="sidebar-user-area">
            <div class="sidebar-user-thumb">
                <a href="{{ setRoute('admin.profile.index') }}"><img src="{{ get_image(Auth::user()->image,'admin-profile','profile') }}" alt="user"></a>
            </div>
            <div class="sidebar-user-content">
                <h6 class="title">{{ Auth::user()->fullname }}</h6>
                <span class="sub-title">{{ Auth::user()->getRolesString() }}</span>
            </div>
        </div>
        @php
            $current_route = Route::currentRouteName();
        @endphp
        <div class="sidebar-menu-wrapper">
            <ul class="sidebar-menu">

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.dashboard',
                    'title'     => __("Dashboard"),
                    'icon'      => "menu-icon las la-rocket",
                ])

                {{-- Section Default --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => __("Default"),
                    'group_links'       => [
                        [
                            'title'     => __("Setup Currency"),
                            'route'     => "admin.currency.index",
                            'icon'      => "menu-icon las la-coins",
                        ],
                        [
                            'title'     => __("Fees & Charges"),
                            'route'     => "admin.trx.settings.index",
                            'icon'      => "menu-icon las la-wallet",
                        ],
                        [
                            'title'     => __("Coupons"),
                            'route'     => "admin.coupon.index",
                            'icon'      => "menu-icon las la-wallet",
                        ]
                    ]
                ])
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => __("Remittance Settings"),
                    'group_links'       => [
                        [
                            'title'     => __("Remittance Bank"),
                            'route'     => "admin.remittance.bank.index",
                            'icon'      => "menu-icon las la-university",
                        ],
                        [
                            'title'     => __("Mobile Method"),
                            'route'     => "admin.mobile.method.index",
                            'icon'      => "menu-icon las la-money-bill-alt",
                        ],
                        [
                            'title'     => __("Source of Fund"),
                            'route'     => "admin.source.fund.index",
                            'icon'      => "menu-icon las la-wallet",
                        ],
                        [
                            'title'     => __("Sending Purpose"),
                            'route'     => "admin.sending.purpose.index",
                            'icon'      => 'menu-icon las la-share-alt'
                        ]

                    ]
                ])

                @include('admin.components.side-nav.link-group',[
                    'group_title'       => __("Transactions & Logs"),
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => __("Remittance"),
                                'icon'      => "menu-icon las la-calculator",
                                'links'     => [
                                    [
                                        'title'     => __("Review Payment Logs"),
                                        'route'     => "admin.send.remittance.review.payment",
                                    ],
                                    [
                                        'title'     => __("Pending Logs"),
                                        'route'     => "admin.send.remittance.pending",
                                    ],
                                    [
                                        'title'     => __("Confirm Payment Logs"),
                                        'route'     => "admin.send.remittance.confirm.payment",
                                    ],
                                    [
                                        'title'     => __("On Hold Logs"),
                                        'route'     => "admin.send.remittance.hold",
                                    ],
                                    [
                                        'title'     => __("Settled Logs"),
                                        'route'     => "admin.send.remittance.settled",
                                    ],
                                    [
                                        'title'     => __("Complete Logs"),
                                        'route'     => "admin.send.remittance.complete",
                                    ],
                                    [
                                        'title'     => __("Canceled Logs"),
                                        'route'     => "admin.send.remittance.canceled",
                                    ],
                                    [
                                        'title'     => __("Failed Logs"),
                                        'route'     => "admin.send.remittance.failed",
                                    ],
                                    [
                                        'title'     => __("Refunded Logs"),
                                        'route'     => "admin.send.remittance.refunded",
                                    ],
                                    [
                                        'title'     => __("Delayed Logs"),
                                        'route'     => "admin.send.remittance.delayed",
                                    ],
                                    [
                                        'title'     => __("All Logs"),
                                        'route'     => "admin.send.remittance.index",
                                    ]
                                ],
                            ],
                        ],
                        [
                            'title'             => "Statements",
                            'icon'              => "menu-icon las la-sign-out-alt",
                            'route'             => "admin.statements.index",

                        ],

                    ]
                ])
                {{-- Interface Panel --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => "Interface Panel",
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => "User Care",
                                'icon'      => "menu-icon las la-user-edit",
                                'links'     => [
                                    [
                                        'title'     => "Active Users",
                                        'route'     => "admin.users.active",
                                    ],
                                    [
                                        'title'     => "Email Unverified",
                                        'route'     => "admin.users.email.unverified",
                                    ],
                                    [
                                        'title'     => "KYC Unverified",
                                        'route'     => "admin.users.kyc.unverified",
                                    ],
                                    [
                                        'title'     => "All Users",
                                        'route'     => "admin.users.index",
                                    ],
                                    [
                                        'title'     => "Email To Users",
                                        'route'     => "admin.users.email.users",
                                    ],
                                    [
                                        'title'     => "Banned Users",
                                        'route'     => "admin.users.banned",
                                    ]
                                ],
                            ],
                            [
                                'title'             => "Admin Care",
                                'icon'              => "menu-icon las la-user-shield",
                                'links'     => [
                                    [
                                        'title'     => "All Admin",
                                        'route'     => "admin.admins.index",
                                    ],
                                    [
                                        'title'     => "Admin Role",
                                        'route'     => "admin.admins.role.index",
                                    ],
                                    [
                                        'title'     => "Role Permission",
                                        'route'     => "admin.admins.role.permission.index",
                                    ],
                                    [
                                        'title'     => "Email To Admin",
                                        'route'     => "admin.admins.email.admins",
                                    ]
                                ],
                            ],
                        ],

                    ]
                ])

                {{-- Section Settings --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => "Settings",
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => "Web Settings",
                                'icon'      => "menu-icon lab la-safari",
                                'links'     => [
                                    [
                                        'title'     => "Basic Settings",
                                        'route'     => "admin.web.settings.basic.settings",
                                    ],
                                    [
                                        'title'     => "Image Assets",
                                        'route'     => "admin.web.settings.image.assets",
                                    ],
                                    [
                                        'title'     => "Setup SEO",
                                        'route'     => "admin.web.settings.setup.seo",
                                    ]
                                ],
                            ],
                            [
                                'title'             => "App Settings",
                                'icon'              => "menu-icon las la-mobile",
                                'links'     => [
                                    [
                                        'title'     => "Splash Screen",
                                        'route'     => "admin.app.settings.splash.screen",
                                    ],
                                    [
                                        'title'     => "Onboard Screen",
                                        'route'     => "admin.app.settings.onboard.screens",
                                    ],
                                    [
                                        'title'     => "App URLs",
                                        'route'     => "admin.app.settings.urls",
                                    ],
                                ],
                            ],
                        ],
                    ]
                ])

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.languages.index',
                    'title'     => "Languages",
                    'icon'      => "menu-icon las la-language",
                ])

                {{-- Verification Center --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => "Verification Center",
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => "Setup Email",
                                'icon'      => "menu-icon las la-envelope-open-text",
                                'links'     => [
                                    [
                                        'title'     => "Email Method",
                                        'route'     => "admin.setup.email.config",
                                    ],
                                ],
                            ]
                        ],

                    ]
                ])

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.setup.kyc.index',
                    'title'     => "Setup KYC",
                    'icon'      => "menu-icon las la-clipboard-list",
                ])

                @if (admin_permission_by_name("admin.setup.sections.section"))
                    <li class="sidebar-menu-header">Setup Web Content</li>
                    @php
                        $current_url = URL::current();

                        $setup_section_childs  = [
                            setRoute('admin.setup.sections.section','banner'),
                            setRoute('admin.setup.sections.section.country','country'),
                            setRoute('admin.setup.sections.section','brand'),
                            setRoute('admin.setup.sections.section','about'),
                            setRoute('admin.setup.sections.section','choose'),
                            setRoute('admin.setup.sections.section','how-its-work'),
                            setRoute('admin.setup.sections.section','feature'),
                            setRoute('admin.setup.sections.section','testimonial'),
                            setRoute('admin.setup.sections.section','journal'),
                            setRoute('admin.setup.sections.section','app-download'),
                            setRoute('admin.setup.sections.section','footer'),
                            setRoute('admin.setup.sections.section','subscribe'),
                            setRoute('admin.setup.sections.section','contact'),

                        ];
                    @endphp

                    <li class="sidebar-menu-item sidebar-dropdown @if (in_array($current_url,$setup_section_childs)) active @endif">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-terminal"></i>
                            <span class="menu-title">Setup Section</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class="sidebar-menu-item">
                                <a href="{{ setRoute('admin.setup.sections.section','banner') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','banner')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Banner Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section.country','country') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section.country','country')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Country Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','brand') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','brand')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("Brand Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','about') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','about')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __("About Section") }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','choose') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','choose')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{__("Choose Us Section")}}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','how-its-work') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','how-its-work')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{__("How Its Work Section")}}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','feature') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','feature')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{__("Feature Section")}}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','testimonial') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','testimonial')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{__("Testimonial Section")}}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','journal') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','journal')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{__("Journal Section")}}</span>
                                </a>

                                <a href="{{ setRoute('admin.setup.sections.section','app-download') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','app-download')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{__("App Download Section")}}</span>
                                </a>

                                <a href="{{ setRoute('admin.setup.sections.section','footer') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','footer')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{__("Footer Section")}}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','subscribe') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','subscribe')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{__("Subscribe Section")}}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section','contact') }}" class="nav-link @if ($current_url == setRoute('admin.setup.sections.section','contact')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{__("Contact Section")}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.subscriber.index',
                    'title'     => "Subscribe",
                    'icon'      => "menu-icon las la-bell",
                ])
                @include('admin.components.side-nav.link',[
                    'route'     => "admin.contact.index",
                    'title'     => "Contact Messages",
                    'icon'      => "menu-icon las la-sms",
                ])
                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.setup.pages.index',
                    'title'     => "Setup Pages",
                    'icon'      => "menu-icon las la-file-alt",
                ])

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.extensions.index',
                    'title'     => "Extensions",
                    'icon'      => "menu-icon las la-puzzle-piece",
                ])
                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.useful.links.index',
                    'title'     => "Useful Links",
                    'icon'      => "menu-icon las la-link",
                ])
                @if (admin_permission_by_name("admin.payment.gateway.view"))
                    <li class="sidebar-menu-header">Payment Methods</li>
                    @php
                        $payment_add_money_childs  = [
                            setRoute('admin.payment.gateway.view',['remittance-gateway','automatic']),
                            setRoute('admin.payment.gateway.view',['remittance-gateway','manual']),
                        ]
                    @endphp
                    <li class="sidebar-menu-item sidebar-dropdown @if (in_array($current_url,$payment_add_money_childs)) active @endif">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-funnel-dollar"></i>
                            <span class="menu-title">{{ __("Remittance Gateway") }}</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class="sidebar-menu-item">
                                <a href="{{ setRoute('admin.payment.gateway.view',['remittance-gateway','automatic']) }}" class="nav-link @if ($current_url == setRoute('admin.payment.gateway.view',['remittance-gateway','automatic'])) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">Automatic</span>
                                </a>
                                <a href="{{ setRoute('admin.payment.gateway.view',['remittance-gateway','manual']) }}" class="nav-link @if ($current_url == setRoute('admin.payment.gateway.view',['remittance-gateway','manual'])) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">Manual</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- Notifications --}}
                @include('admin.components.side-nav.link-group',[
                    'group_title'       => "Notification",
                    'group_links'       => [
                        'dropdown'      => [
                            [
                                'title'     => "Push Notification",
                                'icon'      => "menu-icon las la-bell",
                                'links'     => [
                                    [
                                        'title'     => "Setup Notification",
                                        'route'     => "admin.push.notification.config",
                                    ],
                                    [
                                        'title'     => "Send Notification",
                                        'route'     => "admin.push.notification.index",
                                    ]
                                ],
                            ]
                        ],

                    ]
                ])

                @php
                    $bonus_routes = [
                        'admin.server.info.index',
                        'admin.cache.clear',
                    ];
                @endphp

                @if (admin_permission_by_name_array($bonus_routes))
                    <li class="sidebar-menu-header">Bonus</li>
                @endif

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.server.info.index',
                    'title'     => "Server Info",
                    'icon'      => "menu-icon las la-sitemap",
                ])

                @include('admin.components.side-nav.link',[
                    'route'     => 'admin.cache.clear',
                    'title'     => "Clear Cache",
                    'icon'      => "menu-icon las la-broom",
                ])
            </ul>
        </div>
    </div>
</div>
