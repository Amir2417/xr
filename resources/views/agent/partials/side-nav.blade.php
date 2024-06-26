<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-area">
            <div class="sidebar-logo">
                <a href="{{ setRoute('index') }}" class="sidebar-main-logo">
                    <img src="{{ get_logo_agent($basic_settings) }}" data-white_img="{{ get_logo_agent($basic_settings,"dark") }}"
                    data-dark_img="{{ get_logo_agent($basic_settings) }}" alt="logo">
                </a>
                <button class="sidebar-menu-bar">
                    <i class="fas fa-exchange-alt"></i>
                </button>
            </div>
            <div class="sidebar-menu-wrapper">
                <ul class="sidebar-menu">
                    <li class="sidebar-menu-item">
                        <a href="{{ setRoute('agent.dashboard') }}">
                            <i class="menu-icon las la-palette"></i>
                            <span class="menu-title">{{ __("Dashboard") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="send-remittance.html">
                            <i class="menu-icon las la-fax"></i>
                            <span class="menu-title">{{ __("Send Remittance") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="money-in.html">
                            <i class="menu-icon las la-cloud-upload-alt"></i>
                            <span class="menu-title">{{ __("Money In") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="money-out.html">
                            <i class="menu-icon las la-plus-square"></i>
                            <span class="menu-title">{{ __("Money Out") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="my-sender.html">
                            <i class="menu-icon las la-share-square"></i>
                            <span class="menu-title">{{ __("My Sender") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="my-recipient.html">
                            <i class="menu-icon las la-user-plus"></i>
                            <span class="menu-title">{{ __("My Recipient") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="profit-log.html">
                            <i class="menu-icon las la-wallet"></i>
                            <span class="menu-title">{{ __("Profit Log") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="transaction.html">
                            <i class="menu-icon las la-history"></i>
                            <span class="menu-title">{{ __("Transactions") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="statements.html">
                            <i class="menu-icon las la-file-alt"></i>
                            <span class="menu-title">{{ __("Statement") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="{{ setRoute('agent.security.kyc.index') }}">
                            <i class="menu-icon las la-user-shield"></i>
                            <span class="menu-title">{{ __("KYC") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="{{ setRoute('agent.security.google.2fa') }}">
                            <i class="menu-icon las la-lock"></i>
                            <span class="menu-title">{{ __("2FA Security") }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="javascript:void(0)" class="logout-btn">
                            <i class="menu-icon las la-sign-out-alt"></i>
                            <span class="menu-title">{{ __("Logout") }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="sidebar-doc-box bg_img" data-background="{{ asset('public/frontend') }}/images/element/side-bg.webp">
            <div class="sidebar-doc-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <div class="sidebar-doc-content">
                <h4 class="title">{{ __("Need Help?") }}</h4>
                <p>{{ __("How can we help you") }}</p>
                <div class="sidebar-doc-btn">
                    <a href="support-tickets.html" class="btn--base w-100">{{ __("Get Support") }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(".logout-btn").click(function(){
        
        var actionRoute =  "{{ setRoute('agent.logout') }}";
        var target      = 1;
        var message     = `{{ __("Are you sure to") }} <strong>{{ __("Logout") }}</strong>?`;

        openAlertModal(actionRoute,target,message,"{{ __('Logout') }}","POST");
    });
</script>
@endpush
