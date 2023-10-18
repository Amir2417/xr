<nav class="navbar-wrapper">
    <div class="dashboard-title-part">
        <div class="left">
            <div class="icon">
                <button class="sidebar-menu-bar">
                    <i class="fas fa-exchange-alt"></i>
                </button>
            </div>
            <div class="dashboard-path">
                @yield('breadcrumb')
            </div>
        </div>
        <div class="right">
            @php
                $current_url  = URL::current();
            @endphp
            @if ($current_url == setRoute('user.transaction.index'))
                <form class="header-search-wrapper">
                    <div class="position-relative">
                        <input class="form--control" type="text" placeholder="Ex: Transactions"
                            aria-label="Search">
                        <span class="las la-search"></span>
                    </div>
                </form>
            @endif
            <div class="header-notification-wrapper">
                <button class="notification-icon">
                    <i class="las la-bell"></i>
                </button>
                <div class="notification-wrapper">
                    <div class="notification-header">
                        <h5 class="title">{{ __("Notifications") }}</h5>
                    </div>
                    <ul class="notification-list">
                        @forelse ($notifications as $item)
                            <li>
                                <div class="thumb">
                                    <img src="@if ($user->image){{ get_image($user->image ?? '', 'user-profile') ?? '' }}@else{{ asset('public/frontend/') }}/images/user/2.jpg  @endif" alt="user">
                                </div>
                                <div class="content">
                                    <div class="title-area">
                                        <h5 class="title">{{ $item->message ?? "" }}</h5>
                                        <span class="time">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$item->created_at ?? "")->format('h:i A') }}</span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <strong class="title text--danger">{{ __("Notification Not Found!") }}</strong>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="header-user-wrapper">
                <div class="header-user-thumb">
                    <a href="{{ setRoute('user.profile.index')}}"><img src="{{ auth()->user()->userImage ?? asset('public/frontend/images/client/client-3.jpg') }}"
                    alt="client"></a>
                </div>
            </div>
        </div>
    </div>
</nav>