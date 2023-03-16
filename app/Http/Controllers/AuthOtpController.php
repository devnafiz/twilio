<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Auth;
class AuthOtpController extends Controller
{
    public function login(){
    	//dd('ok');
    	return view('auth.otp-login');
    }
   //generate otp

    public function generate(Request $request){
    	$request->validate([

           'mobile_no'=>'required|exists:users,mobile_no'
    	]);

    	//generate OTp

    	$verificationCode =$this->generateOtp($request->mobile_no);

    	$message= "Your Otp to login -:".$verificationCode->otp;

    	//now use mobile  sms gtway

    	return redirect()->route('otp.verification',['user_id' => $verificationCode->user_id])->with('success',$message);




    }

    public function generateOtp($mobile_no){

    	$user =User::where('mobile_no',$mobile_no)->first();
    	//user does not have any verification code

    	$verificationCode=VerificationCode::where('user_id',$user->id)->latest()->first();

    	$now =Carbon::now();
        
    	if($verificationCode && $now->isBefore($verificationCode->expire_at)){
         return $verificationCode;

    	}

    	return VerificationCode::create([
          'user_id'=>$user->id,
          'otp' =>rand(12345,999999),
          'expire_at'=>Carbon::now()->addMinutes(10)
    	]);

    }


    public function verification($user_id){
    	return view('auth.otp-verification')->with([
          'user_id'=>$user_id
    	]);
    }


    public function loginwithOtp(Request $request){

    	$request->validate([
          'user_id'=> 'required|exists:users,id',
          'otp'=>'required'

    	]);

    	//valoidation login here

           $verificationCode =VerificationCode::where('user_id',$request->user_id)->where('otp',$request->otp)->first();


           $now =Carbon::now();
           if(!$verificationCode){
           	 return redirect()->route('otp.login')->with('error','Your OTP not Coreect');

           }elseif($verificationCode && $now->isAfter($verificationCode->expire_at)){
         return redirect()->route('otp.login')->with('error','Your OTP has been Expired');

    	}
    	$user =User::whereId($request->user_id)->first();

    	if($user){

    		Auth::login($user);
    		return redirect('/home');
    	}



    }
}
