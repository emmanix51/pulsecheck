<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AuthController;

use App\Mail\SurveyDistributed;


Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/test-email', function () {
    // $details = [
    //     'title' => 'Test Email',
    //     'body' => 'This is a test email.'
    // ];
    // $test = 'wew';
    // $name = "wew";
    // $from = "wow";
    // Mail::to('emantubo@gmail.com')->send(new SurveyDistributed(array(
    //     "name" => $name,
    //     "from" => $from,
    // )));

    // dd("email sent");

    try {
        $name = "wew";
        $from = "wow";
        Mail::to('emantubo@gmail.com')->send(new SurveyDistributed(array(
            "name" => $name,
            "from" => $from,
        )));

        dd([
            env('MAIL_MAILER'),
            env('MAIL_HOST'),
            env('MAIL_PORT'),
            env('MAIL_USERNAME'),
            env('MAIL_PASSWORD'),
            env('MAIL_ENCRYPTION'),
            env('MAIL_FROM_ADDRESS'),
            env('MAIL_FROM_NAME'),
        ]);
        return "Email sent successfully";
    } catch (\Exception $e) {
        return "Failed to send email. Error: " . $e->getMessage();
    }
    // return 'Email Sent';
});
