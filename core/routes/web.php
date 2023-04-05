<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
    Route::post('paypal', 'paypal\ProcessController@ipn')->name('paypal');
    Route::get('paypal_sdk', 'paypal_sdk\ProcessController@ipn')->name('paypal_sdk');
    Route::post('perfect_money', 'perfect_money\ProcessController@ipn')->name('perfect_money');
    Route::post('stripe', 'stripe\ProcessController@ipn')->name('stripe');
    Route::post('stripe_js', 'stripe_js\ProcessController@ipn')->name('stripe_js');
    Route::post('stripe_v3', 'stripe_v3\ProcessController@ipn')->name('stripe_v3');
    Route::post('skrill', 'skrill\ProcessController@ipn')->name('skrill');
    Route::post('paytm', 'paytm\ProcessController@ipn')->name('paytm');
    Route::post('payeer', 'payeer\ProcessController@ipn')->name('payeer');
    Route::post('paystack', 'paystack\ProcessController@ipn')->name('paystack');
    Route::post('voguepay', 'voguepay\ProcessController@ipn')->name('voguepay');
    Route::get('flutterwave/{trx}/{type}', 'flutterwave\ProcessController@ipn')->name('flutterwave');
    Route::post('razorpay', 'razorpay\ProcessController@ipn')->name('razorpay');
    Route::post('instamojo', 'instamojo\ProcessController@ipn')->name('instamojo');
    Route::get('blockchain', 'blockchain\ProcessController@ipn')->name('blockchain');
    Route::get('blockio', 'blockio\ProcessController@ipn')->name('blockio');
    Route::post('coinpayments', 'coinpayments\ProcessController@ipn')->name('coinpayments');
    Route::post('coinpayments_fiat', 'coinpayments_fiat\ProcessController@ipn')->name('coinpayments_fiat');
    Route::post('coingate', 'coingate\ProcessController@ipn')->name('coingate');
    Route::post('coinbase_commerce', 'coinbase_commerce\ProcessController@ipn')->name('coinbase_commerce');
    Route::get('mollie', 'mollie\ProcessController@ipn')->name('mollie');
    Route::post('cashmaal', 'cashmaal\ProcessController@ipn')->name('cashmaal');
});

// User Support Ticket
Route::prefix('ticket')->group(function () {
    Route::get('/', 'TicketController@supportTicket')->name('ticket');
    Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
    Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
    Route::put('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
});


/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');
        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });


    Route::middleware('admin')->group(function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
        Route::get('password', 'AdminController@password')->name('password');
        Route::post('password', 'AdminController@passwordUpdate')->name('password.update');


        //Sector
        Route::get('sector', 'ManageDoctorsController@sectors')->name('sector');
        Route::post('sector/new', 'ManageDoctorsController@storeSectors')->name('sector.store');
        Route::post('sector/update/{id}', 'ManageDoctorsController@updateSectors')->name('sector.update');
        
        //User
        Route::get('user', 'ManageUsersController@allUsers')->name('user');
        Route::get('user/detail/{id}', 'ManageUsersController@detail')->name('user.detail');
        Route::post('user/update/{id}', 'ManageUsersController@update')->name('user.update');
        Route::get('user/login/history/{id}', 'ManageUsersController@staffLoginHistory')->name('user.login.history.single');
        Route::get('user/send-email', 'ManageUsersController@showEmailAllForm')->name('user.email.all');
        Route::post('user/send-email', 'ManageUsersController@sendEmailAll')->name('user.email.send');
        Route::get('user/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('user.email.single');
        Route::post('user/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('user.email.single');
        Route::get('user/login/ipHistory/{ip}', 'ManageUsersController@loginIpHistory')->name('user.login.ipHistory');
         Route::get('user/login/history', 'ManageUsersController@loginHistory')->name('user.login.history');
        
        //Location
        Route::get('location', 'ManageDoctorsController@locations')->name('location');
        Route::post('location/new', 'ManageDoctorsController@storelocations')->name('location.store');
        Route::post('location/update/{id}', 'ManageDoctorsController@updatelocations')->name('location.update');

        // Doctors Manager
        Route::get('doctors', 'ManageDoctorsController@allDoctors')->name('doctors.all');
        Route::get('doctors/new', 'ManageDoctorsController@newDoctor')->name('doctors.new');
        Route::post('doctors/store', 'ManageDoctorsController@storeDoctor')->name('doctors.store');
        Route::get('doctor/detail/{id}', 'ManageDoctorsController@detail')->name('doctors.detail');
        Route::post('doctor/update/{id}', 'ManageDoctorsController@update')->name('doctors.update');

        Route::get('doctors/active', 'ManageDoctorsController@activeDoctors')->name('doctors.active');
        Route::get('doctors/banned', 'ManageDoctorsController@bannedDoctors')->name('doctors.banned');
        Route::get('doctors/email-verified', 'ManageDoctorsController@emailVerifiedDoctors')->name('doctors.emailVerified');
        Route::get('doctors/email-unverified', 'ManageDoctorsController@emailUnverifiedDoctors')->name('doctors.emailUnverified');
        Route::get('doctors/sms-unverified', 'ManageDoctorsController@smsUnverifiedDoctors')->name('doctors.smsUnverified');
        Route::get('doctors/sms-verified', 'ManageDoctorsController@smsVerifiedDoctors')->name('doctors.smsVerified');
        Route::get('doctors/{scope}/search', 'ManageDoctorsController@search')->name('doctors.search');


        //Doctor Login History
        Route::get('doctors/login/history/{id}', 'ManageDoctorsController@doctorLoginHistory')->name('doctors.login.history.single');
        Route::get('doctors/login/history', 'ManageDoctorsController@loginHistory')->name('doctors.login.history');
        Route::get('doctors/login/ipHistory/{ip}', 'ManageDoctorsController@loginIpHistory')->name('doctors.login.ipHistory');

        Route::get('doctors/send-email', 'ManageDoctorsController@showEmailAllForm')->name('doctors.email.all');
        Route::post('doctors/send-email', 'ManageDoctorsController@sendEmailAll')->name('doctors.email.send');
        Route::get('doctor/send-email/{id}', 'ManageDoctorsController@showEmailSingleForm')->name('doctors.email.single');
        Route::post('doctor/send-email/{id}', 'ManageDoctorsController@sendEmailSingle')->name('doctors.email.single');

        // Assistant Manager
        Route::get('assistants', 'ManageAssistantController@allAssistants')->name('assistants.all');
        Route::get('assistant/new', 'ManageAssistantController@newAssistant')->name('assistants.new');
        Route::post('assistant/store', 'ManageAssistantController@storeAssistant')->name('assistants.store');
        Route::get('assistant/detail/{id}', 'ManageAssistantController@detail')->name('assistants.detail');
        Route::post('assistant/update/{id}', 'ManageAssistantController@update')->name('assistants.update');

        Route::get('assistants/active', 'ManageAssistantController@activeAssistants')->name('assistants.active');
        Route::get('assistants/banned', 'ManageAssistantController@bannedAssistants')->name('assistants.banned');
        Route::get('assistants/email-verified', 'ManageAssistantController@emailVerifiedAssistants')->name('assistants.emailVerified');
        Route::get('assistants/email-unverified', 'ManageAssistantController@emailUnverifiedAssistants')->name('assistants.emailUnverified');
        Route::get('assistants/sms-unverified', 'ManageAssistantController@smsUnverifiedAssistants')->name('assistants.smsUnverified');
        Route::get('assistants/sms-verified', 'ManageAssistantController@smsVerifiedAssistants')->name('assistants.smsVerified');
        Route::get('assistants/{scope}/search', 'ManageAssistantController@search')->name('assistants.search');

        //Assiatant Login History
        Route::get('assistants/login/history/{id}', 'ManageAssistantController@assistantLoginHistory')->name('assistants.login.history.single');
        Route::get('assistants/login/history', 'ManageAssistantController@loginHistory')->name('assistants.login.history');
        Route::get('assistants/login/ipHistory/{ip}', 'ManageAssistantController@loginIpHistory')->name('assistants.login.ipHistory');

        Route::get('assistants/send-email', 'ManageAssistantController@showEmailAllForm')->name('assistants.email.all');
        Route::post('assistants/send-email', 'ManageAssistantController@sendEmailAll')->name('assistants.email.send');
        Route::get('assistant/send-email/{id}', 'ManageAssistantController@showEmailSingleForm')->name('assistants.email.single');
        Route::post('assistant/send-email/{id}', 'ManageAssistantController@sendEmailSingle')->name('assistants.email.single');

        // Staff Manager
        Route::get('staff', 'ManageStaffController@allStaff')->name('staff.all');
        Route::get('staff/new', 'ManageStaffController@newStaff')->name('staff.new');
        Route::post('staff/store', 'ManageStaffController@storeStaff')->name('staff.store');
        Route::get('staff/detail/{id}', 'ManageStaffController@detail')->name('staff.detail');
        Route::post('staff/update/{id}', 'ManageStaffController@update')->name('staff.update');

        Route::get('staff/active', 'ManageStaffController@activeStaff')->name('staff.active');
        Route::get('staff/banned', 'ManageStaffController@bannedStaff')->name('staff.banned');
        Route::get('staff/email-verified', 'ManageStaffController@emailVerifiedStaff')->name('staff.emailVerified');
        Route::get('staff/email-unverified', 'ManageStaffController@emailUnverifiedStaff')->name('staff.emailUnverified');
        Route::get('staff/sms-unverified', 'ManageStaffController@smsUnverifiedStaff')->name('staff.smsUnverified');
        Route::get('staff/sms-verified', 'ManageStaffController@smsVerifiedStaff')->name('staff.smsVerified');
        Route::get('staff/{scope}/search', 'ManageStaffController@search')->name('staff.search');

        //Staff Login History
        Route::get('staff/login/history/{id}', 'ManageStaffController@staffLoginHistory')->name('staff.login.history.single');
        Route::get('staff/login/history', 'ManageStaffController@loginHistory')->name('staff.login.history');
        Route::get('staff/login/ipHistory/{ip}', 'ManageStaffController@loginIpHistory')->name('staff.login.ipHistory');

        Route::get('staff/send-email', 'ManageStaffController@showEmailAllForm')->name('staff.email.all');
        Route::post('staff/send-email', 'ManageStaffController@sendEmailAll')->name('staff.email.send');
        Route::get('staff/send-email/{id}', 'ManageStaffController@showEmailSingleForm')->name('staff.email.single');
        Route::post('staff/send-email/{id}', 'ManageStaffController@sendEmailSingle')->name('staff.email.single');

        //Create Appointment
        Route::get('create/appointment', 'AppointmentController@createAppointment')->name('appointments.create');
        Route::get('details/appointment', 'AppointmentController@appointmentDetails')->name('appointments.book.details');
        Route::get('booked_date/appointment', 'AppointmentController@bookedDate')->name('appointment.booked.date');
        Route::post('store/appointment/{id}', 'AppointmentController@appointmentStore')->name('appointments.store');

        //Appointment
        Route::get('all/appointment', 'AppointmentController@allAppointment')->name('appointments.all');
        Route::post('appointment/view/{id}', 'AppointmentController@appointmentView')->name('appointments.view');
        Route::get('appointment/done', 'AppointmentController@appointmentDone')->name('appointments.done');
        Route::post('appointment/remove/{id}', 'AppointmentController@appointmentRemove')->name('appointments.remove');
        Route::get('appointment/trashed', 'AppointmentController@appointmentTrashed')->name('appointments.trashed');


        // Subscriber
        Route::get('subscriber', 'SubscriberController@index')->name('subscriber.index');
        Route::get('subscriber/send-email', 'SubscriberController@sendEmailForm')->name('subscriber.sendEmail');
        Route::post('subscriber/remove', 'SubscriberController@remove')->name('subscriber.remove');
        Route::post('subscriber/send-email', 'SubscriberController@sendEmail')->name('subscriber.sendEmail');

        // DEPOSIT SYSTEM
        Route::name('deposit.')->prefix('deposit')->group(function () {
            Route::get('/', 'DepositController@deposit')->name('list');
            Route::get('pending', 'DepositController@pending')->name('pending');
            Route::get('rejected', 'DepositController@rejected')->name('rejected');
            Route::get('approved', 'DepositController@approved')->name('approved');
            Route::get('successful', 'DepositController@successful')->name('successful');
            Route::get('details/{id}', 'DepositController@details')->name('details');

            Route::post('reject', 'DepositController@reject')->name('reject');
            Route::post('approve', 'DepositController@approve')->name('approve');
            Route::get('/{scope}/search', 'DepositController@search')->name('search');

            // Deposit Gateway
            Route::get('gateway', 'GatewayController@index')->name('gateway.index');
            Route::get('gateway/edit/{alias}', 'GatewayController@edit')->name('gateway.edit');
            Route::post('gateway/update/{code}', 'GatewayController@update')->name('gateway.update');
            Route::post('gateway/remove/{code}', 'GatewayController@remove')->name('gateway.remove');
            Route::post('gateway/activate', 'GatewayController@activate')->name('gateway.activate');
            Route::post('gateway/deactivate', 'GatewayController@deactivate')->name('gateway.deactivate');

            // Manual Methods
            Route::get('gateway/manual', 'ManualGatewayController@index')->name('manual.index');
            Route::get('gateway/manual/new', 'ManualGatewayController@create')->name('manual.create');
            Route::post('gateway/manual/new', 'ManualGatewayController@store')->name('manual.store');
            Route::get('gateway/manual/edit/{alias}', 'ManualGatewayController@edit')->name('manual.edit');
            Route::post('gateway/manual/update/{id}', 'ManualGatewayController@update')->name('manual.update');
            Route::post('gateway/manual/activate', 'ManualGatewayController@activate')->name('manual.activate');
            Route::post('gateway/manual/deactivate', 'ManualGatewayController@deactivate')->name('manual.deactivate');
        });


        // Admin Support
        Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
        Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
        Route::put('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete');


        // Language Manager
        Route::get('/language', 'LanguageController@langManage')->name('language-manage');
        Route::post('/language', 'LanguageController@langStore')->name('language-manage-store');
        Route::delete('/language/delete/{id}', 'LanguageController@langDel')->name('language-manage-del');
        Route::post('/language/update/{id}', 'LanguageController@langUpdatepp')->name('language-manage-update');
        Route::get('/language/edit/{id}', 'LanguageController@langEdit')->name('language-key');
        Route::put('/language/keyword-update/{id}', 'LanguageController@langUpdate')->name('language.key-update');
        Route::post('/language/import', 'LanguageController@langImport')->name('language.import_lang');

        Route::post('store-lang-key/{id}', 'LanguageController@storeLanguageJson')->name('store-lang-key');
        Route::post('delete-lang-key/{id}', 'LanguageController@deleteLanguageJson')->name('delete-lang-key');
        Route::post('update-lang-key/{id}', 'LanguageController@updateLanguageJson')->name('update-lang-key');

        // General Setting
        Route::get('setting', 'GeneralSettingController@index')->name('setting.index');
        Route::post('setting', 'GeneralSettingController@update')->name('setting.update');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo-icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo-icon');

        // Plugin
        Route::get('plugin', 'PluginController@index')->name('plugin.index');
        Route::post('plugin/update/{id}', 'PluginController@update')->name('plugin.update');
        Route::post('plugin/activate', 'PluginController@activate')->name('plugin.activate');
        Route::post('plugin/deactivate', 'PluginController@deactivate')->name('plugin.deactivate');


        // Email Setting
        Route::get('email-template/global', 'EmailTemplateController@emailTemplate')->name('email-template.global');
        Route::post('email-template/global', 'EmailTemplateController@emailTemplateUpdate')->name('email-template.global');
        Route::get('email-template/setting', 'EmailTemplateController@emailSetting')->name('email-template.setting');
        Route::post('email-template/setting', 'EmailTemplateController@emailSettingUpdate')->name('email-template.setting');
        Route::get('email-template/index', 'EmailTemplateController@index')->name('email-template.index');
        Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('email-template.edit');
        Route::post('email-template/{id}/update', 'EmailTemplateController@update')->name('email-template.update');
        Route::post('email-template/send-test-mail', 'EmailTemplateController@sendTestMail')->name('email-template.sendTestMail');


        // SMS Setting
        Route::get('sms-template/global', 'SmsTemplateController@smsSetting')->name('sms-template.global');
        Route::post('sms-template/global', 'SmsTemplateController@smsSettingUpdate')->name('sms-template.global');
        Route::get('sms-template/index', 'SmsTemplateController@index')->name('sms-template.index');
        Route::get('sms-template/edit/{id}', 'SmsTemplateController@edit')->name('sms-template.edit');
        Route::post('sms-template/update/{id}', 'SmsTemplateController@update')->name('sms-template.update');
        Route::post('email-template/send-test-sms', 'SmsTemplateController@sendTestSMS')->name('email-template.sendTestSMS');

        // SEO
        Route::get('seo', 'FrontendController@seoEdit')->name('seo');


        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {

            Route::get('templates', 'FrontendController@templates')->name('templates');
            Route::post('templates', 'FrontendController@templatesActive')->name('templates.active');

            Route::get('frontend-sections/{key}', 'FrontendController@frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'FrontendController@frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'FrontendController@frontendElement')->name('sections.element');
            Route::post('remove', 'FrontendController@remove')->name('remove');

            // Page Builder
            Route::get('manage-pages', 'PageBuilderController@managePages')->name('manage.pages');
            Route::post('manage-pages', 'PageBuilderController@managePagesSave')->name('manage.pages.save');
            Route::patch('manage-pages', 'PageBuilderController@managePagesUpdate')->name('manage.pages.update');
            Route::delete('manage-pages', 'PageBuilderController@managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'PageBuilderController@manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'PageBuilderController@manageSectionUpdate')->name('manage.section.update');
        });
    });
});


/*
|--------------------------------------------------------------------------
| Start Doctor Area
|--------------------------------------------------------------------------
*/

Route::namespace('Doctor')->prefix('doctor')->name('doctor.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');

        // Doctor Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

});


Route::namespace('Doctor')->name('doctor.')->prefix('doctor')->group(function () {
    Route::middleware('doctor')->group(function () {
        Route::get('authorization', 'DoctorAuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'DoctorAuthorizationController@sendVerifyCode')->name('send_verify_code');
        Route::post('verify-email', 'DoctorAuthorizationController@emailVerification')->name('verify_email');
        Route::post('verify-sms', 'DoctorAuthorizationController@smsVerification')->name('verify_sms');
        Route::post('verify-g2fa', 'DoctorAuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkDoctorStatus'])->group(function () {
            Route::get('dashboard', 'DoctorController@dashboard')->name('dashboard');
            Route::get('profile', 'DoctorController@profile')->name('profile');
            Route::post('profile/update', 'DoctorController@profileUpdate')->name('profile.update');
            Route::get('password', 'DoctorController@password')->name('password');
            Route::post('password/update', 'DoctorController@passwordUpdate')->name('password.update');

            //2FA
            Route::get('twofactor', 'DoctorController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'DoctorController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'DoctorController@disable2fa')->name('twofactor.disable');

            //About
            Route::get('about', 'DoctorController@about')->name('about');
            Route::post('about/update', 'DoctorController@aboutUpdate')->name('about.update');

            //Education
            Route::get('education/all', 'DoctorController@educationAll')->name('education');
            Route::post('education/store', 'DoctorController@educationStore')->name('education.store');
            Route::post('education/update/{id}', 'DoctorController@educationUpdate')->name('education.update');
            Route::delete('education/remove/{id}', 'DoctorController@educationRemove')->name('education.remove');

            //Experience
            Route::get('experience/all', 'DoctorController@experienceAll')->name('experience');
            Route::post('experience/store', 'DoctorController@experienceStore')->name('experience.store');
            Route::post('experience/update/{id}', 'DoctorController@experienceUpdate')->name('experience.update');
            Route::delete('experience/remove/{id}', 'DoctorController@experienceRemove')->name('experience.remove');

            //Appointment
            Route::get('appointment', 'DoctorController@allAppointment')->name('appointment');
            Route::post('appointment/view/{id}', 'DoctorController@appointmentView')->name('appointment.view');
            Route::get('appointment/done', 'DoctorController@appointmentDone')->name('appointment.done');
            Route::post('appointment/remove/{id}', 'DoctorController@appointmentRemove')->name('appointment.remove');
            Route::get('appointment/trashed', 'DoctorController@appointmentTrashed')->name('appointment.trashed');

            //Create Appointment
            Route::get('create/appointment', 'DoctorController@appointmentDetails')->name('appointments.create');
            Route::get('booked_date/appointment', 'DoctorController@bookedDate')->name('appointments.booked.date');
            Route::post('store/appointment', 'DoctorController@appointmentStore')->name('appointments.store');

            //Schedule Manage
            Route::get('schedule', 'DoctorController@schedule')->name('schedule');
            Route::post('schedule/manage}', 'DoctorController@scheduleManage')->name('schedule.slot');

            //Social Icon
            Route::get('social-icon/all', 'DoctorController@socialIcon')->name('social.icon');
            Route::post('social-icon/store', 'DoctorController@socialIconStore')->name('social.icon.store');
            Route::post('social-icon/update/{id}', 'DoctorController@socialIconUpdate')->name('social.icon.update');
            Route::delete('social-icon/remove/{id}', 'DoctorController@socialIconRemove')->name('social.icon.remove');

            //Speciality Icon
            Route::get('speciality/all', 'DoctorController@speciality')->name('speciality');
            Route::post('speciality/update', 'DoctorController@specialityUpdate')->name('speciality.update');
        });

    });

});

/*
|--------------------------------------------------------------------------
| Start Assistant Area
|--------------------------------------------------------------------------
*/


Route::namespace('Assistant')->prefix('assistant')->name('assistant.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');

        // Assistant Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });
});

Route::namespace('User')->prefix('user')->name('user.')->group(function () {

    Route::namespace('Auth')->group(function () {
        Route::post('/', 'LoginController@login')->name('login');
        Route::post('register', 'LoginController@register')->name('register');
        Route::get('logout', 'LoginController@logout')->name('logout');

        // Assistant Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');


        Route::post('verify-g2fa', 'UserAuthorizationController@g2faVerification')->name('go2fa.verify');
    });
   

    Route::get('authorization', 'UserAuthorizationController@authorizeForm')->name('authorization');
    Route::get('resend-verify', 'UserAuthorizationController@sendVerifyCode')->name('send_verify_code');
    Route::post('verify-email', 'UserAuthorizationController@emailVerification')->name('verify_email');
    Route::post('verify-sms', 'UserAuthorizationController@smsVerification')->name('verify_sms');
    Route::middleware(['checkUserStatus'])->group(function () {
         Route::get('doctors', 'UserController@allDoctors')->name('doctors');
        Route::get('dashboard', 'UserController@dashboard')->name('dashboard');
        Route::get('profile', 'UserController@profile')->name('profile');
        Route::post('profile/update', 'UserController@profileUpdate')->name('profile.update');
        Route::get('password', 'UserController@password')->name('password');
        Route::post('password/update', 'UserController@passwordUpdate')->name('password.update');
        Route::get('twofactor', 'UserController@show2faForm')->name('twofactor');
        Route::post('twofactor/enable', 'UserController@create2fa')->name('twofactor.enable');
        Route::post('twofactor/disable', 'UserController@disable2fa')->name('twofactor.disable');
        Route::get('appointment', 'UserController@allAppointment')->name('appointments.all');

        Route::get('create/appointment', 'AssistantController@createAppointment')->name('appointments.create');
        Route::get('details/appointment', 'AssistantController@appointmentDetails')->name('appointments.book.details');
        Route::get('booked_date/appointment', 'AssistantController@bookedDate')->name('appointments.booked.date');
        Route::post('store/appointment/{id}', 'AssistantController@appointmentStore')->name('appointments.store');

    });

});

Route::namespace('Assistant')->name('assistant.')->prefix('assistant')->group(function () {
    Route::middleware('assistant')->group(function () {
        Route::get('authorization', 'AssistantAuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AssistantAuthorizationController@sendVerifyCode')->name('send_verify_code');
        Route::post('verify-email', 'AssistantAuthorizationController@emailVerification')->name('verify_email');
        Route::post('verify-sms', 'AssistantAuthorizationController@smsVerification')->name('verify_sms');
        Route::post('verify-g2fa', 'AssistantAuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkAssistantStatus'])->group(function () {
            Route::get('dashboard', 'AssistantController@dashboard')->name('dashboard');
            Route::get('profile', 'AssistantController@profile')->name('profile');
            Route::post('profile/update', 'AssistantController@profileUpdate')->name('profile.update');
            Route::get('password', 'AssistantController@password')->name('password');
            Route::post('password/update', 'AssistantController@passwordUpdate')->name('password.update');

            //2FA
            Route::get('twofactor', 'AssistantController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'AssistantController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'AssistantController@disable2fa')->name('twofactor.disable');

            //Appointment
            Route::get('appointment/doctor/{id}', 'AssistantController@doctorAppointment')->name('doctor.appointment.view');
            Route::post('appointment/view/{id}', 'AssistantController@appointmentView')->name('appointment.view');
            Route::get('appointment/doctor/done/{id}', 'AssistantController@appointmentDone')->name('doctor.appointment.done');
            Route::post('appointment/remove/{id}', 'AssistantController@appointmentRemove')->name('appointment.remove');
            Route::get('appointment/doctor/trashed/{id}', 'AssistantController@appointmentTrashed')->name('doctor.appointment.trashed');

            //Create Appointment
            Route::get('create/appointment', 'AssistantController@createAppointment')->name('appointments.create');
            Route::get('details/appointment', 'AssistantController@appointmentDetails')->name('appointments.book.details');
            Route::get('booked_date/appointment', 'AssistantController@bookedDate')->name('appointments.booked.date');
            Route::post('store/appointment/{id}', 'AssistantController@appointmentStore')->name('appointments.store');

            //Doctors
            Route::get('doctors', 'AssistantController@allDoctors')->name('doctors');

        });
    });
});

/*
|--------------------------------------------------------------------------
| Start Staff Area
|--------------------------------------------------------------------------
*/


Route::namespace('Staff')->prefix('staff')->name('staff.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');

        // Staff Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });
});

Route::namespace('Staff')->name('staff.')->prefix('staff')->group(function () {
    Route::middleware('staff')->group(function () {
        Route::get('authorization', 'StaffAuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'StaffAuthorizationController@sendVerifyCode')->name('send_verify_code');
        Route::post('verify-email', 'StaffAuthorizationController@emailVerification')->name('verify_email');
        Route::post('verify-sms', 'StaffAuthorizationController@smsVerification')->name('verify_sms');
        Route::post('verify-g2fa', 'StaffAuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkStaffStatus'])->group(function () {
            Route::get('dashboard', 'StaffController@dashboard')->name('dashboard');
            Route::get('profile', 'StaffController@profile')->name('profile');
            Route::post('profile/update', 'StaffController@profileUpdate')->name('profile.update');
            Route::get('password', 'StaffController@password')->name('password');
            Route::post('password/update', 'StaffController@passwordUpdate')->name('password.update');

            //2FA
            Route::get('twofactor', 'StaffController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'StaffController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'StaffController@disable2fa')->name('twofactor.disable');

            Route::get('appointment', 'StaffController@allAppointment')->name('appointments.all');
            Route::post('appointment/view/{id}', 'StaffController@appointmentView')->name('appointments.view');
            Route::get('appointment/done', 'StaffController@appointmentDone')->name('done.appointment');
            Route::post('appointment/remove/{id}', 'StaffController@appointmentRemove')->name('appointments.remove');
            Route::get('appointment/trashed', 'StaffController@appointmentTrashed')->name('appointments.trashed');

            //Create Appointment
            Route::get('create/appointment', 'StaffController@createAppointment')->name('appointment.create');
            Route::get('details/appointment', 'StaffController@appointmentDetails')->name('appointment.book.details');
            Route::get('booked_date/appointment', 'StaffController@bookedDate')->name('appointment.booked.date');
            Route::post('store/appointment/{id}', 'StaffController@appointmentStore')->name('appointment.store');

            //Doctors
            Route::get('doctors', 'StaffController@allDoctors')->name('doctors');

        });
    });
});
Route::post('/payment-successful-page', 'Gateway\PaymentController@paymentFinallized')->name('paymentFinalized');

Route::middleware(['checkUserStatus'])->group(function () {

    Route::any('/deposit', 'Gateway\PaymentController@deposit')->name('deposit');
    Route::post('deposit/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
    Route::get('deposit/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');
    Route::get('deposit/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
    Route::get('deposit/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
    Route::post('deposit/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
//Booking
    Route::get('booking/{id}', 'SiteController@booking')->name('booking');
    Route::get('booked_date', 'SiteController@bookedDate')->name('bookeddate');

//Appointment Store
    Route::post('/appointment/store/{id}', 'SiteController@appointmentStore')->name('appointment.store');
});
// Deposit

//All Doctors
Route::get('mentors-all', 'SiteController@doctorsAll')->name('doctors.all');

//Featured Doctors All
Route::get('mentors-all-featured', 'SiteController@featuredDoctorsAll')->name('featured.doctors.all');

//Sector Wise Doctor
Route::get('mentors-all/sector/{id}', 'SiteController@sectorDoctors')->name('sector.doctors.all');

//Location Wise Doctor
Route::get('mentors-all/location/{id}', 'SiteController@locationDoctors')->name('location.doctors.all');

//Search Doctor
Route::get('mentors-all/search', 'SiteController@doctorSearch')->name('search.doctors');


//Subscriber Store
Route::post('subscriber', 'SiteController@subscriberStore')->name('subscriber.store');

//Blog
Route::get('/blog', 'SiteController@blog')->name('blog');
Route::get('/blog/details/{id}/{title}', 'SiteController@blogDetails')->name('blog.details');

//Contact
Route::get('/contact', 'SiteController@contact')->name('contact');
Route::post('/contact', 'SiteController@contactSubmit')->name('contact.send');

//Lang
Route::get('/change/{lang?}', 'SiteController@changeLanguage')->name('lang');

//Home
Route::get('/login', 'SiteController@login')->name('login');
Route::get('/register', 'SiteController@register')->name('register');
Route::get('/', 'SiteController@index')->name('home');
Route::get('/{slug}', 'SiteController@pages')->name('pages');
Route::get('/auth/linkedin','SiteController@redirectLinkedin');
Route::get('/auth/google','SiteController@redirectGoogle');
Route::get('auth/linkedin-callback','SiteController@linkedinCallBack');
Route::get('auth/google-callback','SiteController@googleCallBack');
Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholderImage');
