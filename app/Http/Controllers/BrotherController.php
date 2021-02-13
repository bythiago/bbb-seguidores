<?php

namespace App\Http\Controllers;

use App\Models\Brother;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Phpfastcache\Helper\Psr16Adapter;

class BrotherController extends Controller
{
    private $brother;

    public function __construct(Brother $brother)
    {   
        $this->brother = $brother;
    }

    private function brothers(){
        return array(
            'lucaskokapenteado',
            'projota',
            'lumena.aleluia',
            'gilnogueiraofc',
            'camilladelucas',
            'sarah_andrade',
            'negodioficial',
            'karolconka',
            'fiuk',
            'pocah',
            'juliette.freire',
            'viihtube',
            'carladiaz'
        );
    }

    private function create(array $account){
        DB::beginTransaction();
        try {    
            $this->brother->create($account);
            DB::commit();
        } catch(\Exception $e){
            DB::rollBack();
        }

        return $account;
    }

    private function getInstagramAccount (){
        $instagram = \InstagramScraper\Instagram::withCredentials(new \GuzzleHttp\Client(), env('INSTAGRAM_USERNAME'), env('INSTAGRAM_PASSWORD'), new Psr16Adapter('Files'));
        $instagram->login(false);
        $instagram->saveSession();

        return $instagram;
    }

    private function followed($followed)
    {

        $followed_replaced = (int)preg_replace( '/[^0-9]/', '', $followed);
        $followed_formated = $followed_replaced / 1000000;
        
        if(floor($followed_formated) >= 1){
            return sprintf('%s milhões seguidores', round($followed_formated, 1));
        }

        if(number_format($followed_replaced) >= 10000){
            return sprintf('%s mil seguidores', round(number_format($followed_replaced)));
        }

        return sprintf('%s seguidores', number_format($followed_replaced));
    }

    public function json()
    {
        $accounts = array_map(function($brother){
            return [
                $this->brother->whereUsername($brother)->whereDate('created_at', Carbon::now())->latest()->first(['followed', 'followed_formated', 'username', 'profile_url']),
                $this->brother->whereUsername($brother)->first(['followed'])
            ];
        }, $this->brothers());

        rsort($accounts);
        return response()->json($accounts, 200);
    }
    
    public function graph()
    {
        $accounts = array_map(function($brother){
            return $this->brother->whereUsername($brother)->whereDate('created_at', Carbon::now())->get(['followed', 'username']);
        }, $this->brothers());

        $response = [];
        foreach($accounts as $items){
            foreach($items as $account){
                $response[$account->username][] = $account->toArray();
            }
        }

        return response()->json($response, 200);
    }

    public function import(){

        try {

            $instagram = $this->getInstagramAccount();
            
            $accounts = [];
            foreach($this->brothers() as $brother){
                $accounts[] = $instagram->getAccount($brother);
            }

            $response = [];
            foreach($accounts as $account){
                $response[] = $this->create([
                    'followed' => $account->getFollowedByCount(),
                    'followed_formated' => $this->followed($account->getFollowedByCount()),
                    'username' => sprintf("%s", $account->getUsername()),
                    'profile_url' => base64_encode(file_get_contents($account->getProfilePicUrlHd())),
                ]);
            }

            return response()->json($response, 200);
            
        } catch (\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function index(){
        return view('brothers');
    }
}