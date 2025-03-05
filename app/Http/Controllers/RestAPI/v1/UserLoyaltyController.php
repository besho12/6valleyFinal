<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Utils\Helpers;
use App\Models\AdsPoints;
use Illuminate\Http\Request;
use App\Utils\CustomerManager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\LoyaltyPointTransaction;
use Illuminate\Support\Facades\Validator;

class UserLoyaltyController extends Controller
{
    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)]);
        }

        $loyalty_point_status = getWebConfig(name: 'loyalty_point_status');
        if($loyalty_point_status==1)
        {
            $user = $request->user();
            $total_loyalty_point = $user->loyalty_point;

            $loyalty_point_list = LoyaltyPointTransaction::where('user_id',$user->id)
                                                    ->latest()
                                                    ->paginate($request['limit'], ['*'], 'page', $request['offset']);

            return response()->json([
                'limit'=>(integer)$request->limit,
                'offset'=>(integer)$request->offset,
                'total_loyalty_point'=>$total_loyalty_point,
                'total_loyalty_point_count'=>$loyalty_point_list->total(),
                'loyalty_point_list'=>$loyalty_point_list->items()
            ],200);
        }else{
            return response()->json(['message' => translate('access_denied!')], 422);
        }
    }

    public function loyalty_exchange_currency(Request $request)
    {
        $wallet_status = getWebConfig(name: 'wallet_status');
        $loyalty_point_status = getWebConfig(name: 'loyalty_point_status');

        if($wallet_status != 1 || $loyalty_point_status !=1)
        {
            return response()->json([
                'message' => translate('transfer_loyalty_point_to_currency_is_not_possible_at_this_moment!')
            ],422);
        }

        $validator = Validator::make($request->all(), [
            'point' => 'required|integer|min:1'
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)]);
        }

        $user = $request->user();
        if($request->point < (int)getWebConfig(name: 'loyalty_point_minimum_point')
            || $request->point > $user->loyalty_point)
        {
            return response()->json([
                'message' => translate('insufficient_point!')
            ],422);
        }

        $wallet_transaction = CustomerManager::create_wallet_transaction($user->id,$request->point,'loyalty_point','point_to_wallet');
        CustomerManager::create_loyalty_point_transaction($user->id, $wallet_transaction->transaction_id, $request->point, 'point_to_wallet');

        try
        {

            Mail::to($user['email'])->send(new \App\Mail\AddFundToWallet($wallet_transaction));


        }catch(\Exception $ex){

        }

        return response()->json([
            'message' => translate('point_to_wallet_transfer_successfully!')
        ],200);
    }

    public function ads_exchange_currency(Request $request)
    {
        $wallet_status = getWebConfig(name: 'wallet_status');

        if($wallet_status != 1)
        {
            return response()->json([
                'message' => translate('transfer_loyalty_point_to_currency_is_not_possible_at_this_moment!')
            ],422);
        }

        $validator = Validator::make($request->all(), [
            'points' => 'required|integer|min:1'
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::validationErrorProcessor($validator)]);
        }

        $user_ads_points = $this->get_available_ads_points($request['user_id']);
        
        if($request->points < (int)getWebConfig(name: 'ads_point_minimum_point')
            || $request->points > $user_ads_points)
        {
            return response()->json([
                'message' => translate('insufficient_point!')
            ],422);
        }

        $wallet_transaction = CustomerManager::create_wallet_transaction($request['user_id'],$request->points,'ads_point','point_to_wallet');
        CustomerManager::create_ads_point_transaction($request['user_id'], $wallet_transaction->transaction_id, $request->points, 'point_to_wallet');

        $this->subtract_from_available_ads_points($request['user_id'], $request->point);

        return response()->json([
            'message' => translate('point_to_wallet_transfer_successfully!')
        ],200);
    }

    function get_available_ads_points($user_id){
        return AdsPoints::where('user_id', $user_id)->sum('points');
    }

    function subtract_from_available_ads_points($user_id, $points){
        $exist_user_points = AdsPoints::where('user_id', $user_id)->first();
        $newPoints = floatval($exist_user_points['points']) - floatval($points);
        AdsPoints::where('user_id', $user_id)->update(['points'=>$newPoints]);
    }

}
