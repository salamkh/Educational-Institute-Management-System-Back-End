<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Student;
use App\Models\StudentCourse;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class SendSMSController extends Controller
{

    private $SMS_SENDER = "Al-Tafawouk";
    private $RESPONSE_TYPE = 'json';
    private $SMS_USERNAME = 'Altafawok1';
    private $SMS_PASSWORD = 'tttt@2023';

   /*https://bms.syriatel.sy/API/
      SendSMS.aspx?/127.0.0.1:8000/api/sendMessage&
      user_name=Altafawok1
      &password=tttt@2023
      &sender=Al-Tafawouk
      &msg=second testing message
      &to=963981431362
     */



      /*https://bms.syriatel.sy/API/
      SendSMS.aspx?/127.0.0.1:8000/api/sendMessage&
      user_name=Altafawok1
      &password=tttt@2023
      &sender=Al-Tafawouk
      &msg=second testing message
      &to=963981431362
     */

    public function sendMessage(Request $request)
    {
        $message = new Message();
        $message->msg =$request->input('msg');
        $message->sendDate = date('Y-m-d H:i:s');
        $message->to =$request->input('to');

        $message->save();
        return response(
            [
                'studentCourse' => $message,
                'message' => 'تم إرسال الرسالة بنجاح',
            ],
            200
        );
    }


    public function showAllMessages()
    {
        $message = Message::get();
        for ($i = 0; $i < sizeof($message); $i++) {
            $student = Student::where('phone', $message[$i]->to)->get()->first();
            if ($student != null) {
                $message[$i]->name = $student->name;
            }
        }
        return response(
            [
                'studentMessages' => $message,
            ],
            200
        );
    }
    public function showAllMessagesForStudent($to)
    {
        $message = Message::where('to', $to)->get();

        return response(
            [
                'studentMessages' => $message,
            ],
            200
        );
    }

}
