<!DOCTYPE html>
<html>
<head>
<!-- <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}"> -->
    <title>Hyloship INVOICE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
            border: 1px solid;
            padding: 10px;
                }
        .col-md-6 {
            box-sizing: border-box;
            /* float: left; */
            width: 50%; /* Half the width of the container */
            /* padding-right: 15px;
            padding-left: 15px; */
        }
        .col-md-8 {
            box-sizing: border-box;
            /* float: left; */
            width: 66%; /* Half the width of the container */
            padding-right: 15px;
            padding-left: 15px;
        }

        /* Adjust the width of the column on medium-sized devices and larger */
        @media (min-width: 768px) {
            .col-md-6 {
                width: 50%; /* Half the width of the container */
            }
        }

        /* Optional: Add styles to the container and row if needed */
        .container {
            width: 100%; /* Full width */
            margin-right: auto;
            margin-left: auto;
            padding-right: 15px;
            padding-left: 15px;
        }

        .row {
            margin-right: -15px;
            margin-left: -15px;
            width: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            display: flow-root;
        }
        .title {
            font-size: 24px;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .subtitle{
            float: right;
            font-size: 14px;
            text-align: end;
        }
        .content {
            margin-bottom: 30px;
            width: 100%;
            font-size: 14px;
        }
        .hyloship_detail{
            /* float: left; */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            /* border: 1px solid #dddddd; */
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        
        .footer {
            text-align: center;
            font-style: italic;
            color: #888888;
        }


    </style>
</head>
<body>
    <div class="header">
    <div class="title" style="padding-right: 10px;">@if($invoice->invoice_type =='c') Credit note @elseif($invoice->invoice_type =='d') Debit note @else TAX INVOICE @endif</div>
    <table>
        <tbody>
            <tr>
                <td>
                    <img src="https://hyloship.com/public/hyloshiplogo.png" alt="hyloship Logo"
                                    class="img-responsive logo" style="margin-top:-8px !important;width:180px;">
                </td>
            
                <td>
                    <div class="subtitle">
                        <b>@if($invoice->invoice_type =='c') Credit NO @elseif($invoice->invoice_type =='d') Credit NO @else INVOICE NO @endif</b> {{$invoice->invoice_id}}<br>
                        <b>@if($invoice->invoice_type =='n')Invoice @endif Date</b> :{{$invoice->invoice_date}}<br>
                        <b>@if($invoice->invoice_type =='n')Invoice @endif Period</b> :{{$invoice->start_date}} - {{$invoice->end_date}}<br>
                        <b>Ref. No.</b> :{{$invoice->id}}
                        
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    </div>
    <div class="content">
        <div class="card-body">
        <table>
        <tbody>
                    <tr>
                        <td>
                            <b>From:</b> Hyloship Private Limited<br>
                            
                            <b>GSTIN:</b>
                        </td>
                        <td> 
                            <div class="hyloship_detail" style="text-align: right;width: 72%;float: right;">
                                <b>To:</b>{{$invoice->company_name}}<br>
                                {{$invoice->billing_address}}<br>
                                <b>GSTIN:</b> {{$invoice->gst}}<br><!-- comment -->
                                <b>Place of supply:</b> {{$invoice->place_supply}}<br>
                                <b>State Code:</b> {{$invoice->state_code}}
                            </div>
                        </td>
                            
                    </tr>
        </table>
                    
            
            <br>
            
            <div class="table-responsive" style="">
                <table class="table table-striped table-hover dataTable js-exportable">
                    <thead>

                        
                    </thead>
                    <tbody>
                    <tr>
                            <th>Item Description</th>
                            <th>HSN</th>
                            <th>Amount</th>
                            
                        </tr>
                            <tr>
                                <td>Shipping Services</td>
                                <td>996719</td>
                                <td><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQXoRKtKev3knB2p9rfjZ-WnXfpkRaVepw9qw&amp;s" alt="hyloship Logo" class="img-responsive logo" style="/* margin-top:-8px !important; */width: 7px;margin-right: 2px;">{{round($invoice->subtotal,2)}}</td>
                                
                            </tr>
                            <tr>
                                <td>IGST @ 18%</td>
                                <td></td>
                                <td><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQXoRKtKev3knB2p9rfjZ-WnXfpkRaVepw9qw&amp;s" alt="hyloship Logo" class="img-responsive logo" style="/* margin-top:-8px !important; */width: 7px;margin-right: 2px;">{{round($invoice->igst,2)}}</td>
                                
                            </tr>
                            <tr>
                                <td>SGST @ 9%</td>
                                <td></td>
                                <td><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQXoRKtKev3knB2p9rfjZ-WnXfpkRaVepw9qw&amp;s" alt="hyloship Logo" class="img-responsive logo" style="/* margin-top:-8px !important; */width: 7px;margin-right: 2px;">{{round($invoice->sgst,2)}}</td>
                                
                            </tr>
                            <tr>
                                <td>CGST @ 9%</td>
                                <td></td>
                                <td><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQXoRKtKev3knB2p9rfjZ-WnXfpkRaVepw9qw&amp;s" alt="hyloship Logo" class="img-responsive logo" style="/* margin-top:-8px !important; */width: 7px;margin-right: 2px;">{{round($invoice->cgst,2)}}</td>
                                
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Total Amount: <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQXoRKtKev3knB2p9rfjZ-WnXfpkRaVepw9qw&amp;s" alt="hyloship Logo" class="img-responsive logo" style="/* margin-top:-8px !important; */width: 7px;margin-right: 2px;">{{round($invoice->total,2)}}</td>
                                
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <?php  $rf = round($invoice->total)-round($invoice->total,2);
                                
                                ?>
                                <td>Round-off: <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQXoRKtKev3knB2p9rfjZ-WnXfpkRaVepw9qw&amp;s" alt="hyloship Logo" class="img-responsive logo" style="/* margin-top:-8px !important; */width: 7px;margin-right: 2px;">{{round($rf,2)}}</td>
                                
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td><b>Final Amount: <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQXoRKtKev3knB2p9rfjZ-WnXfpkRaVepw9qw&amp;s" alt="hyloship Logo" class="img-responsive logo" style="/* margin-top:-8px !important; */width: 7px;margin-right: 2px;">{{round($invoice->total)}}</b></td>
                                
                            </tr>
                      
                    </tbody>
                </table>
                <?php echo 'Amount in words: <b>'. ucwords(getIndianCurrency(round($invoice->total))).' Only </b>'; ?>
            </div>
            <?php 
            function getIndianCurrency(float $number)
            {
                $decimal = round($number - ($no = floor($number)), 2) * 100;
                $hundred = null;
                $digits_length = strlen($no);
                $i = 0;
                $str = array();
                $words = array(0 => '', 1 => 'one', 2 => 'two',
                    3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
                    7 => 'seven', 8 => 'eight', 9 => 'nine',
                    10 => 'ten', 11 => 'eleven', 12 => 'twelve',
                    13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
                    16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
                    19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
                    40 => 'forty', 50 => 'fifty', 60 => 'sixty',
                    70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
                $digits = array('', 'hundred','thousand','lakh', 'crore');
                while( $i < $digits_length ) {
                    $divider = ($i == 2) ? 10 : 100;
                    $number = floor($no % $divider);
                    $no = floor($no / $divider);
                    $i += $divider == 10 ? 1 : 2;
                    if ($number) {
                        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                        $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
                    } else $str[] = null;
                }
                $Rupees = implode('', array_reverse($str));
                $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
                return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
            }
            ?>
            <br>
            <span style="font-size: 12px;">Kindly remit the net payable amount to the below mentioned
            account (Ignore if already paid):</span><br>
            <div class="row">
                <div class="col-md-8">
                    <b>Bank Details:</b><br>
                    <b>Name:</b> Hyloship Private Limited<br>
                    <b>Bank:</b> HDFC Bank<br>
                    <b>Account No.:</b> <br>
                    <b>IFSC Code:</b> <br>
                    <b>Branch:</b>
                </div>
                <div class="col-md-4">

                </div>
            </div>
            <br>
            
        </div>
    </div>
    <div class="footer">
    <!--<b>Regd. Office:</b> C-586 F/F, Front Side Flat No. 6, Paryavaran Complex,South West Delhi, Delhi, 110030<br>-->
    This is a computer generated invoice no signature is required<br>
        Generated by hyloship on {{ date('Y-m-d H:i:s') }}
    </div>
</body>
</html>
