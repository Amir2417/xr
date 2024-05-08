<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ProPaid - Money Transfer System with User, Agent and Admin Panel</title>
    

    <style>
        body{
            background: #ffffff;
            font-family: "Outfit", sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.5em;
            color: #52526C;
            overflow-x: hidden;
            padding: 0;
            margin: 0;
        }
        *, ::after, ::before {
            box-sizing: border-box;
        }
        .page-wrapper {
            position: relative;
            min-height: 100vh;
        }
        .bg-overlay-base {
            position: relative;
            z-index: 2;
        }
        .bg_img {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat !important;
        }
        .bg-overlay-base:after {
            content: "";
            position: absolute;
            background-color: #f0eff5;
            opacity: 0.6;
            width: 100%;
            height: 100%;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: -1;
        }
        .main-wrapper{
            max-width: 1300px;
            margin: 0 auto;
            justify-content: center;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            min-height: 100vh;
        }
        .body-wrapper {
            -webkit-transition: all 0.5s;
            transition: all 0.5s;
        }
        .mb-20-none {
            margin-bottom: -20px;
        }
        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(var(--bs-gutter-y) * -1);
            margin-right: calc(var(--bs-gutter-x)/ -2);
            margin-left: calc(var(--bs-gutter-x)/ -2);
        }
        .row > * {
            position: relative;
        }
        .row>* {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: calc(1.5rem / 2);
            padding-left: calc(1.5rem / 2);
            margin-top: 0;
        }
        @media (min-width: 768px){
            .col-md-6 {
                flex: 0 0 auto;
                width: 50%;
            }
        }
        @media (min-width: 992px){
            .col-lg-4 {
                flex: 0 0 auto;
                width: 33.3333333333%;
            }
        }
        @media (min-width: 1200px){
            .col-xl-4 {
                flex: 0 0 auto;
                width: 33.3333333333%;
            }
        }
        .mb-20 {
            margin-bottom: 20px;
        }
        .pdf-logo{
            width: 270px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f7f7f7;
            border-radius: 20px 20px 0 0;
            text-align: center;
            margin: 0 auto;
        }
        .logo-wrapper{
            text-align: center;
        }
        .logo-wrapper img{
            width: 110px;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        .logo-wrapper .number{
            display: block;
            padding-top: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #000248;
        }
        .custom-card .card-body {
            background: #f7f7f7;
            padding: 30px;
            border-radius: 20px;
        }
        .preview-list-wrapper {
            background: #f0eff5;
            border-radius: 0;
            overflow: hidden;
        }
        .preview-list-wrapper .preview-list-item {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            border-bottom: 1px solid rgb(231, 232, 236);
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding: 15px;
        }
        .preview-list-user-wrapper {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }
        .preview-list-user-wrapper .preview-list-user-icon {
            width: 30px;
            height: 30px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            background-color: transparent;
            border: 1px solid #263159;
            color: #263159;
            border-radius: 50%;
            font-size: 18px;
            -webkit-transition: all 0.5s;
            transition: all 0.5s;
        }
        .preview-list-user-wrapper .preview-list-user-content {
            padding-left: 20px;
        }
        .preview-list-user-wrapper .preview-list-user-content span {
            color: #000248;
            font-weight: 400;
        }
        .preview-list-right {
            text-align: right;
            color: #000248;
            font-weight: 600;
        }
        span {
            display: inline-block;
        }
        .text--base {
            color: #263159 !important;
        }
        .table-wrapper {
            background-color: #f7f7f7;
            padding: 30px;
            border-radius: 15px;
        }
        .custom-card .table-wrapper {
            background-color: #f0eff5;
        }
        .dashboard-header-wrapper {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        h1, h2, h3, h4, h5, h6 {
            clear: both;
            line-height: 1.3em;
            color: #000248;
            -webkit-font-smoothing: antialiased;
            font-family: "Outfit", sans-serif;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 0;
        }
        .dashboard-header-wrapper .title {
            margin-bottom: 0;
        }
        h4 {
            font-size: 18px;
        }
        table {
            caption-side: bottom;
            border-collapse: collapse;
        }
        .custom-table {
            width: 100%;
            white-space: nowrap;
        }
        tbody, td, tfoot, th, thead, tr {
            border-color: inherit;
            border-style: solid;
            border-width: 0;
        }
        .custom-table thead tr {
            border-bottom: 1px solid rgb(231, 232, 236);
        }
        .custom-table thead tr th {
            border: none;
            font-weight: 600;
            color: #000248;
            font-size: 14px;
            padding: 12px 15px;
            text-align: left;
        }
        .custom-table tbody tr {
            border-bottom: 1px solid rgb(231, 232, 236);
        }
        .custom-table tbody tr td {
            border: none;
            font-weight: 500;
            color: #52526C;
            font-size: 13px;
            padding: 12px 15px;
        }
        .mt-20 {
            margin-top: 20px;
        }
        @media print{
            header{
                position: fixed;
                top: 0;
                border: none;
            }
            main{
                margin-top: 2cm;
            }
            footer{
                position: fixed;
                bottom: 0;
            }
            @page {
                size: auto;
                margin: 6.35mm;
            }
        }
    </style>
</head>
<body>
  
  


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Dashboard
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="page-wrapper bg-overlay-base bg_img" style="background-image: url(/assets/images/element/banner-bg.jpg)">
    <div class="main-wrapper">
        <div class="main-body-wrapper">
            <div class="body-wrapper p-0">
                <div class="row mb-20-none">
                    <div class="col-xl-12 col-lg-12 mb-20">
                        <div class="pdf-logo">
                            <div class="logo-wrapper">
                                <img src="../assets/images/logo/logo.png" alt="logo">
                                <span class="number">Remittance Number : 34600957</span>
                            </div>
                        </div>
                        <div class="custom-card">
                            <div class="card-body">
                                <div class="row mb-20-none">
                                    <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
                                        <div class="preview-list-wrapper">
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="las la-file-code"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>Send Amount</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span><span class="text--base">40 USD</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="las la-qrcode"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>Received Amount</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span><span class="text--base">20 EGP</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="las la-exchange-alt"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>Exchange rate</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span>1 USD = 1.00000000 EGP</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="las la-network-wired"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>Fees & Charges</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span>0.2 USD</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
                                        <div class="preview-list-wrapper">
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="las la-calendar"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>Transaction Type</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span><span class="text--base">Bank Transfer</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="las la-money-bill-wave"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>Bank Name</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span><span class="text--base">Brac Bank</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="las la-gem"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>Bank Account Number</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span>2222 0000 7878 4444</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="las la-power-off"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>Request Date</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span>13-11-2023</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
                                        <div class="preview-list-wrapper">
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="las la-calendar-check"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>Sending Purpose</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span><span class="text--base">Salary</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="las la-tachometer-alt"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>Source of Fund</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span><span class="text--base">Company</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="lab la-drupal"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>Payment Type</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span>Funds</span>
                                                </div>
                                            </div>
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="las la-window-close"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>Payment Date</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span>24-07-2024</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-area mt-20">
                                    <div class="table-wrapper">
                                        <div class="dashboard-header-wrapper">
                                            <h4 class="title">Sender</h4>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="custom-table">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Country</th>
                                                        <th>City</th>
                                                        <th>Mobile</th>
                                                        <th>Address</th>
                                                        <th>Nationality</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Sean Black</td>
                                                        <td>Ethiopia</td>
                                                        <td>Melbourne Footscray</td>
                                                        <td>+61423501764</td>
                                                        <td>13 merchant street footscray Vic 3012</td>
                                                        <td>Australian</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-area mt-20">
                                    <div class="table-wrapper">
                                        <div class="dashboard-header-wrapper">
                                            <h4 class="title">Receiver</h4>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="custom-table">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Country</th>
                                                        <th>City</th>
                                                        <th>Mobile</th>
                                                        <th>Address</th>
                                                        <th>Nationality</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Sean Black</td>
                                                        <td>Ethiopia</td>
                                                        <td>Melbourne Footscray</td>
                                                        <td>+61423501764</td>
                                                        <td>13 merchant street footscray Vic 3012</td>
                                                        <td>Australian</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Dashboard
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


</body>
</html>