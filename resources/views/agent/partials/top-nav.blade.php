<nav class="navbar-wrapper">
    <div class="dashboard-title-part">
        <div class="left">
            <div class="icon">
                <button class="sidebar-menu-bar">
                    <i class="fas fa-exchange-alt"></i>
                </button>
            </div>
            @yield('breadcrumb')
        </div>
        <div class="right">
            <form class="header-search-wrapper">
                <div class="position-relative">
                    <input class="form--control" type="text" placeholder="{{ __('Ex: Transactions') }}"
                        aria-label="Search">
                    <span class="las la-search"></span>
                </div>
            </form>
            <div class="header-notification-wrapper">
                <button class="notification-icon">
                    <i class="las la-bell"></i>
                </button>
                <div class="notification-wrapper">
                    <div class="notification-header">
                        <h5 class="title">Notification</h5>
                    </div>
                    <ul class="notification-list">
                        <li>
                            <div class="thumb">
                                <img src="" alt="user">
                            </div>
                            <div class="content">
                                <div class="title-area">
                                    <h5 class="title">Send Remittance</h5>
                                    <span class="time">Thu 3.30PM</span>
                                </div>
                                <span class="sub-title">Hi, How are you? What about our next
                                    meeting</span>
                            </div>
                        </li>
                        
                    </ul>
                </div>
            </div>
            <div class="header-user-wrapper">
                <div class="header-user-thumb">
                    <a href="{{ setRoute('agent.profile.index') }}"><img src="{{ auth()->user()->agentImage }}"
                            alt="client"></a>
                </div>
            </div>
        </div>
    </div>
</nav>