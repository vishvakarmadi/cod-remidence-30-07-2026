@extends('admin.admin_layouts')
@section('admin_content')
    
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                
                <div class='card-body'>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:.45pt;margin-right:1.35pt;margin-bottom:.0001pt;margin-left:20.75pt;text-align:center;'><strong><u><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Operational&nbsp;SOP</span></u></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.35pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <h1 style='margin-top:2.6pt;margin-right:0cm;margin-bottom:0cm;margin-left:21.85pt;text-indent:0cm;font-size:16px;font-family:"Calibri",sans-serif;'><u><span style='font-family:"Leelawadee UI",sans-serif;background:yellow;'>Last&nbsp;Mile</span></u></h1>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.35pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:2.6pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:27.0pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>1&nbsp;Delay&nbsp;in&nbsp;Delivery</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.25pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: decimal;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <h3 style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:36.0pt;text-indent:-14.45pt;font-size:13px;font-family:"Calibri",sans-serif;text-decoration:underline;'><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Delivery&nbsp;Delay&nbsp;Escalation</span></h3>
                            </li>
                        </ol>
                    </div>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.25pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">If the order has not been delivered on the provided estimated delivery date (EDD)</span><span style="font-size:19px;">&nbsp;</span><span style="font-size:19px;">and no delivery attempts have been made: Escalate delivery delay through shipment escalation panel for immediate action and updates.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.35pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: decimal;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <h3 style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:36.0pt;text-indent:-14.45pt;font-size:13px;font-family:"Calibri",sans-serif;text-decoration:underline;'><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Delivery&nbsp;Freight&nbsp;Refund&nbsp;SOP</span></h3>
                            </li>
                        </ol>
                    </div>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.1pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:3.05pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:27.6pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>No&nbsp;delivery&nbsp;attempt&nbsp;made&nbsp;and&nbsp;shipment&nbsp;got&nbsp;returned:</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">If no delivery attempt is made and shipment got returned &ndash; Freight Refund will be provided (excluding ODA/Damaged/Misroute and if there is a natural calamity or disaster. FR will be issued in 7 to 1</span><span style="font-size:19px;">0</span><span style="font-size:19px;">&nbsp;days of reporting the claim for approved cases.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.35pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:.05pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:27.5pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>NDR</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: decimal;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <h3 style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:36.0pt;text-indent:-14.45pt;font-size:13px;font-family:"Calibri",sans-serif;text-decoration:underline;'><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>NDR&nbsp;Attempt&nbsp;SOP</span></h3>
                            </li>
                        </ol>
                    </div>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.5pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">Attempts: after the 1st attempt, 2 more delivery attempts will be made by the courier. When a shipment goes into NDR, we do an SMS &amp; IVR Calling for the buyer&rsquo;s response.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">In the case of an OTP-based shipment, if the buyer refuses the shipment with the OTP, then no further attempts will be made.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">&nbsp;</span></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: decimal;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <h3 style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:36.0pt;text-indent:-14.45pt;font-size:13px;font-family:"Calibri",sans-serif;text-decoration:underline;'><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>How&nbsp;to&nbsp;take action&nbsp;on&nbsp;NDR:</span></h3>
                            </li>
                        </ol>
                    </div>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.2pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">Go to&nbsp;</span><span style="font-size:19px;">NDR</span><span style="font-size:19px;">&nbsp;Tab , Go to Action Required and see the reason in exception Info, based on the same click on Take Action</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">&nbsp;</span></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: decimal;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">Courier partner is supposed to make 3 valid delivery attempts on other than verified cases on different calendar days, before processing a shipment RTO.</span></li>
                        </ol>
                    </div>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-left:21.85pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>1.7&nbsp;Once&nbsp;the&nbsp;shipment&nbsp;is&nbsp;updated as&nbsp;return&nbsp;will&nbsp;not&nbsp;be&nbsp;revoked&nbsp;and&nbsp;same&nbsp;will&nbsp;be&nbsp;Return&nbsp;delivered then.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: undefined;margin-left:0cmundefined;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><strong><span style='font-family:"Leelawadee UI",sans-serif;background:yellow;font-size:12.0pt;background:yellow;'>POD SOP</span></strong></li>
                        </ol>
                    </div>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><strong><u><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;Sop Request</span></u></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:1.65pt;margin-right:31.85pt;margin-bottom:.0001pt;margin-left:0cm;line-height:115%;'><span style='font-size:16px;line-height:115%;font-family:"Leelawadee UI",sans-serif;'>Within&nbsp;2&nbsp;days&nbsp;of&nbsp;the&nbsp;delivery&nbsp;date,&nbsp;proof&nbsp;of&nbsp;delivery&nbsp;(POD)&nbsp;can&nbsp;be&nbsp;requested&nbsp;from&nbsp;the&nbsp;escalation&nbsp;panel&nbsp;except&nbsp;for&nbsp;OTP-based&nbsp;delivered&nbsp;shipments.&nbsp;POD&nbsp;requests&nbsp;will&nbsp;not&nbsp;be&nbsp;accepted&nbsp;after&nbsp;48&nbsp;hrs&nbsp;of&nbsp;delivery.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:6.15pt;margin-right:31.6pt;margin-bottom:.0001pt;margin-left:27.0pt;text-indent:-.5pt;line-height:115%;'><span style='font-size:16px;line-height:115%;font-family:"Leelawadee UI",sans-serif;'>-If POD is shared but customer still denying Receipt of shipment- <strong>VOC is required</strong> for investigation (within 48 hrs) -If&nbsp;shipment&nbsp;is&nbsp;delivered as&nbsp;per&nbsp;customer&nbsp;but status&nbsp;not updated,&nbsp;Product with shipping&nbsp;label&nbsp;images&nbsp;required</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: undefined;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><strong><u><span style='font-family:"Leelawadee UI",sans-serif;font-size:12.0pt;background:yellow;'>Delivery&nbsp;Dispute&nbsp;SOP</span></u></strong></li>
                        </ol>
                    </div>
                    <p style='margin-top:5.6pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:39.2pt;text-indent:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <table style="width: 5.0e+2pt;margin-left:29.55pt;border-collapse:collapse;border:none;">
                        <tbody>
                            <tr>
                                <td style="width: 254pt;border: 1pt solid black;padding: 0cm;height: 54.3pt;vertical-align: top;">
                                    <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;margin-top:5.1pt;margin-right:  109.55pt;margin-bottom:.0001pt;margin-left:50.2pt;text-align:center;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Delivery&nbsp;Dispute</span></strong></p>
                                </td>
                                <td style="width: 241.8pt;border-top: 1pt solid black;border-right: 1pt solid black;border-bottom: 1pt solid black;border-image: initial;border-left: none;padding: 0cm;height: 54.3pt;vertical-align: top;">
                                    <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;margin-top:4.95pt;margin-right:36.75pt;margin-bottom:.0001pt;margin-left:24.8pt;text-indent:.45pt;line-height:100%;'><span style='font-size:16px;line-height:100%;font-family:"Leelawadee UI",sans-serif;'>Raise&nbsp;a&nbsp;Delivery&nbsp;Dispute&nbsp;within&nbsp;48&nbsp;Hrs&nbsp;of&nbsp;the&nbsp;shipment delivery. After that dispute won&rsquo;t&nbsp;be&nbsp;entertained.</span></p>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 254pt;border-right: 1pt solid black;border-bottom: 1pt solid black;border-left: 1pt solid black;border-image: initial;border-top: none;padding: 0cm;height: 66.65pt;vertical-align: top;">
                                    <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;margin-top:5.1pt;margin-right:  126.5pt;margin-bottom:.0001pt;margin-left:68.55pt;text-align:center;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Eligibility</span></strong></p>
                                </td>
                                <td style="width: 241.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;padding: 0cm;height: 66.65pt;vertical-align: top;">
                                    <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;margin-top:5.1pt;margin-right:17.35pt;margin-bottom:.0001pt;margin-left:24.55pt;text-indent:-.15pt;line-height:100%;'><span style='font-size:16px;line-height:100%;font-family:"Leelawadee UI",sans-serif;'>A delivery dispute can be raised for Delivered but&nbsp;Not Received,Damaged Product, Empty Packet,&nbsp;Wrong&nbsp;Product&nbsp;&amp;Partial&nbsp;Delivery.</span></p>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 254pt;border-right: 1pt solid black;border-bottom: 1pt solid black;border-left: 1pt solid black;border-image: initial;border-top: none;padding: 0cm;height: 71.35pt;vertical-align: top;">
                                    <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;margin-top:5.1pt;margin-right:  107.2pt;margin-bottom:.0001pt;margin-left:50.2pt;text-align:center;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Process</span></strong></p>
                                </td>
                                <td style="width: 241.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;padding: 0cm;height: 71.35pt;vertical-align: top;">
                                    <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;margin-top:5.1pt;margin-right:27.85pt;margin-bottom:.0001pt;margin-left:24.95pt;text-indent:-.15pt;line-height:110%;'><span style='font-size:16px;line-height:110%;font-family:"Leelawadee UI",sans-serif;'>Share images of the pre-shipped package,&nbsp;packaging video, post- shipped package, inner&nbsp;content, catalogue product,and unboxing&nbsp;video.&nbsp;Disputed&nbsp;remarks&nbsp;on&nbsp;POD&nbsp;is&nbsp;mandatory.</span></p>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 254pt;border-right: 1pt solid black;border-bottom: 1pt solid black;border-left: 1pt solid black;border-image: initial;border-top: none;padding: 0cm;height: 65.95pt;vertical-align: top;">
                                    <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;margin-top:5.2pt;margin-right:  110.45pt;margin-bottom:.0001pt;margin-left:50.2pt;text-align:center;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Support&nbsp;&amp;&nbsp;Refund</span></strong></p>
                                </td>
                                <td style="width: 241.8pt;border-top: none;border-left: none;border-bottom: 1pt solid black;border-right: 1pt solid black;padding: 0cm;height: 65.95pt;vertical-align: top;">
                                    <p style='margin:0cm;font-size:15px;font-family:"Arial MT",sans-serif;margin-top:5.2pt;margin-right:30.9pt;margin-bottom:.0001pt;margin-left:24.95pt;'><span style='font-size:16px;line-height:107%;font-family:"Leelawadee UI",sans-serif;'>Ops&nbsp;will&nbsp;use&nbsp;these&nbsp;images&nbsp;and videos&nbsp;as&nbsp;to&nbsp;file&nbsp;a dispute with the courier and initiate the&nbsp;refund&nbsp;process if&nbsp;eligibility&nbsp;criteria&nbsp;is&nbsp;met</span></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: undefined;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <h1 style='margin-top:2.6pt;margin-right:0cm;margin-bottom:0cm;margin-left:30.35pt;text-indent:-9.05pt;font-size:16px;font-family:"Calibri",sans-serif;'><span style='font-family:"Leelawadee UI",sans-serif;background:yellow;background:yellow;'>Lost&nbsp;SOP</span></h1>
                            </li>
                        </ol>
                    </div>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: undefined;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <h3 style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:36.0pt;text-indent:-14.45pt;font-size:13px;font-family:"Calibri",sans-serif;text-decoration:underline;'><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>If&nbsp;shipment&nbsp;is&nbsp;Unsecured:</span></h3>
                            </li>
                        </ol>
                    </div>
                    <h3 style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:21.2pt;text-indent:0cm;font-size:13px;font-family:"Calibri",sans-serif;text-decoration:underline;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;text-decoration:none;'>&nbsp;</span></h3>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.55pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:50.4pt;text-indent:-.25pt;line-height:100%;'><span style='font-size:16px;line-height:100%;font-family:"Leelawadee UI",sans-serif;'>Once&nbsp;the&nbsp;LOST/Damaged&nbsp;confirmation received&nbsp;from&nbsp;courier,&nbsp;The&nbsp;invoice&nbsp;value&nbsp;of&nbsp;the&nbsp;shipment up&nbsp;to&nbsp;INR.4999&nbsp;(Maximum&nbsp;Liability) can&nbsp;be&nbsp;claimed.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.5pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: undefined;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>COF&nbsp;will&nbsp;be&nbsp;issued&nbsp;for&nbsp;the&nbsp;shipments&nbsp;above&nbsp;INR&nbsp;4999&nbsp;for&nbsp;all&nbsp;couriers</span></li>
                        </ol>
                    </div>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: undefined;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='line-height:101%;font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Any fragile /Eatables product will not be considered for lost / damage Credit Notes I</span><span style='line-height:101%;font-family:"Leelawadee UI",sans-serif;font-family:"Leelawadee UI",sans-serif;font-size:12.0pt;'>,</span><span style='line-height:101%;font-family:"Leelawadee UI",sans-serif;font-size:16px;'>e. - Food, Glass, Ceramic, Plants,&nbsp;Spray/Perfumes and any Liquid&nbsp;form shipments.</span></li>
                        </ol>
                    </div>
                    <p style='margin-top:0cm;margin-right:20.35pt;margin-bottom:.0001pt;margin-left:21.35pt;text-indent:0cm;font-size:15px;font-family:"Calibri",sans-serif;line-height:101%;'><span style='font-size:16px;line-height:101%;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: undefined;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='line-height:100%;font-family:"Leelawadee UI",sans-serif;font-size:16px;'>No&nbsp;Credit&nbsp;Note&nbsp;for&nbsp;Damage&nbsp;or&nbsp;Lost&nbsp;shall&nbsp;be&nbsp;issued&nbsp;where&nbsp;in&nbsp;the&nbsp;average&nbsp;delivery&nbsp;%&nbsp;for&nbsp;last&nbsp;3&nbsp;Months&nbsp;is&nbsp;below&nbsp;40%&nbsp;for all&nbsp;couriers except DELHIVERY (for&nbsp;Delhivery&nbsp;courier,&nbsp;min&nbsp;60%&nbsp;delivery is&nbsp;mandatory)</span></li>
                        </ol>
                    </div>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:50.3pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&gt;&nbsp;However&nbsp;we&nbsp;shall&nbsp;push&nbsp;courier&nbsp;to&nbsp;return&nbsp;the&nbsp;shipment&nbsp;with&nbsp;utmost&nbsp;urgency&nbsp;and&nbsp;care.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.2pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: undefined;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>No&nbsp;Credit&nbsp;Note&nbsp;for&nbsp;Damage&nbsp;or&nbsp;Lost&nbsp;shall&nbsp;be&nbsp;issued&nbsp;where&nbsp;in&nbsp;the&nbsp;overall&nbsp;delivery&nbsp;%&nbsp;is&nbsp;below&nbsp;60%&nbsp;for&nbsp;DELHIVERY&nbsp;courier.</span></li>
                        </ol>
                    </div>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: undefined;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <h3 style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:36.0pt;text-indent:-14.45pt;font-size:13px;font-family:"Calibri",sans-serif;text-decoration:underline;'><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>If&nbsp;shipment&nbsp;is&nbsp;Secured&nbsp;by&nbsp;Insurance</span><span style='font-family:"Leelawadee UI",sans-serif;'>.&nbsp;</span></h3>
                            </li>
                        </ol>
                    </div>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.4pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">No insurance claim will be provided by Hyloship, seller can opt 3<sup>rd</sup> party insurance to insured his shipments.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">We will help to provide the required documents to seller, which are available with Hyloship.&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">&nbsp;</span></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: undefined;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>For&nbsp;RTO&nbsp;delivery -&nbsp;a&nbsp;maximum&nbsp;of&nbsp;3&nbsp;delivery&nbsp;attempts&nbsp;will&nbsp;be&nbsp;made&nbsp;to&nbsp;get the&nbsp;shipment&nbsp;delivered.&nbsp;After&nbsp;3&nbsp;attempts,&nbsp;the&nbsp;shipment will</span><span style='font-family:"Leelawadee UI",sans-serif;font-size:12.0pt;'>&nbsp;</span><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>be&nbsp;disposed&nbsp;of,&nbsp;and&nbsp;no&nbsp;refund&nbsp;will be&nbsp;issued.</span></li>
                        </ol>
                    </div>
                    <p style='margin-top:.9pt;margin-right:66.85pt;margin-bottom:.0001pt;margin-left:0cm;text-indent:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ol style="margin-bottom:0cm;list-style-type: undefined;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='line-height:101%;font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Ewaybill (EBN) is mandatory for shipment value is equal to or more than INR 50000/- which need to be&nbsp;physically&nbsp;handed&nbsp;over to&nbsp;pickup team at&nbsp;the&nbsp;time of&nbsp;pickup.</span></li>
                        </ol>
                    </div>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:5.75pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:43.3pt;text-align:center;'><strong><u><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>First&nbsp;Mile&nbsp;SOP</span></u></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.5pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:2.8pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:26.15pt;'><strong><u><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>AMAZON&nbsp;</span></u></strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>:</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:3.1pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:29.4pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>Booking&nbsp;Cutoff&nbsp;time&nbsp;-&nbsp;1</span></strong><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>1.45</span></strong><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>&nbsp;</span></strong><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>Am</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <h2 style='margin-top:.05pt;margin-right:0cm;margin-bottom:0cm;margin-left:43.3pt;font-size:15px;font-family:"Calibri",sans-serif;'><u><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Labelling&nbsp;Guidelines</span></u></h2>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.3pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong></p>
                    <ol start="12" style="list-style-type: lower-alpha;margin-left:11px;">
                        <li><span style="font-size:19px;">You should use sticky paper to print the labels.</span></li>
                        <li><span style="font-size:19px;">Label size should be 4x6 inches &ndash; you can print 4 labels on one A4 sheet.</span></li>
                        <li><span style="font-size:19px;">The label print must be clear and readable, the bar code and QR code should be scannable and the To/From address must be visible.</span></li>
                        <li><span style="font-size:19px;">Do not fold the shipping label along any edge as the information on the bottom of the label is required at various points during transit.</span></li>
                        <li><span style="font-size:19px;">Label should be securely pasted on a flat surface and invoice should be put inside the package.&nbsp;</span></li>
                        <li><span style="font-size:19px;">Affix shipping label on the visible side of the package and put the invoice inside the package.&nbsp;</span></li>
                        <li><span style="font-size:19px;">Brand tape should be used while packing the shipment</span></li>
                    </ol>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.25pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-left:62.65pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Note:&nbsp;</span></strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Do&nbsp;not&nbsp;put&nbsp;the&nbsp;label&nbsp;in&nbsp;the&nbsp;document&nbsp;pouch&nbsp;of&nbsp;the&nbsp;outer&nbsp;package.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <ol start="12" style="list-style-type: lower-alpha;margin-left:11px;">
                        <li><span style="font-size:19px;">Use good quality sticky paper to print the labels. Do not make any markings on the shipping label.</span></li>
                        <li><span style="font-size:19px;">Non-adherence to these guidelines can lead to pickup associates not picking the packages.</span></li>
                        <li><span style="font-size:19px;">Orders getting</span><span style="font-size:19px;">&nbsp;</span><span style="font-size:19px;">&nbsp;cancelled or delayed.</span></li>
                        <li><span style="font-size:19px;">Maximum Size: 70Cm*70cm*45cm</span></li>
                        <li><span style="font-size:19px;">Maximum weight: 20Kg</span></li>
                        <li><span style="font-size:19px;">Maximum value of shipment &ndash; up to 50K</span></li>
                        <li><span style="font-size:19px;">Please capture images and video while packing to avoid forward / RTO delivery dispute &bull;</span></li>
                        <li><span style="font-size:19px;">Do not ship any banned items given in&nbsp;</span><span style="font-size:19px;">Aggrement</span></li>
                    </ol>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-left:15.95pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>Ecom&nbsp;:&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-left:15.95pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>&nbsp;</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-left:15.95pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>Booking&nbsp;Cutoff&nbsp;time&nbsp;-&nbsp;ECOM 11:00&nbsp;AM</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.5pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-left:39.1pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>*&nbsp;Normal&nbsp;/&nbsp;Adequate&nbsp;Packaging&nbsp;Required</span></p>
                    <ul style="list-style-type: undefined;margin-left:21.2px;">
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Maximum&nbsp;weight:&nbsp;5Kg&nbsp;(volumetric&nbsp;or&nbsp;actual&nbsp;whichever&nbsp;is&nbsp;higher)</span>
                            <ul style="list-style-type: undefined;">
                                <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Please&nbsp;capture&nbsp;images&nbsp;and&nbsp;video&nbsp;while&nbsp;packing&nbsp;to&nbsp;avoid&nbsp;forward&nbsp;/&nbsp;RTO&nbsp;delivery&nbsp;dispute&nbsp;&bull;</span></li>
                            </ul>
                        </li>
                    </ul>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:1.45pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:44.5pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Do&nbsp;not&nbsp;ship&nbsp;any&nbsp;banned&nbsp;items&nbsp;given&nbsp;in&nbsp;</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Aggrement</span></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>Xpressbees</span></strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>:</span></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.1pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:26.9pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>Booking&nbsp;Cutoff&nbsp;time&nbsp;-&nbsp;11:00&nbsp;AM</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.35pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <ul style="list-style-type: undefined;">
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Normal&nbsp;/&nbsp;Adequate&nbsp;Packaging&nbsp;Required</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Maximum&nbsp;weight:&nbsp;20Kg&nbsp;(volumetric&nbsp;or&nbsp;actual&nbsp;whichever&nbsp;is&nbsp;higher)</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Please&nbsp;capture&nbsp;images&nbsp;and&nbsp;video&nbsp;while&nbsp;packing&nbsp;to&nbsp;avoid&nbsp;forward&nbsp;/&nbsp;RTO&nbsp;delivery&nbsp;dispute &bull;</span></li>
                    </ul>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:1.4pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:44.5pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Do&nbsp;not&nbsp;ship&nbsp;any&nbsp;banned items&nbsp;given&nbsp;in&nbsp;</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Aggrement</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.2pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <h2 style='margin-top:0cm;margin-right:432.4pt;margin-bottom:.0001pt;margin-left:16.5pt;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>Bluedart&nbsp;</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;font-weight:normal;'>:</span></h2>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.2pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.05pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:26.9pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>Booking&nbsp;Cutoff&nbsp;time&nbsp;-&nbsp;12:00&nbsp;Noon</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.35pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <ul style="list-style-type: undefined;">
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Normal&nbsp;/&nbsp;Adequate&nbsp;Packaging&nbsp;Required</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Please&nbsp;capture&nbsp;images&nbsp;and&nbsp;video&nbsp;while&nbsp;packing&nbsp;to&nbsp;avoid&nbsp;forward&nbsp;/&nbsp;RTO&nbsp;delivery&nbsp;dispute &bull;</span></li>
                    </ul>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:1.45pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:44.5pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Do&nbsp;not&nbsp;ship&nbsp;any&nbsp;banned items&nbsp;given&nbsp;in&nbsp;</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Aggrement</span></p>
                    <h2 style='margin-top:9.6pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></h2>
                    <h2 style='margin-top:9.6pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>Ekart&nbsp;</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;font-weight:normal;'>:</span></h2>
                    <h2 style='margin-top:9.6pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;font-weight:normal;'>&nbsp;</span></h2>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><strong><span style="font-size:19px;background:aqua;">Booking Cutoff time</span></strong><strong><span style="font-size:19px;background:aqua;">&nbsp;</span></strong><strong><span style="font-size:19px;background:aqua;">EKART 10:30 AM</span></strong><span style="font-size:19px;">&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style="font-size:19px;">&nbsp;</span></p>
                    <ol start="12" style="list-style-type: lower-alpha;margin-left:11px;">
                        <li><span style="font-size:19px;">Normal / Adequate Packaging Required</span></li>
                        <li><span style="font-size:19px;">Maximum weight: 5Kg (volumetric or actual whichever is higher)</span></li>
                        <li><span style="font-size:19px;">Please capture images and video while packing to avoid forward / RTO delivery dispute &bull;</span></li>
                        <li><span style="font-size:19px;">Do not ship any banned items given in&nbsp;</span><span style="font-size:19px;">Aggrement.</span></li>
                    </ol>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:1.45pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:44.5pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <h3 style='margin-top:9.8pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:28.8pt;text-indent:0cm;font-size:13px;font-family:"Calibri",sans-serif;text-decoration:underline;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;text-decoration:none;'>Shadowfax&nbsp;</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;font-weight:normal;text-decoration:none;'>:</span></h3>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:7.1pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:26.9pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>Booking&nbsp;Cutoff&nbsp;time&nbsp;in&nbsp;SF&nbsp;12:00&nbsp;Noon</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.35pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <ul style="list-style-type: undefined;">
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Normal&nbsp;/&nbsp;Adequate&nbsp;Packaging&nbsp;Required</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Maximum&nbsp;weight:&nbsp;5Kg&nbsp;(volumetric&nbsp;or&nbsp;actual&nbsp;whichever&nbsp;is&nbsp;higher)</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Please&nbsp;capture&nbsp;images&nbsp;and&nbsp;video&nbsp;while&nbsp;packing&nbsp;to&nbsp;avoid&nbsp;forward&nbsp;/&nbsp;RTO&nbsp;delivery&nbsp;dispute &bull;</span></li>
                    </ul>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:1.35pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:44.5pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Do&nbsp;not&nbsp;ship&nbsp;any&nbsp;banned items&nbsp;given&nbsp;in&nbsp;</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Aggrement</span></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:1.35pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:44.5pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <h2 style='margin-top:9.45pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:42.85pt;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>Smartr&nbsp;</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;font-weight:normal;'>:</span></h2>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.45pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-left:26.9pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>Booking&nbsp;Cutoff&nbsp;time&nbsp;in&nbsp;Smartr&nbsp;12:00&nbsp;Noon</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.35pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-left:36.85pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>.&nbsp;Normal&nbsp;/&nbsp;Adequate&nbsp;Packaging&nbsp;Required</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>.</span></p>
                    <div style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'>
                        <ul style="margin-bottom:0cm;list-style-type: undefined;">
                            <li style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Please&nbsp;capture&nbsp;images&nbsp;and&nbsp;video&nbsp;while&nbsp;packing&nbsp;to&nbsp;avoid&nbsp;forward&nbsp;/&nbsp;RTO&nbsp;delivery&nbsp;dispute &bull;</span></li>
                        </ul>
                    </div>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:1.4pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:44.5pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Do&nbsp;not&nbsp;ship&nbsp;any&nbsp;banned items&nbsp;given&nbsp;in&nbsp;</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Aggrement</span></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:1.4pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <h2 style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:43.3pt;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>DTDC&nbsp;</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;font-weight:normal;'>:</span></h2>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.2pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-left:26.9pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>Booking&nbsp;Cutoff&nbsp;Time&nbsp;in&nbsp;DTDC&nbsp;12:00&nbsp;Noon</span></strong></p>
                    <p style='margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:0cm;text-indent:54.0pt;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Normal&nbsp;/&nbsp;Adequate&nbsp;Packaging&nbsp;Required</span></p>
                    <ul style="list-style-type: undefined;">
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Maximum&nbsp;weight:&nbsp;20Kg&nbsp;(volumetric&nbsp;or&nbsp;actual&nbsp;whichever&nbsp;is&nbsp;higher)</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Please&nbsp;capture&nbsp;images&nbsp;and&nbsp;video&nbsp;while&nbsp;packing&nbsp;to&nbsp;avoid&nbsp;forward&nbsp;/&nbsp;RTO&nbsp;delivery&nbsp;dispute &bull;</span></li>
                    </ul>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:1.35pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:44.5pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Do&nbsp;not&nbsp;ship&nbsp;any&nbsp;banned items&nbsp;given&nbsp;in&nbsp;</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Aggrement</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <h2 style='margin-top:8.4pt;margin-right:0cm;margin-bottom:0cm;margin-left:43.3pt;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>Delhivery</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;font-weight:normal;'>:</span></h2>
                    <h2 style='margin-top:8.4pt;margin-right:0cm;margin-bottom:0cm;margin-left:43.3pt;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;font-weight:normal;'>&nbsp;</span></h2>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:26.9pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>Booking&nbsp;Cutoff&nbsp;time&nbsp;12:00&nbsp;Noon</span></strong></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.2pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <ul style="list-style-type: undefined;">
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Normal&nbsp;/&nbsp;Adequate&nbsp;Packaging&nbsp;Required</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Maximum&nbsp;weight:&nbsp;20Kg&nbsp;(volumetric&nbsp;or&nbsp;actual&nbsp;whichever&nbsp;is&nbsp;higher)</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Please&nbsp;capture&nbsp;images&nbsp;and&nbsp;video&nbsp;while&nbsp;packing&nbsp;to&nbsp;avoid&nbsp;forward&nbsp;/&nbsp;RTO&nbsp;delivery&nbsp;dispute &bull;</span></li>
                    </ul>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:1.2pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:44.5pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Do&nbsp;not&nbsp;ship&nbsp;any&nbsp;banned items&nbsp;given&nbsp;in&nbsp;</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Aggrement</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><strong><u><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>Weight Calculation:-</span></u></strong><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>&nbsp;</span></strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:yellow;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Dead weight or Volumetric which is higher.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Dead weight - Is calculating in Grams or Kimogram.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Volumetric - DTDC &nbsp; L x B x H / 4750.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Delhivery &nbsp;L x B x H / 4000&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;All other Courier - L x B x H / 5000</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:aqua;'>Weight Disputes:-</span></strong><strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></strong><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>Weight disputes is uploading in Hyloship panel (In weight Reconciliation) and it should be entertain by seller within 7 days of uploading, either it will auto accepted and no action will be taken after accepted.</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.15pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <h2 style='margin-top:0cm;margin-right:1.35pt;margin-bottom:.0001pt;margin-left:20.75pt;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;background:fuchsia;'>Aggrement</span></h2>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;margin-top:.5pt;margin-right:172.0pt;margin-bottom:.0001pt;margin-left:191.65pt;text-align:center;line-height:203%;'><strong><span style='font-size:16px;line-height:203%;font-family:"Leelawadee UI",sans-serif;background:fuchsia;'>List of Banned Items in Courier&nbsp;DO&nbsp;NOT&nbsp;SHIP&nbsp;BELOW&nbsp;ITEMS</span></strong></p>
                    <p style='margin-top:.15pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:21.8pt;text-indent:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <ul style="list-style-type: undefined;margin-left:20px;">
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Currency</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Indian&nbsp;Postal Articles&nbsp;like&nbsp;Passports&nbsp;etc</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Liquids&nbsp;&amp;&nbsp;Semi-liquids&nbsp;-&nbsp;(<strong>By&nbsp;Air&nbsp;up&nbsp;to&nbsp;100&nbsp;ML</strong>)&nbsp;(By&nbsp;Surface allowed&nbsp;with&nbsp;adequate&nbsp;packing)</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Philately&nbsp;Items</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Pornography</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Bullion</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;'>Battery.</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Drugs&nbsp;,&nbsp;Narcotics&nbsp;and&nbsp;e-cigarettes&nbsp;(Illegal)</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Firearms,&nbsp;parts&nbsp;thereof,&nbsp;ammunition&nbsp;including&nbsp;toy&nbsp;gun&nbsp;and&nbsp;related&nbsp;articles</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Precious&nbsp;&amp;&nbsp;Semi-Precious&nbsp;Items&nbsp;(<strong>Artificial&nbsp;Jewelry&nbsp;allowed</strong>)</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Radioactive&nbsp;Material</span></li>
                        <li><span style='font-family:"Leelawadee UI",sans-serif;font-size:16px;'>Commodities&nbsp;banned&nbsp;by&nbsp;Law at&nbsp;any&nbsp;given&nbsp;time&nbsp;without&nbsp;prior&nbsp;notice</span></li>
                    </ul>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-top:.65pt;margin-right:0cm;margin-bottom:.0001pt;margin-left:50.3pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>All&nbsp;IATA&nbsp;Restricted&nbsp;Items&nbsp;and&nbsp;Dangerous&nbsp;goods</span><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>.</span></p>
<!--                    <p><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'><br>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:13px;font-family:"Calibri",sans-serif;margin-left:36.85pt;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'><br>&nbsp;</span></p>
                    <p style='margin:0cm;font-size:15px;font-family:"Calibri",sans-serif;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'>&nbsp;</span></p>
                    <p><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;'><br>&nbsp;</span></p>
                    <h2 style='margin-top:3.45pt;margin-right:432.4pt;margin-bottom:.0001pt;margin-left:15.05pt;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><span style='font-size:16px;font-family:"Leelawadee UI",sans-serif;font-weight:normal;'>&nbsp;</span></h2>-->
                </div>
            </div>
        </div>
    </div>
    
@endsection
