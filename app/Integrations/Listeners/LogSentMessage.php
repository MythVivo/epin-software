<?php
namespace App\Integrations\Listeners;
use Illuminate\Support\Facades\DB;
class LogSentMessage
{
    public function handle($event){
        $user = DB::table('users')->where('email', array_keys($event->message->getTo())[0])->first();
        DB::table('sms_log')->insert(['user_id' => $user->id,'konu'=>$event->message->getSubject() ,'email' => $user->email, 'text'=>gzcompress($event->message->getBody(),9), 'created_at' => date('YmdHis')]);
    }
}
?>
