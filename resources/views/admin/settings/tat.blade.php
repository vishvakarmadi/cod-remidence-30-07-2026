@extends('admin.admin_layouts')
@section('admin_content')
<style>
    td {
    padding: 5px;
}
</style>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                
                <div class="card-body">
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><strong><u><span style="font-size:19px;color:#0D0D0D;">TAT FOR FORWARD, RTO, POD &amp; DISPUTES</span></u></strong></p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><strong><u><span style="font-size:19px;color:#44546A;"><span style="text-decoration:none;">&nbsp;</span></span></u></strong></p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>&nbsp;</p>
                        <table style="border-collapse:collapse;border:none;">
                            <tbody>
                                <tr>
                                    <td style="width: 58.2pt;border: 1pt solid black;height: 45.35pt;vertical-align: top;">
<!--                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">&nbsp;</span></strong></p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">&nbsp;</span></strong></p>-->
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">Courier</span></strong></p>
                                    </td>
                                    <td style="width: 78.6pt;border-top: 1pt solid black;border-right: 1pt solid black;border-bottom: 1pt solid black;border-image: initial;border-left: none;height: 45.35pt;vertical-align: top;">
                                        <!--<p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">&nbsp;</span></strong></p>-->
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">Type of dispute</span></strong></p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: 1pt solid black;border-right: 1pt solid black;border-bottom: 1pt solid black;border-image: initial;border-left: none;height: 45.35pt;vertical-align: top;">
                                        <!--<p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">&nbsp;</span></strong></p>-->
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">TAT for raising</span></strong><strong><span style="background:yellow;">&nbsp;</span><span style="background:yellow;">&nbsp;dipsut</span></strong><strong><span style="background:  yellow;">e</span></strong></p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: 1pt solid black;border-right: 1pt solid black;border-bottom: 1pt solid black;border-image: initial;border-left: none;height: 45.35pt;vertical-align: top;">
                                        <!--<p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">&nbsp;</span></strong></p>-->
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">Closure TAT</span></strong></p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: 1pt solid black;border-right: 1pt solid black;border-bottom: 1pt solid black;border-image: initial;border-left: none;height: 45.35pt;vertical-align: top;">
                                        <!--<p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">&nbsp;</span></strong></p>-->
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">Process</span></strong></p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: 1pt solid black;border-right: 1pt solid black;border-bottom: 1pt solid black;border-image: initial;border-left: none;height: 45.35pt;vertical-align: top;">
                                        <!--<p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">&nbsp;</span></strong></p>-->
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;text-align:center;'><strong><span style="background:yellow;">proofs required</span></strong></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="4" style="width: 58.2pt;border-right: 1pt solid black;border-bottom: 1pt solid black;border-left: 1pt solid black;border-image: initial;border-top: none;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>DTDC</p>
                                    </td>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>seller has to accept the shipment &amp; mentioned remarks on POD</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>360 degree opening footage, POD remarks</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Label/product images of delivered shipment &amp; customer VOC</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="5" style="width: 58.2pt;border-right: 1pt solid black;border-bottom: 1pt solid black;border-left: 1pt solid black;border-image: initial;border-top: none;height: 39.3pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Ecom</p>
                                    </td>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 39.3pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 39.3pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 39.3pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 39.3pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>If seller accept the shipment , he need to mentioned remarks on POD or if it is OTP based delivery seller needs to select dispute on FE device or else seller can also reject the shipment &amp; raise ticket</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 39.3pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>360 degree opening footage, POD remarks(if its is not OTP based RTO delivery) &amp; packaging video</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Packaging video, Label/product images of delivered shipment &amp; customer VOC/ In case of OTP delivery- no escalation will be entertained</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 43.35pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Reverse QC dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 43.35pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 43.35pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 43.35pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 43.35pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>360 degree opening footage, POD remarks</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="5" style="width: 58.2pt;border-right: 1pt solid black;border-bottom: 1pt solid black;border-left: 1pt solid black;border-image: initial;border-top: none;height: 94.8pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>XPRESSBEES</p>
                                    </td>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 94.8pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 94.8pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 94.8pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 94.8pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>seller has to accept the shipment &amp; mentioned remarks on POD &amp; in case if packet is tampered/XB tape then seller has to reject the shipment &amp; raise he ticket with images</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 94.8pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>If seller accept the shipment then he need to raise ticket with 360 degree opening footage &amp; POD remarks</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 4.75pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 4.75pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 4.75pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 4.75pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 4.75pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Packaging video, Label/product images of delivered shipment &amp; customer VOC/ In case of OTP delivery- no escalation will be entertained</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Reverse QC dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>360 degree opening footage, POD remarks &amp; flyer barcode</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="4" style="width: 58.2pt;border-right: 1pt solid black;border-bottom: 1pt solid black;border-left: 1pt solid black;border-image: initial;border-top: none;height: 38.7pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Ekart</p>
                                    </td>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 38.7pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 38.7pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 38.7pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 38.7pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>seller has to accept the shipment &amp; mentioned remarks on POD</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 38.7pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>360 degree opening footage, POD remarks &amp; packaging video is mandatory</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward dispute will not be enertained in Ekart/100% OTP based delivery in PPD</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="5" style="width: 58.2pt;border-right: 1pt solid black;border-bottom: 1pt solid black;border-left: 1pt solid black;border-image: initial;border-top: none;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Delhivery</p>
                                    </td>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>seller has to accept the shipment &amp; mentioned remarks on POD</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>360 degree opening footage, POD remarks &amp; packaging video</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Packaging video, Label/product images of delivered shipment &amp; customer VOC</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Reverse QC dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>360 degree opening footage, POD remarks</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="4" style="width: 58.2pt;border-right: 1pt solid black;border-bottom: 1pt solid black;border-left: 1pt solid black;border-image: initial;border-top: none;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>BLUEDART</p>
                                    </td>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>21 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>seller has to reject the shipment &amp; mentioned remarks on POD</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need remarks on POD if avaivable</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>14 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>21 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7- 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Packaging video, Label/product images of delivered shipment &amp; customer VOC/ In case of OTP delivery- no escalation will be entertained</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="4" style="width: 58.2pt;border-right: 1pt solid black;border-bottom: 1pt solid black;border-left: 1pt solid black;border-image: initial;border-top: none;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>ATS</p>
                                    </td>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>14-20 Days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>seller has to accept the shipment &amp; mentioned remarks on POD</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>360 degree opening footage, POD remarks &amp; packaging video</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7-10 Days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7-10 Days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>14-20 Days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Packaging And Unboxing Video</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="5" style="width: 58.2pt;border-right: 1pt solid black;border-bottom: 1pt solid black;border-left: 1pt solid black;border-image: initial;border-top: none;height: 31.15pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Shadowfax</p>
                                    </td>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 31.15pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 31.15pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 31.15pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 31.15pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>seller has to accept the shipment &amp; mentioned remarks on POD, if RTO delivery is OTP based then he/she has to mention the same on FE device</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 31.15pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>360 degree opening footage, POD remarks ( if RTO delivery is OTP based then seller has to mention the same on FE device)</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>RTO POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward POD</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 12.9pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>NA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Forward dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 15.05pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Packaging video, Label/product images of delivered shipment &amp; customer VOC</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 78.6pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Reverse QC dispute</p>
                                    </td>
                                    <td style="width: 70.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>48 Hrs</p>
                                    </td>
                                    <td style="width: 58.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>7 - 10 days</p>
                                    </td>
                                    <td style="width: 190.4pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>&nbsp;</p>
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>Need to raise ticket/escalation with in 48 Hrs</p>
                                    </td>
                                    <td style="width: 125.2pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;height: 23pt;vertical-align: top;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;'>360 degree opening footage, POD remarks , if delivery delivery is OTP based then seller has to mention the same on FE device</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>&nbsp;</p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>&nbsp;</p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>&nbsp;</p>
<!--                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;background:fuchsia;">&nbsp;</span></p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;background:fuchsia;">&nbsp;</span></p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;background:fuchsia;">&nbsp;</span></p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;background:fuchsia;">&nbsp;</span></p>-->
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;text-indent:35.0pt;'><strong><u><span style="font-size:19px;background:fuchsia;">CUTT OFF TIMING FOR ORDER ALLOCATION</span></u></strong></p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>&nbsp;</p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>&nbsp;</p>
                        <table style="width: 3.2e+2pt;border: none;margin-left:4.8pt;border-collapse:collapse;">
                            <tbody>
                                <tr>
                                    <td style="width:140.15pt;border:solid black 1.0pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><strong><span style="color:black;">Courier Name</span></strong></p>
                                    </td>
                                    <td style="width:181.8pt;border:solid black 1.0pt;border-left:none;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><strong><span style="color:black;">Cutoff Timing</span></strong></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:140.15pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">AMAZON</span></p>
                                    </td>
                                    <td style="width:181.8pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">10.45</span><span style="color:black;">&nbsp;Am</span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:140.15pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">ECOM EXPRESS</span></p>
                                    </td>
                                    <td style="width:181.8pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">11.00 Am</span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:140.15pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">XPRESSBEES</span></p>
                                    </td>
                                    <td style="width:181.8pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">11.00 Am</span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:140.15pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">BLUEDART</span></p>
                                    </td>
                                    <td style="width:181.8pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">12.00 Noon</span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:140.15pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">EKART</span></p>
                                    </td>
                                    <td style="width:181.8pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">10.30 Am</span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:140.15pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">SHADOWFAX</span></p>
                                    </td>
                                    <td style="width:181.8pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">12.00 Noon</span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:140.15pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">SMARTR</span></p>
                                    </td>
                                    <td style="width:181.8pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">12.00 Noon</span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:140.15pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">DTDC</span></p>
                                    </td>
                                    <td style="width:181.8pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">12.00 Noon</span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:140.15pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">DELHIVERY</span></p>
                                    </td>
                                    <td style="width:181.8pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="color:black;">12.00 Noon</span></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>&nbsp;</p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>&nbsp;</p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>&nbsp;</p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>&nbsp;</p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><strong>&nbsp;</strong></p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-indent:77.0pt;'><strong><u><span style="font-size:19px;background:yellow;">ZONE WISE TAT</span></u></strong></p>
<!--                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><strong><span style="font-size:19px;background:yellow;">&nbsp;</span></strong></p>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><strong><span style="font-size:19px;background:yellow;">&nbsp;</span></strong></p>-->
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><strong><span style="font-size:19px;background:yellow;">Note:- Pickup day will be not count in TAT</span></strong></p>
                        <!--<p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><strong><span style="font-size:19px;background:yellow;">&nbsp;</span></strong></p>-->
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>&nbsp;</p>
                        <table style="float: left;border: none;width:220.55pt;border-collapse:collapse;margin-left:6.75pt;margin-right:6.75pt;">
                            <tbody>
                                <tr>
                                    <td style="width:135.6pt;border:solid black 1.0pt;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><strong><span style="font-size:19px;color:black;">Zone</span></strong></p>
                                    </td>
                                    <td style="width:84.95pt;border:solid black 1.0pt;border-left:none;background:yellow;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><strong><span style="font-size:19px;color:black;">Days</span></strong></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:135.6pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="font-size:19px;color:black;">Within City</span></p>
                                    </td>
                                    <td style="width:84.95pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="font-size:19px;color:black;">1 - 2 days</span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:135.6pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="font-size:19px;color:black;">Within States</span></p>
                                    </td>
                                    <td style="width:84.95pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="font-size:19px;color:black;">3 - 4 days</span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:135.6pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="font-size:19px;color:black;">Metro to Metro</span></p>
                                    </td>
                                    <td style="width:84.95pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="font-size:19px;color:black;">3 - 5 days</span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:135.6pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="font-size:19px;color:black;">Rest of India (ROI)</span></p>
                                    </td>
                                    <td style="width:84.95pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="font-size:19px;color:black;">6 - 8 days</span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:135.6pt;border:solid black 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="font-size:19px;color:black;">North East &amp; J&amp;K</span></p>
                                    </td>
                                    <td style="width:84.95pt;border-top:none;border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:14.4pt;">
                                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;vertical-align:middle;'><span style="font-size:19px;color:black;">8 - 10 days</span></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>&nbsp;</p>
                </div>
            </div>
        </div>
    </div>
    
@endsection
