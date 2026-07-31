<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background-image: url('{{ asset('public/uploads/register.jpg') }}');
        background-repeat: no-repeat;
        background-size: 100%;
        text: white;
    }


    .container .title {
        font-size: 25px;
        font-weight: 500;
        position: relative;
    }

    .container .title::before {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 3px;
        width: 30px;
        background: linear-gradient(123deg, #000000, #c93d00);
    }

    .container form .user_details {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    form .user_details .input_pox {
        margin-bottom: 15px;
        margin: 20px 0 12px 0;
        width: calc(100% / 2 - 20px);
    }

    .user_details .input_pox .datails {
        display: block;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .user_details .input_pox input {
        height: 45px;
        width: 100%;
        outline: none;
        border-radius: 5px;
        border: 1px solid #ccc;
        padding-left: 15px;
        font-size: 16px;
        border-bottom-width: 2px;
        transition: all 0.3s ease;

    }


    .user_details .input_pox input:focus,
    .user_details .input_pox input:valid {
        border-color: #c93d00;
    }

    form .gender_details .gender_title {
        font-size: 20px;
        font-weight: 500;
    }

    form .gender_details .category {
        display: flex;
        width: 80%;
        margin: 14px 0;
        justify-content: space-between;
    }

    .gender_details .category label {
        display: flex;
    }

    .gender_details .category .dot {
        height: 18px;
        width: 18px;
        background: #d9d9d9;
        border-radius: 50%;
        margin-right: 10px;
        border: 5px solid transparent;
    }

    #dot-1:checked~.category label .one,
    #dot-2:checked~.category label .two,
    #dot-3:checked~.category label .three {
        border-color: #d9d9d9;
        background-color: #c93d00;
    }


    form .button {
        height: 45px;
        margin: 45px 0;
    }

    form button {
        height: 100%;
        width: 100%;
        outline: none;
        color: #fff;
        border: none;
        font-size: 18px;
        font-weight: 500;
        border-radius: 5px;
        letter-spacing: 1px;
        background: linear-gradient(123deg, #c93d00, #c93d00);

    }

    form .button input :hover {
        background: linear-gradient(-123deg, #000000, #c93d00);
    }

    @media (max-width: 584px) {
        .container {
            max-width: 100%;
        }

        form .user_details .input_pox {
            margin-bottom: 15px;
            width: 100%;
        }

        form .gender_details .category {
            width: 100%;
        }

        .container form .user_details {
            max-height: 300px;
            overflow: scroll;
        }

        .user_details::-webkit-scrollber {
            width: 0;
        }

        .job-tag {
            max-width: 100%;
            width: 100%;
            position: absolute;
            left: 0px;
            top: -130px;
            box-shadow: inherit;
            padding: 15px;
            text-align: center;
        }

        .job-tag-right,
        .job-tag-left {
            display: none;
        }

        .job-tag .text-box h4 {
            font-size: 15px;
        }

        .job-tag .text-box span+span {
            display: none;
        }

        .job-tag .text-box a {
            font-size: 18px;
        }

    }

    .job-tag {
        background: #000;
        padding: 25px 0px;
        max-width: 90%;
        font-weight: 500;
        position: relative;
        box-shadow: -6px 4px 6px #333;
    }

    .job-tag-right {
        position: absolute;
        top: 0px;
        right: -30px;
        border-top: 55px solid #33333300;
        border-left: 30px solid #000000;
        border-right: 0px solid transparent;
        border-bottom: 55px solid #146f7900;
    }

    .job-tag-left {
        position: absolute;
        width: 100%;
        height: 100%;
        background: #000;
        left: -100%;
        top: 0px;
        box-shadow: -4px 4px 6px #333;
    }

    .job-tag .text-box {
        color: #fff;
    }

    .job-tag .text-box h4 {
        font-weight: 600;
    }

    .job-tag .text-box p {
        margin-bottom: 0px;
        ;
    }

    .job-tag .text-box a {
        color: #fcbc2d !important;
    }

    .container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        padding: 20px;

    }

    .center {
        text-align: justify center;
        font: #cc1a1a;
    }

    .card-body {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 20px;
    }

    .left-card {
        grid-column: 1 / 2;
        height: 500px;
        width: 700px;
    }

    .right-card {
        grid-column: 2 / 3;
        background: #fdfafae8;
        position: relative;

    }

    h5 {
        font-size: 100%;
        color: #ffffff;
    }
    
    h1 {
        
        color: #ff5c01c9;
    }

    .rectangle-radio {
        display: flex;
    }

    .rectangle-radio .radio-label {
        position: relative;
        margin-right: 10px;
        display: flex;
        align-items: center;
    }

    .rectangle-radio input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid #000;
        border-radius: 4px;
        outline: none;
        margin-right: 5px;
        cursor: pointer;
    }

    .rectangle-radio input[type="radio"]:checked {
        background-color: #000;
    }

    .rectangle-radio input[type="radio"]+span {
        font-family: Arial, sans-serif;
    }
.fotter_li {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed; /* Fixed position */
    left: 0; /* Span the full width of the viewport */
    bottom: 0; /* Anchor it to the bottom */
    width: 100%; /* Span the full width of the viewport */
    margin: 0;
    padding: 10px 0; /* Provide some padding above and below */
    background-color: #333; /* Assuming you want a dark background; change as needed */
    color: white; /* Light text color for contrast */
    list-style: none;
    text-align: center; /* Center the text */
    box-shadow: 0 -2px 5px rgba(0,0,0,0.2); /* Optional: Adds a subtle shadow for depth */
}

/* Style for individual list items if needed */
.fotter_li li {
    padding: 0 15px; /* Spacing between menu items */
}

/* Ensure anchor tags in the list are styled correctly */
.fotter_li li a {
    color: white; /* Light text color for contrast */
    text-decoration: none; /* Removes underline from links */
    transition: color 0.3s ease-in-out; /* Smooth transition for hover effect */
}

/* Hover state for links */
.fotter_li li a:hover {
    color: #f8f8f8; /* Slightly lighter color on hover */
}

</style>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('public/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        crossorigin="anonymous">
    <link href="/public/toastr/toastr.css" rel="stylesheet" />
    <script src="/public/toastr/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="/public/toastr/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="/public/toastr/toastr.js" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            toastr.options.timeOut = 10000;
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
                @endforeach
            @elseif (Session::has('success'))
                toastr.success('{{ Session::get('success ') }}');
            @endif
        });
    </script>
    <title>hyloship - Register</title>
</head>

<body>



    <div class="container">
        <div class="left-card">
            <div class="card-body">
                <div class="center">
                    <h1>Delivering Happiness</h1><br>
                    <h5><i class="fa-solid fa-tag"></i> &nbsp;One Dashboard Multiple Carriers</h5><br>
                    <h5><i class="fa-solid fa-location-dot"></i> &nbsp;Real Time Tracking Software</h5><br>
                    <h5><i class="fa fa-clock"></i> &nbsp;Automated COD Remittance*</h5><br>
                    <h5><i class="fa fa-headset"></i> &nbsp;Reduce RTO by more than 20%</h5><br>
                    <h5><i class="fa-sharp fa-solid fa-earth-americas"></i> &nbsp;Reduce Cart Abandon by more than 30%</h5><br>
                    <h5><i class="fa fa-circle-check"></i> &nbsp;Automated NDR Resolution System</h5><br>
                    <div class="card-body">
                        <div class="text-box">
                            <h5>For any Queries - <a href="mailto:info@hyloship.com" style="color: #ff5c01c9;">Contact Us</a></h5>
                        </div>

                    </div>
                </div>
            </div>
            <div class="login_footer">
                    <ul class="fotter_li">
            <li><a href="https://hyloship.com/about.html?12099" target="_blank">About Us</a></li>
            <li><a href="https://hyloship.com/t_c.html?12099" target="_blank">Terms and Conditions</a></li>
<!--            <li><a href="https://hyloship.com/privacy-policy.html" target="_blank">Privacy</a></li>
            <li><a href="https://hyloship.com/refunds-and-cancellations.html" target="_blank">Refunds/Cancellations</a></li>
            <li><a href="https://hyloship.com/payments.html" target="_blank">Payments</a></li>
            <li><a href="https://hyloship.com/contact-us.html" target="_blank">Contact Us</a></li>-->
        </ul>
        </div>
        </div>
        <div class="right-card">
            <div class="card-body">
                <div class="title">Registration</div>
                <form action="{{ route('admin.register.store') }}" method="POST">
                    @csrf
                    <div class="user_details">
                        <div class="rectangle-radio">
                            <label>Select your monthly shipping volume</label>
                            &nbsp;
                            &nbsp;
                            <label class="radio-label">
                                <input type="radio" name="volume" value="1-100">
                                1-100
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="volume" value="100-1000">
                                100-1000
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="volume" value="1000-10000">
                                1000-10000
                            </label>
                        </div>
                        <div class="input_pox">
                            <label class="datails">Full Name<span class="required">*</span></label>
                            <input type="text" name="name" placeholder="enter your name" required>
                        </div>
                        <div class="input_pox">
                            <span class="datails">Company Name<span class="required">*</span></span>
                            <input type="text" name="company_name" placeholder="enter your company name" required>
                        </div>
                        <div class="input_pox">
                            <span class="datails">Email<span class="required">*</span></span>
                            <input type="email" name="email" placeholder="enter your Email" required>
                        </div>
                        <div class="input_pox">
                            <span class="datails">Phone Number<span class="required">*</span></span>
                            <input type="number" name="mobile" placeholder="enter your Phone" required>
                        </div>
                        <div class="input_pox">
                            <span class="datails">Password<span class="required">*</span></span>
                            <input type="password" name="password" placeholder="enter your Password" required>
                        </div>
                        <div class="input_pox">
                            <span class="datails">Confirm Password<span class="required">*</span></span>
                            <input type="password" name="re_password" placeholder="Confirm your Password" required>
                            <input type="hidden" name="role_id" class="form-control" value="4">
                        </div>
                        <div class="input_pox">
                            <span class="datails">Address<span class="required">*</span></span>
                            <input type="textarea" name="company_address" placeholder="Address" required>
                        </div>

                    </div>
<!--                    <div class="input_pox">
                        <label for=""><input type="checkbox" name="term" required class="form-control"
                                style="width: 22px;"> By clicking, you agree to hyloship's <a href=""
                                style="text-decoration-line: none;" target="_blank">Terms Of Service</a> and <a href=""
                                style="text-decoration-line: none;" target="_blank">Policy</a>.</label>
                    </div>-->
                    <div class="button">
                        <div class="col-6"><button type="submit">Register</button></div><br>
                        <div class="col-6">Have an account already?<a href="{{ route('admin.login') }}"
                                style="text-decoration-line: none;"> Log in</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
    </div>
</body>

</html>
