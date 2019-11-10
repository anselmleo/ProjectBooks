<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Redis;
use App\Mail\OrderPostedEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderPostedEmailJob extends Job
{
    private $user;

    /**
     * Create a new job instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Allow only 2 emails every 1 second
        // Redis::throttle('my-mailtrap')->allow(2)->every(1)->then(function () {

        //     Mail::to($this->user)->send(new OrderPostedEmail($this->user));
        //     Log::info('Emailed order ' . $this->order->id);

        // }, function () {
        //     // Could not obtain lock; this job will be re-queued
        //     return $this->release(2);
        // });
        Mail::to($this->user)->send(new OrderPostedEmail($this->user));
        Log::info('Emailed user on order posted ' . $this->user->id);
    }
}
