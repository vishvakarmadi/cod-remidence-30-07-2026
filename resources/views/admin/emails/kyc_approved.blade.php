<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your KYC has been Approved!</title>
    <!-- Font Awesome CDN for Email Client rendering -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; padding: 40px 10px;">
        <tr>
            <td align="center">
                <!-- Main Container Card -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02); overflow: hidden; border: 1px solid #e2e8f0;">
                    
                    <!-- Decorative Top Bar -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #07344E 0%, #175e87 100%); height: 6px;"></td>
                    </tr>

                    <!-- Header with Logo -->
                    <tr>
                        <td align="center" style="padding: 30px 40px 20px 40px;">
                            <img src="https://hyloship.com/images/logo.png" alt="HyloShip Logo" style="max-height: 50px; max-width: 180px; display: block; border: 0; outline: none; text-decoration: none;">
                        </td>
                    </tr>

                    <!-- Main Welcome Banner -->
                    <tr>
                        <td align="center" style="padding: 0 40px 10px 40px;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 700; color: #1e293b; line-height: 1.3;">
                                KYC Approved &amp; Account Active!
                            </h1>
                        </td>
                    </tr>

                    <!-- Email Body -->
                    <tr>
                        <td style="padding: 20px 40px 30px 40px; font-size: 15px; line-height: 1.6; color: #475569;">
                            <p style="margin-top: 0; margin-bottom: 20px; font-size: 15px; color: #475569;">
                                Hello <strong>{{ $kycs->name }}</strong>,
                            </p>
                            <p style="margin-bottom: 20px; font-size: 15px; color: #475569;">
                                Great news! Your KYC documents have been successfully verified and approved by our team. Your seller account is now fully active on <strong>HyloShip</strong>.
                            </p>

                            <!-- Quick Start Steps -->
                            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px; margin-bottom: 25px;">
                                <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 16px; font-weight: 700; color: #15803d; display: flex; align-items: center;">
                                    <i class="fa fa-rocket" style="margin-right: 8px; font-size: 18px; color: #15803d; vertical-align: middle;"></i>
                                    <span style="vertical-align: middle;">What can you do now?</span>
                                </h3>
                                <ul style="margin: 0; padding: 0; list-style: none; font-size: 14.5px; color: #166534; line-height: 1.8;">
                                    <li style="margin-bottom: 8px; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; color: #15803d; vertical-align: middle;">
                                            <i class="fa fa-check" style="font-size: 14px;"></i>
                                        </span>
                                        Log into your HyloShip Seller Panel
                                    </li>
                                    <li style="margin-bottom: 8px; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; color: #15803d; vertical-align: middle;">
                                            <i class="fa fa-check" style="font-size: 14px;"></i>
                                        </span>
                                        Add or import your orders
                                    </li>
                                    <li style="margin-bottom: 8px; display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; color: #15803d; vertical-align: middle;">
                                            <i class="fa fa-check" style="font-size: 14px;"></i>
                                        </span>
                                        Recharge your wallet and start booking shipments immediately
                                    </li>
                                    <li style="display: flex; align-items: flex-start;">
                                        <span style="display: inline-block; margin-right: 8px; color: #15803d; vertical-align: middle;">
                                            <i class="fa fa-check" style="font-size: 14px;"></i>
                                        </span>
                                        Track packages in real-time
                                    </li>
                                </ul>
                            </div>

                            <!-- CTA Button -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom: 25px;">
                                        <a href="{{ $login_url }}" target="_blank" style="display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #07344E 0%, #175e87 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(7, 52, 78, 0.25); text-align: center; border: none; outline: none;">
                                            <i class="fa fa-rocket" style="margin-right: 8px;"></i> Start Shipping Now
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Support Section -->
                            <div style="border-top: 1px dashed #e2e8f0; padding-top: 20px; margin-top: 20px;">
                                <p style="margin: 0 0 10px 0; font-weight: 600; color: #1e293b; display: flex; align-items: center;">
                                    <i class="fa fa-comments" style="margin-right: 8px; font-size: 16px; color: #07344E; vertical-align: middle;"></i>
                                    <span style="vertical-align: middle;">Need any assistance?</span>
                                </p>
                                <p style="margin: 0; font-size: 14px; color: #64748b;">
                                    If you have any questions or need help with integrations, rates, or booking, feel free to reach out:
                                </p>
                                <p style="margin: 5px 0 0 0; font-size: 14px; display: flex; align-items: center;">
                                    <i class="fa fa-envelope" style="margin-right: 8px; font-size: 14px; color: #07344E; vertical-align: middle;"></i>
                                    <span style="vertical-align: middle;">Email: <a href="mailto:sales@hyloship.com" style="color: #07344E; text-decoration: none; font-weight: 600;">sales@hyloship.com</a></span>
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Closing & Signature -->
                    <tr>
                        <td style="padding: 0 40px 30px 40px; font-size: 14px; line-height: 1.5; color: #475569; border-bottom: 1px solid #f1f5f9;">
                            Best Regards,<br>
                            <strong>Team HyloShip</strong>
                        </td>
                    </tr>

                    <!-- Footer Info -->
                    <tr>
                        <td align="center" style="padding: 25px 40px; background-color: #f8fafc; font-size: 12px; color: #94a3b8; line-height: 1.6;">
                            <p style="margin: 0 0 5px 0;">This is an automated system email from HyloShip. Please do not reply directly to this message.</p>
                            <p style="margin: 0;">
                                <a href="https://www.hyloship.com" target="_blank" style="color: #64748b; text-decoration: underline;">www.hyloship.com</a> | 
                                <a href="mailto:sales@hyloship.com" style="color: #64748b; text-decoration: underline;">sales@hyloship.com</a>
                            </p>
                            <p style="margin: 15px 0 0 0; font-weight: 500;">&copy; {{ date('Y') }} HyloShip. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
