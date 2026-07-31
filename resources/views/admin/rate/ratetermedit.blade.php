@extends('admin.admin_layouts')
@section('admin_content')
    @php
        $session = Auth::guard('admin')->user();
    @endphp
    <style>
        ul.a {
            list-style-type: circle;
        }

        .container {
            max-width: 900px;
            width: 100%;
            background-color: #fff;
            margin: auto;
            padding: 15px;
            box-shadow: 0 2px 20px #0001, 0 1px 6px #0001;
            brate-radius: 5px;
            overflow-x: auto;
        }

        ._table {
            width: 100%;
            brate-collapse: collapse;
        }

        ._table :is(th, td) {
            brate: 1px solid #0002;
            padding: 8px 10px;
        }

        /* form field design start */
        .form_control {
            brate: 1px solid #0002;
            background-color: transparent;
            outline: none;
            padding: 8px 12px;
            font-family: 1.2rem;
            width: 100%;
            color: #333;
            font-family: Arial, Helvetica, sans-serif;
            transition: 0.3s ease-in-out;
        }

        .form_control::placeholder {
            color: inherit;
            opacity: 0.5;
        }

        .form_control:is(:focus, :hover) {
            box-shadow: inset 0 1px 6px #0002;
        }

        /* form field design end */


        .success {
            background-color: #24b96f !important;
        }

        .warning {
            background-color: #ebba33 !important;
        }

        .primary {
            background-color: #259dff !important;
        }

        .secondery {
            background-color: #00bcd4 !important;
        }

        .danger {
            background-color: #ff5722 !important;
        }

        .action_container {
            display: inline-flex;
        }

        .action_container>* {
            brate: none;
            outline: none;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            padding: 8px 14px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        .action_container>*+* {
            brate-left: 1px solid #fff5;
        }

        .action_container>*:hover {
            filter: hue-rotate(-20deg) brightness(0.97);
            transform: scale(1.05);
            brate-color: transparent;
            box-shadow: 0 2px 10px #0004;
            brate-radius: 2px;
        }

        .action_container>*:active {
            transition: unset;
            transform: scale(.95);
        }

        @media only screen and (max-width: 600px) {
            .responsive-iframe {
                top: 0;
                left: 0;
                bottom: 0;
                right: 0;
                width: 100%;
                height: 200px;
                brate: none;
            }

            .rounded-circle {
                width: 48px !important;
            }

            ul {
                line-height: 162%;
                width: 220px;
                font-size: 11px;
            }
        }
    </style>


    <div class="block-header">
        <div class="row">
            <div class="col-lg-5 col-md-8 col-sm-12">
                <h2>Terms and condition Edit</h2>
            </div>
            <div class="col-lg-7 col-md-4 col-sm-12 text-right">
                <ul class="breadcrumb justify-content-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="icon-home"></i></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <form id="form_submit" action="{{ route('admin.rate.termupdate',$term->id) }}" method="POST">
            @csrf
            <div class="col-12">
                <div class="card pt-30">
                    <div class="card-header">
                        <h5 class="card-title mb-0"></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-12 ">
                                <label>Terms</label><span class="required"> *</span>
                                <input class="form-control" type="text" name="terms" required value="{{ $term->terms }}">
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-control-label">Conditions</label><span class="required"> *</span>
                                <input class="form-control" type="text" name="conditions" required value="{{ $term->conditions }}">
                            </div>
                            <div class="form-group col-md-5">
                                <button type="submit">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


@endsection
