<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to HyloShip</title>
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
                                Welcome Onboard, {{ $admin->name }}!
                            </h1>
                        </td>
                    </tr>

                    <!-- Email Body -->
                    <tr>
                        <td style="padding: 20px 40px 30px 40px; font-size: 15px; line-height: 1.6; color: #475569;">
                            <p style="margin-top: 0; margin-bottom: 20px; font-size: 15px; color: #475569;">
                                Thank you for registering with <strong>HyloShip</strong>! We're excited to welcome you to our Seller Panel and partner with you to make your logistics operations seamless, fast, and cost-effective.
                            </p>

                            <!-- KYC Instruction Block -->
                            <div style="background-color: #f0f6f9; border: 1px solid #cbdde6; border-radius: 8px; padding: 20px; margin-bottom: 25px;">
                                <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 16px; font-weight: 700; color: #07344E; display: flex; align-items: center;">
                                    <i class="fa fa-file-text-o" style="margin-right: 8px; font-size: 18px; color: #07344E; vertical-align: middle;"></i>
                                    <span style="vertical-align: middle;">Next Step: Complete Your KYC</span>
                                </h3>
                                <p style="margin: 0; font-size: 14.5px; color: #1a4d6b; line-height: 1.5;">
                                    To activate your seller account and start shipping, please log into your dashboard and submit your KYC verification documents (PAN, Aadhaar, GST, etc.). Once submitted, our team will review and approve them within a few hours.
                                </p>
                            </div>

                            <!-- CTA Button -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom: 25px;">
                                        <a href="https://hyloship.com/admin/kyc" target="_blank" style="display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #07344E 0%, #175e87 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 15px; box-shadow: 0 4px 6px -1px rgba(7, 52, 78, 0.25); text-align: center; border: none; outline: none;">
                                            <i class="fa fa-id-card-o" style="margin-right: 8px;"></i> Complete Your KYC Now
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
                                    If you have any questions or face any issues while setting up, feel free to reach out to our support team:
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
                            <p style="margin: 0 0 5px 0;">This is an automated welcome email from HyloShip. Please do not reply directly to this message.</p>
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
