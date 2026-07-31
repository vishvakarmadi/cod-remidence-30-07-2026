<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\PhotoChangeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\LoginController as LoginControllerForAdmin;
use App\Http\Controllers\Admin\LogoutController as LogoutControllerForAdmin;
use App\Http\Controllers\Admin\CustomerController as CustomerControllerForAdmin;
use App\Http\Controllers\Admin\DashboardController as DashboardControllerForAdmin;
use App\Http\Controllers\Admin\RateController as RateControllerForAdmin;
use App\Http\Controllers\Admin\IntegrationController as IntegrationControllerForAdmin;
use App\Http\Controllers\Admin\ForgetPasswordController as ForgetPasswordControllerForAdmin;
use App\Http\Controllers\Admin\ResetPasswordController as ResetPasswordControllerForAdmin;
use App\Http\Controllers\Admin\PasswordChangeController as PasswordChangeControllerForAdmin;
use App\Http\Controllers\Admin\ProfileChangeController as ProfileChangeControllerForAdmin;
use App\Http\Controllers\Admin\KycController as KycControllerForAdmin;
use App\Http\Controllers\Admin\WarehouseController as WarehouseControllerForAdmin;
use App\Http\Controllers\Admin\DemoController as DemoControllerForAdmin;
use App\Http\Controllers\Admin\OrderController as OrderControllerForAdmin;
use App\Http\Controllers\Admin\NotificationController as NotificationControllerForAdmin;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CronController;
use App\Http\Controllers\Admin\WeightController;
use App\Http\Controllers\Admin\GoogleController;
use App\Http\Controllers\Admin\GoogleAuthController;
use App\Http\Controllers\Admin\TrackController;
use App\Http\Controllers\Admin\BroadcastController;
use App\Http\Controllers\Admin\CreditController;
use Illuminate\Support\Facades\Mail;



Route::get('/test-mail', function () {
    Mail::raw('This is a test email from Laravel!', function ($message) {
        $message->to(['adityav3011@gmail.com','dinanath.kumar@aframaxlogistics.com','operations@hyloship.com'])   // your email to receive
                ->subject('Laravel Mail Test');
    });
    return 'Test email sent!';
});



/* --------------------------------------- */
/* Admin Login and profile management */
/* --------------------------------------- */
// Route::get('/', function () {return redirect('login');});
// Route::get('admin', function () {return redirect('login');});
// Route::get('admin/', function () {return redirect('login');});
// Route::get('register/{company_code}', [LoginControllerForAdmin::class,'register'])->name('admin.register');
Route::get('admin', [LoginControllerForAdmin::class,'index'])->name('admin.login');
Route::get('admin/', [LoginControllerForAdmin::class,'index'])->name('admin.login');
Route::get('login', [LoginControllerForAdmin::class,'index'])->name('admin.login');
//Route::get('login1', [LoginControllerForAdmin::class,'login1'])->name('admin.login1');
// Route::get('login', function () {
//     abort(503);
// })->name('admin.login');
Route::post('login/store', [LoginControllerForAdmin::class,'store'])->name('admin.login.store');
Route::get('login/store', [LoginControllerForAdmin::class,'login1'])->name('admin.login1');
Route::get('logout', [LogoutControllerForAdmin::class,'index'])->name('admin.logout'); 
Route::get('new', [LoginControllerForAdmin::class,'new'])->name('admin.new');
// Route::get('register', function () {
//     abort(503);
// })->name('admin.register');
// Route::get('register1', [LoginControllerForAdmin::class,'register1'])->name('admin.register1');
Route::get('register/{company_code}', [LoginControllerForAdmin::class,'register'])->name('admin.register');
Route::post('register/store', [LoginControllerForAdmin::class,'register_store'])->name('admin.register.store');
Route::get('css/style.css', [GeneralSettingController::class,'color_change'])->name('admin.css.change');
Route::get('states', [LoginControllerForAdmin::class,'states'])->name('states');
Route::get('cache', [GeneralSettingController::class,'cache'])->name('cache');
Route::get('cache/clear', [GeneralSettingController::class,'cache_clear'])->name('cache.clear');
Route::get('otp', [LoginControllerForAdmin::class,'otp'])->name('admin.otp');
Route::post('otp/store', [LoginControllerForAdmin::class,'otpverify'])->name('admin.otp.store');
Route::get('forget-password', [ForgetPasswordControllerForAdmin::class,'index'])->name('admin.forget_password');
Route::get('forget-password1', [ForgetPasswordControllerForAdmin::class,'forget_password1'])->name('admin.forget-password1');
Route::post('forget-password/store', [ForgetPasswordControllerForAdmin::class,'store'])->name('admin.forget_password.store');
Route::get('reset-password/{token}/{email}', [ResetPasswordControllerForAdmin::class,'index']);
Route::post('reset-password/update', [ResetPasswordControllerForAdmin::class,'update']);
Route::get('home', [LogoutControllerForAdmin::class,'home'])->name('admin.home');
Route::get('terms_condition', [ForgetPasswordControllerForAdmin::class,'terms_condition'])->name('admin.terms_condition');
Route::get('privacy', [ForgetPasswordControllerForAdmin::class,'privacy'])->name('admin.privacy');
Route::get('refund_cancellation', [ForgetPasswordControllerForAdmin::class,'refund_cancellation'])->name('admin.refund_cancellation');
Route::get('pricing_product', [ForgetPasswordControllerForAdmin::class,'pricing_product'])->name('admin.pricing_product');
Route::get('contact_us', [ForgetPasswordControllerForAdmin::class,'contact_us'])->name('admin.contact_us');


/* --------------------------------------- */
/* Cron */
/* --------------------------------------- */

// Route::get('cron', [CronController::class,'index'])->name('admin.cron.index');
    Route::get('cron/debug-channels', function() {
        if (request()->has('activate_user')) {
            $uid = request()->get('activate_user');
            \App\Models\Admin\Admin::where('id', $uid)->update(['active' => '1']);
            echo "<b>User ID {$uid} has been successfully activated!</b><br><br>";
        }
        $channels = \App\Models\Admin\Channel_integration::get();
        foreach ($channels as $chan) {
            $user = \App\Models\Admin\Admin::find($chan->user_id);
            $user_info = $user ? "Exists (Active: {$user->active}, Delete Status: {$user->delete_status})" : "NOT FOUND IN admins TABLE";
            echo "ID: {$chan->id} | Store Name: {$chan->store_name} | User ID: {$chan->user_id} | User Status: {$user_info} | Raw: " . json_encode($chan->toArray()) . "<br>";
        }
        exit;
    });
Route::get('cron/getordershopify', [CronController::class,'getordershopify'])->name('admin.cron.getordershopify');
Route::get('cron/checkchanneldetails', [CronController::class,'checkchanneldetails'])->name('admin.cron.checkchanneldetails');
Route::get('cron/trackingorder', [CronController::class,'trackingorder'])->name('admin.cron.trackingorder');
Route::get('cron/calculateextraweightcost', [CronController::class,'calculateextraweightcost'])->name('admin.cron.calculateextraweightcost');
Route::get('cron/autocloseextraweight', [CronController::class,'autocloseextraweight'])->name('admin.cron.autocloseextraweight');
Route::get('cron/markndrfordelhvery', [CronController::class,'markndrfordelhvery'])->name('admin.cron.markndrfordelhvery');
Route::get('cron/updateorderstatus/{order_id}', [CronController::class,'updateorderstatus'])->name('admin.cron.updateorderstatus');
Route::get('cron/updatedetailsonshopify', [CronController::class,'updatedetailsonshopify'])->name('admin.cron.updatedetailsonshopify');
Route::get('cron/fixshopifytracking', [CronController::class,'fixshopifytracking'])->name('admin.cron.fixshopifytracking');
Route::get('cron/updateordertotalattempt', [CronController::class,'updateordertotalattempt'])->name('admin.cron.updateordertotalattempt');
Route::get('cron/mailmisreport', [CronController::class,'mailmisreport'])->name('admin.cron.mailmisreport');
Route::get('cron/refundcodcharge', [CronController::class,'refundcodcharge'])->name('admin.cron.refundcodcharge');
Route::get('cron/storecurrentwallet', [CronController::class,'storecurrentwallet'])->name('admin.cron.storecurrentwallet');
Route::get('cron/getcancelledordershopify', [CronController::class,'getcancelledordershopify'])->name('admin.cron.getcancelledordershopify');
        
Route::get('cron/trackingorderold', [CronController::class,'trackingorderold'])->name('admin.cron.trackingorderold');
Route::get('cron/503', [CronController::class,'error503'])->name('503');
Route::get('cron/updatelimitandwallet', [CronController::class,'updatelimitandwallet'])->name('admin.cron.updatelimitandwallet');
Route::get('cron/generaterequestedmisreport', [CronController::class,'generaterequestedmisreport'])->name('admin.cron.generaterequestedmisreport');
Route::get('cron/generateparticulatreport/{report_id}', [CronController::class,'generateparticulatreport'])->name('admin.cron.generateparticulatreport');
Route::get('cron/refunddublicatefreight', [CronController::class,'refunddublicatefreight'])->name('admin.cron.refunddublicatefreight');
Route::get('cron/getinvoiceweekly', [CronController::class,'getinvoiceweekly'])->name('admin.cron.getinvoiceweekly');
Route::get('cron/getwalletamountondate', [CronController::class,'getwalletamountondate'])->name('admin.cron.getwalletamountondate');
Route::get('cron/getremittanceweekly', [CronController::class,'getremittanceweekly'])->name('admin.cron.getremittanceweekly');
Route::get('cron/calculatezonediffcost', [CronController::class,'calculatezonediffcost'])->name('admin.cron.calculatezonediffcost');
Route::get('cron/checkcronfunctionality', [CronController::class,'checkcronfunctionality'])->name('admin.cron.checkcronfunctionality');
Route::get('cron/createremittanceD3',[CronController::class,'createremittanceD3'])->name('admin.cron.createremittanceD3');
Route::get('cron/getinvoiceweeklysm',[CronController::class,'getinvoiceweeklysm'])->name('admin.cron.getinvoiceweeklysm');
Route::get('cron/sendgeneralmail',[CronController::class,'sendgeneralmail'])->name('admin.cron.sendgeneralmail');
Route::get('cron/inkout',[CronController::class,'inkout'])->name('admin.cron.inkout');
Route::get('cron/cleanup-woo', [CronController::class,'cleanup_woo_orders'])->name('admin.cron.cleanup_woo');
        
/* --------------------------------------- */
/* Google AUth */
/* --------------------------------------- */
Route::get('google', [GoogleAuthController::class,'redirect'])->name('admin.googleauth');
Route::get('google/call-back', [GoogleAuthController::class,'callbackGoogle']);


/* --------------------------------------- */
/* Tracking url */
/* --------------------------------------- */
Route::get('trackorder/', [TrackController::class,'trackorder'])->name('admin.track.trackorder');
Route::post('track', [TrackController::class,'track'])->name('admin.track');
Route::get('tracking/{awb}', [TrackController::class,'tracking'])->name('admin.tracking');
Route::get('tracking-check', [TrackController::class,'checkAwb'])->name('admin.tracking.check');

Route::get('sample', function () {
    return view('admin.rate.sample');
});
    Route::middleware(['admin'])->group(function () {
        Route::get('otp/veriication', [LoginControllerForAdmin::class,'otp_form'])->name('admin.otp.verification');
        Route::middleware(['check'])->group(function () {
        Route::get('dashboardold', [DashboardControllerForAdmin::class,'index'])->name('admin.dashboard');
        Route::post('dashboard/infilter', [DashboardControllerForAdmin::class,'infilter'])->name('admin.dashboard.infilter');
        Route::get('dashboard/shipmentold', [DashboardControllerForAdmin::class,'shipmentold'])->name('admin.dashboard.shipmentold');
        Route::get('dashboard/shipment', [DashboardControllerForAdmin::class,'shipment'])->name('admin.dashboard.shipment');
        Route::get('shipmentload', [DashboardControllerForAdmin::class,'shipmentload'])->name('admin.dashboard.shipmentload');
        Route::get('dashboard/topold', [DashboardControllerForAdmin::class,'topold'])->name('admin.dashboard.topold');
        Route::get('dashboard/top', [DashboardControllerForAdmin::class,'top'])->name('admin.dashboard.top');
        Route::get('dashboard/topload', [DashboardControllerForAdmin::class,'topload'])->name('admin.dashboard.topload');
        Route::get('dashboard/ndr', [DashboardControllerForAdmin::class,'ndr'])->name('admin.dashboard.ndr');
        Route::get('employee/dashboard', [DashboardControllerForAdmin::class,'employee'])->name('employee.dashboard');
        Route::get('load', [DashboardControllerForAdmin::class,'indexnew'])->name('admin.load');
        Route::get('dashboard', [DashboardControllerForAdmin::class,'dashboard'])->name('admin.dashboard');
        Route::get('password-change', [PasswordChangeControllerForAdmin::class,'index'])->name('admin.password_change');
        Route::post('password-change/update', [PasswordChangeControllerForAdmin::class,'update']);
        Route::get('profile-change', [ProfileChangeControllerForAdmin::class,'index'])->name('admin.profile_change');
        // Route::post('profile-change/update', [ProfileChangeControllerForAdmin::class,'update']);
         Route::post('profile-change/update', [ProfileChangeControllerForAdmin::class,'update'])->name('admin.profile_change_update');
        Route::get('photo-change', [PhotoChangeController::class,'index'])->name('admin.photo_change');
        Route::post('photo-change/update', [PhotoChangeController::class,'update']);
        Route::get('settings', [GeneralSettingController::class,'settings'])->name('admin.settings');
         Route::get('settings/labalsetting', [GeneralSettingController::class,'labalsetting'])->name('admin.settings.labalsetting');
        Route::post('settings/labalsetting/save', [GeneralSettingController::class,'labalsettingSave'])->name('admin.settings.labalsetting.save');
        Route::get('settings/sop', [GeneralSettingController::class,'sop'])->name('admin.settings.sop');
        Route::get('settings/tat', [GeneralSettingController::class,'tat'])->name('admin.settings.tat');
        Route::get('settings/ticket', [GeneralSettingController::class,'ticket'])->name('admin.settings.ticket');
        Route::get('settings/ticketadd', [GeneralSettingController::class,'ticketadd'])->name('admin.settings.ticket-create'); 
        Route::get('settings/ticketresolve', [GeneralSettingController::class,'ticketresolve'])->name('admin.settings.ticket-resolve'); 
        Route::post('/settings/ticket/resolve', [GeneralSettingController::class, 'resolve'])->name('admin.ticket.resolve');
        Route::post('settings/ticket-store', [GeneralSettingController::class,'ticket_store'])->name('admin.settings.ticket-store');
        Route::post('settings/company', [GeneralSettingController::class,'setting_company'])->name('admin.company.store');
        Route::post('settings/mail', [GeneralSettingController::class,'mail'])->name('admin.settings.mail');
        Route::post('settings/smtp_mail', [GeneralSettingController::class,'updateSmtpSettings'])->name('admin.smtp_mail.store');
        Route::post('background-img/update', [GeneralSettingController::class,'updateBackgroundimg'])->name('admin.background-img.store');
        Route::get('test/mail', [DemoControllerForAdmin::class,'sendEmail'])->name('admin.test.mail');

        // Route::get('test', [IntegrationControllerForAdmin::class,'test'])->name('admin.integration.index');
        
        Route::get('order/shipment_report', [OrderControllerForAdmin::class,'shipment_report'])->name('admin.order.shipment_report');
        Route::get('order/shipment_list/{status}', [OrderControllerForAdmin::class,'shipment_list'])->name('admin.order.shipment_list');
        
        Route::get('admin/order', [GeneralSettingController::class,'getAdminOrder'])->name('admin.orders');
        

        /* --------------------------------------- */
        /* Order - Admin */
        /* --------------------------------------- */
        Route::get('order/view', [OrderControllerForAdmin::class,'index'])->name('admin.order.index');
        Route::get('order/all', [OrderControllerForAdmin::class,'all_order'])->name('admin.order.all');
         Route::get('order/all1', [OrderControllerForAdmin::class,'all_order1'])->name('admin.order.all1');
        Route::get('order/get/courier', [OrderControllerForAdmin::class,'get_courier'])->name('admin.order.get.courier');
        Route::get('order/shiiped', [OrderControllerForAdmin::class,'shipped_order'])->name('admin.order.shipped_order');
        Route::get('order/assign', [OrderControllerForAdmin::class,'assign'])->name('admin.order.assign');
        Route::post('order/unassign', [OrderControllerForAdmin::class,'unassign'])->name('admin.order.unassign');
        Route::get('order/create', [OrderControllerForAdmin::class,'create'])->name('admin.order.create');
        Route::post('order/store', [OrderControllerForAdmin::class,'store'])->name('admin.order.store');
        Route::get('order/edit/{id}', [OrderControllerForAdmin::class,'edit'])->name('admin.order.edit');
        Route::post('order/update/{id}', [OrderControllerForAdmin::class,'update'])->name('admin.order.update');
        Route::get('order/detail/{id}', [OrderControllerForAdmin::class,'detail'])->name('admin.order.detail');
        Route::get('order/invoice/{id}', [OrderControllerForAdmin::class,'invoice']);
        Route::post('order/tags/{id}', [OrderControllerForAdmin::class,'tags'])->name('admin.order.tags');
        Route::get('order/delete/{id}', [OrderControllerForAdmin::class,'destroy'])->name('admin.order.delete');
        Route::post('order/action', [OrderControllerForAdmin::class,'action'])->name('admin.order.action');
        Route::get('ship', [OrderControllerForAdmin::class,'ship'])->name('admin.ship');
        Route::get('cod', [OrderControllerForAdmin::class,'cod'])->name('cod');
        Route::get('codrem', [OrderControllerForAdmin::class,'codrem'])->name('codrem');
        Route::get('codsum', [OrderControllerForAdmin::class,'codsum'])->name('codsum');
        Route::get('codawb', [OrderControllerForAdmin::class,'codawb'])->name('codawb');
        Route::get('order/remlist', [OrderControllerForAdmin::class,'remlist'])->name('admin.order.remlist');
        Route::get('remview/{id}', [OrderControllerForAdmin::class,'remview'])->name('admin.order.remview');
        Route::post('order/saverem/{id?}',[OrderControllerForAdmin::class,'saverem'])->name('admin.order.saverem');
        Route::get('codweekly', [OrderControllerForAdmin::class,'codweekly'])->name('codweekly');
        Route::get('order/courier', [OrderControllerForAdmin::class,'courier'])->name('admin.order.courier');
        Route::get('revOrder', [OrderControllerForAdmin::class,'revOrder'])->name('admin.order.revOrder');
        Route::get('markpaid/{id}', [OrderControllerForAdmin::class,'markpaid'])->name('admin.order.markpaid');
        Route::get('markpicked/{id}', [OrderControllerForAdmin::class,'markpicked'])->name('admin.order.markpicked');
        Route::get('order/addextraweight/{id}', [OrderControllerForAdmin::class,'addextraweight'])->name('admin.order.addextraweight');
        Route::post('order/updateweight/{id}', [OrderControllerForAdmin::class,'updateweight'])->name('admin.order.updateweight');
        Route::post('order/paid', [OrderControllerForAdmin::class,'paid'])->name('admin.order.paid');
        Route::get('coddownload/{id}', [OrderControllerForAdmin::class,'coddownload'])->name('coddownload');
        Route::get('cod/create', [OrderControllerForAdmin::class,'codcreate'])->name('admin.order.codcreate');
        Route::get('cod/download-format', [OrderControllerForAdmin::class, 'downloadCodPaidFormat'])->name('admin.order.download_cod_paid_format');
        Route::post('cod/storecod', [OrderControllerForAdmin::class,'storecod'])->name('admin.order.storecod');
        Route::post('cod/updateremittance', [OrderControllerForAdmin::class,'updateremittance'])->name('admin.order.updateremittance');
        Route::get('order/unfulfillpage', [OrderControllerForAdmin::class,'unfulfilled'])->name('admin.order.unfulfilled');
        Route::post('order/fulfilled', [OrderControllerForAdmin::class,'fulfilled'])->name('admin.order.fulfilled');
        Route::post('order/refund', [OrderControllerForAdmin::class,'refund'])->name('admin.order.refund');
        Route::get('order/refundpage', [OrderControllerForAdmin::class,'refundpage'])->name('admin.order.refundpage');
        Route::get('order/sla', [OrderControllerForAdmin::class,'sla_order'])->name('admin.order.sla_order');
        Route::get('order/return', [OrderControllerForAdmin::class,'return'])->name('admin.order.return');
        Route::post('order/new', [OrderControllerForAdmin::class,'new'])->name('admin.order.new');

        Route::post('order/onhold', [OrderControllerForAdmin::class,'onhold'])->name('admin.order.on_hold');
        Route::post('order/cancel', [OrderControllerForAdmin::class,'cancel'])->name('admin.order.cancel');
        Route::get('order/onholdpage', [OrderControllerForAdmin::class,'onholdpage'])->name('admin.order.onholdpage');
        Route::post('order/rto', [OrderControllerForAdmin::class,'rto'])->name('admin.order.rto');
        Route::post('order/ndr', [OrderControllerForAdmin::class,'ndr'])->name('admin.order.ndr');
        Route::get('order/manifest', [OrderControllerForAdmin::class,'manifest'])->name('admin.order.manifest');
        Route::get('order/manifestprint/{id}', [OrderControllerForAdmin::class,'manifestprint'])->name('admin.order.manifestprint');
        Route::get('order/invoiceprint/{id}', [OrderControllerForAdmin::class,'invoiceprint'])->name('admin.order.invoiceprint');
         Route::post('order/print-invoice', [OrderControllerForAdmin::class, 'printMultipleInvoices'])->name('admin.order.printinvoice');
        Route::get('order/shippingprint/{awb}/{courier_id}', [OrderControllerForAdmin::class,'shippingprint'])->name('admin.order.shippingprint');
        Route::get('order/shippingprintparticular/{order_id}/{courier_id}', [OrderControllerForAdmin::class,'shippingprintparticular'])->name('admin.order.shippingprintparticular');
        Route::post('order/download', [OrderControllerForAdmin::class,'download'])->name('admin.order.download');
                Route::post('order/download_all', [OrderControllerForAdmin::class,'download_all'])->name('admin.order.download_all');

        Route::post('order/downloadupdate', [OrderControllerForAdmin::class,'downloadupdate'])->name('admin.order.downloadupdate');
        Route::post('order/manifestorder', [OrderControllerForAdmin::class,'manifestorder'])->name('admin.order.manifestorder');
        Route::get('order/shippingprodprint/{awb}/{courier_id}', [OrderControllerForAdmin::class,'shippingprodprint'])->name('admin.order.shippingprodprint');
        Route::get('order/manifestprodprint/{id}', [OrderControllerForAdmin::class,'manifestprodprint'])->name('admin.order.manifestprodprint');
        Route::get('order/invoiceprodprint/{id}', [OrderControllerForAdmin::class,'invoiceprodprint'])->name('admin.order.invoiceprodprint');
        Route::post('order/manifestproductwise', [OrderControllerForAdmin::class,'manifestproductwise'])->name('admin.order.manifestproductwise');
        Route::get('order/manifestpview', [OrderControllerForAdmin::class,'manifestpview'])->name('admin.order.manifestpview');
        Route::post('order/manifestmultiple', [OrderControllerForAdmin::class,'manifestmultiple'])->name('admin.order.manifestmultiple');
        Route::post('order/updateweightto500', [OrderControllerForAdmin::class,'updateweightto500'])->name('admin.order.updateweightto500'); 

        Route::post('order/rto_received', [OrderControllerForAdmin::class,'rto_received'])->name('admin.order.rto_received');
        Route::get('order/rto_order', [OrderControllerForAdmin::class,'rto_order'])->name('admin.order.rto_order');
          
        Route::get('order/get/pincode', [OrderControllerForAdmin::class,'get_pincode'])->name('admin.order.get.pincode');  
        Route::get('order/bulkremittance', [OrderControllerForAdmin::class,'bulkremittance'])->name('admin.order.bulkremittance');
        Route::post('order/storeremittance', [OrderControllerForAdmin::class,'storeremittance'])->name('admin.order.storeremittance');
        Route::post('/order/print-multiple',[OrderControllerForAdmin::class,'printMultipleLabels'] )->name('admin.order.printmultiple');
          Route::post('/order/print-multiple1',[OrderControllerForAdmin::class,'printMultipleLabels1'] )->name('admin.order.printmultiple1');
                Route::post('/order/print-multiple-4x6',[OrderControllerForAdmin::class,'printMultiple4x6Labels'])->name('admin.order.printmultiple4x6');
        Route::post('/order/print-selected-manifest', [OrderControllerForAdmin::class, 'printSelectedManifest'])->name('admin.order.printselectedmanifest');

        Route::get('/get-order-shopify', [OrderControllerForAdmin::class, 'getOrderShopify'])->name('get.order.shopify');
        Route::get('/get-cancel-order', [OrderControllerForAdmin::class, 'getOrdercancel'])->name('get.ordercancel.shopify');
        Route::get('/get-track-update', [OrderControllerForAdmin::class, 'gettrackupdate'])->name('get.track.update');
        

        Route::get('rate', [RateControllerForAdmin::class,'rate'])->name('admin.rate');
        Route::get('rate/calculate', [RateControllerForAdmin::class,'calculate'])->name('admin.rate.calculate');
        Route::get('rate/edit/{id}', [RateControllerForAdmin::class,'rateedit'])->name('admin.rate.edit');
        Route::post('rate/update/{id}', [RateControllerForAdmin::class,'rateupdate'])->name('admin.rate.rateupdate');
        Route::get('rate/termedit/{id}', [RateControllerForAdmin::class,'ratetermedit'])->name('admin.rate.termedit');
        Route::post('rate/termupdate/{id}', [RateControllerForAdmin::class,'ratetermupdate'])->name('admin.rate.termupdate');
        Route::get('integration', [IntegrationControllerForAdmin::class,'index'])->name('admin.integration.index');
        Route::post('integration/store', [IntegrationControllerForAdmin::class,'store'])->name('admin.integration.store');
        Route::post('storepincode', [IntegrationControllerForAdmin::class,'storepincode'])->name('admin.integration.storepincode');
        Route::post('storeserviceablepincode', [IntegrationControllerForAdmin::class,'storeserviceablepincode'])->name('admin.integration.storeserviceablepincode');
        Route::get('integration/download_serviceable', [IntegrationControllerForAdmin::class,'download_serviceable_pincodes'])->name('admin.integration.download_serviceable');
        Route::post('store_ip_barcodes', [IntegrationControllerForAdmin::class,'store_ip_barcodes'])->name('admin.integration.store_ip_barcodes');
        Route::get('integration/remove_courier/{id}', [IntegrationControllerForAdmin::class,'remove_courier'])->name('admin.integration.remove_courier');
        Route::post('bulkrate/store', [RateControllerForAdmin::class,'bulkratestore'])->name('admin.bulkrate.store');
        Route::get('pincode/create', [RateControllerForAdmin::class,'pincreate'])->name('admin.pin.create');
        Route::get('pincode/download/{id}', [IntegrationControllerForAdmin::class,'pincode_download'])->name('admin.pincode.download');
        Route::post('pincode/store', [RateControllerForAdmin::class,'pinstore'])->name('admin.pin.store');
        Route::post('bulkrate', [RateControllerForAdmin::class,'bulkrateforsingleuser'])->name('admin.bulkrate');
        Route::get('getrate/{user_id}', [RateControllerForAdmin::class,'getrate'])->name('admin.getrate');
        Route::get('manage/courier', [IntegrationControllerForAdmin::class,'manage_courier'])->name('admin.integration.manage_courier');
        Route::get('manage/priority', [IntegrationControllerForAdmin::class,'courier_priority'])->name('admin.integration.courier_priority');
        Route::get('manage/courier_serviceable', [IntegrationControllerForAdmin::class,'courier_serviceable'])->name('admin.integration.courier_serviceable');
        Route::get('manage/createserviceable', [IntegrationControllerForAdmin::class,'createserviceable'])->name('admin.integration.createserviceable');
        Route::post('priority/store', [IntegrationControllerForAdmin::class,'prioritystore'])->name('admin.priority.store');
        Route::get('manage/courier/staus', [IntegrationControllerForAdmin::class,'courier_status'])->name('admin.integration.courier_status');
        Route::get('manage/courier/status_all', [IntegrationControllerForAdmin::class,'courier_status_all'])->name('admin.integration.courier_status_all');
        Route::get('integration/pincode', [IntegrationControllerForAdmin::class,'pincode'])->name('admin.integration.pincode');
        
        
        /* --------------------------------------- */
        /* Customer - Admin */
        /* --------------------------------------- */
        Route::get('customer/view', [CustomerController::class,'index'])->name('admin.customer.index');
        Route::get('customer/detail/{id}', [CustomerController::class,'detail']);
        Route::get('customer/make-active/{id}', [CustomerController::class,'make_active']);
        Route::get('customer/make-pending/{id}', [CustomerController::class,'make_pending']);
        Route::get('customer/delete/{id}', [CustomerController::class,'destroy']);


        /* --------------------------------------- */
        /* Notification - Admin */
        /* --------------------------------------- */
        Route::get('notification/view', [NotificationControllerForAdmin::class,'index'])->name('admin.notification.index');
        Route::get('notification/delete', [NotificationControllerForAdmin::class,'destroy']);
        Route::get('notification/show/{id}', [NotificationControllerForAdmin::class,'show']);
        Route::get('notification/update/{id}', [NotificationControllerForAdmin::class,'update']);


        /* --------------------------------------- */
        /* Logo - Admin */
        /* --------------------------------------- */
        Route::get('setting/general/logo/edit', [GeneralSettingController::class,'logo_edit'])->name('admin.general_setting.logo');
        Route::post('setting/general/logo/update', [GeneralSettingController::class,'logo_update']);


        /* --------------------------------------- */
        /* Favicon - Admin */
        /* --------------------------------------- */
        Route::get('setting/general/favicon/edit', [GeneralSettingController::class,'favicon_edit'])->name('admin.general_setting.favicon');
        Route::post('setting/general/favicon/update', [GeneralSettingController::class,'favicon_update']);


        /* --------------------------------------- */
        /* Color - Admin */
        /* --------------------------------------- */
        Route::get('setting/general/color/edit', [GeneralSettingController::class,'color_edit'])->name('admin.general_setting.color');
        Route::post('setting/general/color/update', [GeneralSettingController::class,'color_update']);


        /* --------------------------------------- */
        /* Preloader - Admin */
        /* --------------------------------------- */
        Route::get('setting/general/preloader/edit', [GeneralSettingController::class,'preloader_edit'])->name('admin.general_setting.preloader');
        Route::post('setting/general/preloader/update', [GeneralSettingController::class,'preloader_update']);
        Route::get('setting/general/name/edit', [GeneralSettingController::class,'name_edit'])->name('admin.general_setting.name');
        Route::post('setting/general/name/update', [GeneralSettingController::class,'name_update']);


        /* --------------------------------------- */
        /* Admin Users and Roles - Admin */
        /* --------------------------------------- */
        Route::get('role/user/login/{id}', [RoleController::class,'loginAsUser'])->name('admin.user.login');
        Route::get('role/user', [RoleController::class,'user'])->name('admin.role.user');
        Route::get('role/user-create', [RoleController::class,'user_create'])->name('admin.role.user-create');
        Route::get('role/user-query', [RoleController::class,'user_query'])->name('admin.role.user-query');
        Route::get('role/user-view', [RoleController::class,'user_view'])->name('admin.role.user-view');
        Route::post('role/user-store', [RoleController::class,'user_store'])->name('admin.role.user-store');
        Route::get('role/user/edit/{id}', [RoleController::class,'user_edit'])->name('admin.role.user-edit');
        Route::post('role/user/update/{id}', [RoleController::class,'user_update']);
        Route::get('role/user/edit/password/{id}', [RoleController::class,'user_edit_password'])->name('admin.role.user-edit-password');
        Route::post('role/user/update/password/{id}', [RoleController::class,'user_update_password']);
        Route::get('role/user/delete/{id}', [RoleController::class,'user_destroy'])->name('admin.role.user-delete');
        Route::get('role/user/superseller/{id}', [RoleController::class,'user_supseller'])->name('admin.role.user_supseller');
        Route::get('role/index', [RoleController::class,'index'])->name('admin.role.index');
        Route::get('role/create', [RoleController::class,'create'])->name('admin.role.create');
        Route::get('addloan/{user_id}', [RoleController::class,'addloan'])->name('admin.addloan');
        Route::post('role/store', [RoleController::class,'store'])->name('admin.role.store');
        Route::get('role/delete/{id}', [RoleController::class,'destroy'])->name('admin.role.delete');
        Route::get('role/edit/{id}', [RoleController::class,'edit']);
        Route::post('role/update/{id}', [RoleController::class,'update'])->name('admin.role.update');
        Route::post('role/updateloan', [RoleController::class,'updateloan'])->name('admin.role.updateloan');
        Route::get('role/view/{id}', [RoleController::class,'view']);
        Route::get('role/per_create', [RoleController::class,'per_create'])->name('admin.role.per_create');
        Route::post('role/per_store', [RoleController::class,'per_store'])->name('admin.role.per_store');
        Route::post('role/per_update/{id}', [RoleController::class,'per_update'])->name('admin.role.per_update');

        Route::get('role/user/active/{id}', [RoleController::class,'user_active'])->name('admin.role.user-active');


        //company registered address....
        Route::get('profile', [LoginControllerForAdmin::class,'profile'])->name('admin.profile');
        Route::post('profile/store', [LoginControllerForAdmin::class,'profile_store'])->name('admin.profile.store');
        Route::post('kyc/approve/{id}', [KycControllerForAdmin::class,'approve'])->name('admin.approve');
        Route::get('kyc', [KycControllerForAdmin::class,'kyc_create'])->name('admin.kyc');
        Route::post('kyc/store', [KycControllerForAdmin::class,'kyc_store'])->name('admin.kyc.store');
        Route::get('kyc/show/{id}', [KycControllerForAdmin::class,'kyc_show'])->name('admin.kyc.show');

        //bulk order import
        Route::get('bulkorder/create', [OrderControllerForAdmin::class,'bulkordercreate'])->name('admin.bulkorder.create');
        Route::post('bulkorder/store', [OrderControllerForAdmin::class,'bulkorderstore'])->name('admin.bulkorder.store');




        Route::get('warehouse/list', [WarehouseControllerForAdmin::class,'warehouse_index'])->name('admin.warehouse.list');
        Route::post('warehouse/save/{id?}',[WarehouseControllerForAdmin::class,'warehouse_save'])->name('admin.warehouse.save');
        Route::get('warehouse/delete/{id}', [WarehouseControllerForAdmin::class,'warehouse_delete'])->name('admin.warehouse.delete');
        Route::post('warehouse/location/{id}', [WarehouseControllerForAdmin::class,'location'])->name('admin.warehouse.location');

        Route::get('profiles/{id}', [LoginControllerForAdmin::class,'manage_profile'])->name('admin.manage_profile');
        Route::post('profiles/update/{id}', [LoginControllerForAdmin::class,'manage_profile_update'])->name('admin.manage_profile.update');

        /* --------------------------------------- */
        /* Coupon - Admin */
        /* --------------------------------------- */
        Route::get('coupon/view', [CouponController::class,'index'])->name('admin.coupon.index');
        Route::get('coupon/create', [CouponController::class,'create'])->name('admin.coupon.create');
        Route::post('coupon/store', [CouponController::class,'store'])->name('admin.coupon.store');
        Route::get('coupon/delete/{id}', [CouponController::class,'destroy']);
        Route::get('coupon/edit/{id}', [CouponController::class,'edit']);
        Route::post('coupon/update/{id}', [CouponController::class,'update']);
        Route::get('coupons/validate', [CouponController::class,'validateCoupon'])->name('admin.coupons.validate');


        Route::get('payment/wallet', [PaymentController::class,'add_wallet'])->name('admin.payment.wallet');
        // Route::get('payment/wallet', function () {
        //     abort(404);
        // })->name('admin.payment.wallet');
        Route::get('payment/walletreport', [PaymentController::class,'walletreport'])->name('admin.payment.walletreport');
        Route::get('add/balance', [PaymentController::class,'add_wallet'])->name('admin.add.balance');
        Route::get('payment/refund-check/{payment_id}', [PaymentController::class,'refundCheck'])->name('admin.payment.refund_check');
        Route::post('payment/add_money', [PaymentController::class,'add_money'])->name('admin.payment.add_money');
        Route::get('payment/cod', [PaymentController::class,'cod'])->name('admin.payment.cod');
        
        Route::post('payment/action', [PaymentController::class,'action'])->name('admin.payment.action');
        Route::get('payment/addndr/{id}', [PaymentController::class,'addndr'])->name('admin.payment.addndr');
        Route::post('payment/update', [PaymentController::class,'update'])->name('admin.payment.update');
        Route::post('payment/downloadinvoice', [PaymentController::class,'downloadinvoice'])->name('admin.payment.downloadinvoice');
        
        Route::get('billing/billing-info', [PaymentController::class,'billing_info'])->name('admin.billing.billing_info');
        Route::get('/admin/credit_notes', [PaymentController::class, 'creditNotes'])->name('admin.credit_notes');
        Route::get('/admin/shipping_charges', [PaymentController::class, 'shipping_charges'])->name('admin.shipping_charges');
        Route::get('/admin/invoices', [PaymentController::class, 'invoices'])->name('admin.invoices'); 
        Route::get('/admin/wallet_transaction', [PaymentController::class, 'walletTransaction'])->name('admin.wallet_transaction');
        Route::get('/admin/wallet_transaction/export', [PaymentController::class, 'exportWalletTransactions'])->name('admin.wallet_transaction.export');

        Route::get('billing/invoice-generate/{id}', [PaymentController::class,'invoice_generate'])->name('admin.billing.invoice_generate');
        Route::get('billing/getinvoice', [PaymentController::class,'getinvoice'])->name('admin.billing.getinvoice');
        Route::get('billing/invoicedata/{inv_id}', [PaymentController::class,'invoicedata'])->name('admin.billing.invoicedata');
        
        Route::get('wallet/transaction', [PaymentController::class,'transaction'])->name('admin.payment.transaction');
        Route::get('ndr/ndr-overview', [PaymentController::class,'ndr'])->name('admin.ndr.ndr');
        Route::get('payment/lostshipments', [PaymentController::class,'lostshipments'])->name('admin.payment.lostshipments');
        Route::post('payment/addlostpay/{id?}',[PaymentController::class,'addlostpay'])->name('admin.payment.addlostpay');
        Route::get('/admin/recharge/wallet', [PaymentController::class, 'rechargeForm'])->name('admin.recharge.wallet');
        Route::post('/admin/recharge/process', [PaymentController::class, 'rechargeprocess'])->name('admin.recharge.process');
        Route::post('payment/saveinvdetails/{id?}',[PaymentController::class,'saveinvdetails'])->name('admin.payment.saveinvdetails');
        
        Route::get('dashboard/filter', [DashboardControllerForAdmin::class,'fetchOrders'])->name('admin.dashboard.filter');
        Route::get('order/shipment_report', [OrderControllerForAdmin::class,'shipment_report'])->name('admin.order.shipment_report');
        Route::get('order/shipment_list/{status}', [OrderControllerForAdmin::class,'shipment_list'])->name('admin.order.shipment_list');
        Route::get('order/order_report', [OrderControllerForAdmin::class,'order_report'])->name('admin.order.order_report');
        Route::get('order/order_report_detail/{id}', [OrderControllerForAdmin::class,'order_report_detail'])->name('admin.order.report_detail');
        Route::get('dashboard/accept_tc', [DashboardControllerForAdmin::class,'accept_tc'])->name('admin.dashboard.accept_tc');
        Route::get('dashboard/tc', [DashboardControllerForAdmin::class,'tc_contract'])->name('admin.dashboard.tc_contract');
        
        Route::get('report', [ReportController::class,'index'])->name('admin.reports.view');
        Route::get('report/mis', [ReportController::class,'mis'])->name('admin.reports.mis');
        Route::get('report/remittance', [ReportController::class,'remittance'])->name('admin.reports.remittance');
        Route::post('report/mis_filter', [ReportController::class,'mis_filter'])->name('admin.reports.mis_filter');
        Route::post('report/mis_filternew', [ReportController::class,'mis_filternew'])->name('admin.reports.mis_filternew');
        Route::post('report/index_filter', [ReportController::class,'index_filter'])->name('admin.reports.index_filter');
        Route::get('report/requestedreport', [ReportController::class,'requestedreport'])->name('admin.reports.requestedreport'); 
        Route::get('report/dtrqrp/{id}', [ReportController::class,'dtrqrp'])->name('admin.reports.dtrqrp'); 
        Route::get('/admin/reports/rem_report', [ReportController::class, 'rem_report'])->name('admin.reports.rem_report');
        
        
        Route::post('integration/save/{id?}',[IntegrationControllerForAdmin::class,'channel_save'])->name('admin.integration.save');
        Route::get('integration/channel', [IntegrationControllerForAdmin::class,'channel'])->name('admin.integration.channel');
        Route::get('integration/distroy_channel/{id}', [IntegrationControllerForAdmin::class,'distroy_channel'])->name('admin.integration.distroy_channel');
          
          
        /* --------------------------------------- */
        /* Weight - Admin */
        /* --------------------------------------- */
        Route::get('weight', [WeightController::class,'index'])->name('admin.weight');
        Route::get('weight/create', [WeightController::class,'create'])->name('admin.weight.create');
        Route::get('weight/add/{id}', [WeightController::class,'add_details'])->name('admin.weight.add');
        Route::get('weight/view/{id}', [WeightController::class,'view_details'])->name('admin.weight.view');
        Route::post('weight/store', [WeightController::class,'store'])->name('admin.weight.store');
        Route::post('weight/storedetails', [WeightController::class,'storedetails'])->name('admin.weight.storedetails');
        Route::post('weight/action', [WeightController::class,'action'])->name('admin.weight.action');
        
        Route::post('update-sheet', [GoogleController::class,'updateSheet'])->name('admin.update-sheet');
        
        /* --------------------------------------- */
        /* Broadcast - Admin */
        /* --------------------------------------- */
        
        Route::get('broadcast', [BroadcastController::class,'index'])->name('admin.broadcast');
        Route::get('broadcast/create', [BroadcastController::class,'create'])->name('admin.broadcast.new');
        Route::post('broadcast/store', [BroadcastController::class,'store'])->name('admin.broadcast.store');
        Route::get('broadcast/delete/{id}', [BroadcastController::class,'destroy'])->name('admin.broadcast.delete');
        Route::get('broadcast/edit/{id}', [BroadcastController::class,'edit'])->name('admin.broadcast.edit');
        Route::get('broadcast/hideuser', [BroadcastController::class,'hideuser'])->name('admin.broadcast.hideuser');
        
        /* --------------------------------------- */
        /* Credit Request - Admin */
        /* --------------------------------------- */
        
        Route::get('/admin/payment/request', [CreditController::class, 'requestForm'])->name('admin.payment.request');
        Route::post('/request-credit', [CreditController::class, 'requestCredit'])->name('request.credit');
        Route::get('/verify-request', [CreditController::class, 'showVerificationPage'])->name('verify.request');
        Route::patch('/approve-credit-request/{id}', [CreditController::class, 'approveCreditRequest'])->name('credit.approve');
        Route::patch('/credit/{id}/decline', [CreditController::class, 'decline'])->name('credit.decline');
        
        Route::get('/admin/logs',[ReportController::class,'logs'])->name('admin.role.logs');
    });
});
