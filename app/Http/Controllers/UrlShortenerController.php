<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Url;

class UrlShortenerController extends Controller
{
    //store old and new url in database
    public function store(Request $request){
        try {
            if (auth()->user()->id) {
                $longUrl = $request->get('url');
                $newGeneratedUrl = $request->get('shortlink');
                
                if($longUrl != '' || $newGeneratedUrl != ''){
                    $urlFound = Url::where('old_url',$longUrl)->get(["id","new_url"])->toArray();
                    if (!empty($urlFound)) {
                        return $urlFound[0]['new_url'];
                    }
                    else{
                        $urlTable = new Url;
                        $urlTable->old_url = $longUrl;
                        $urlTable->new_url = $newGeneratedUrl;
                        $urlTable->user_id = auth()->user()->id ?? null;
                        $urlTable->user_ip = $_SERVER['REMOTE_ADDR'];
                        
                        if ($urlTable->save()) {
                            return $urlTable->$new_url;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            dd($e);
        }
    }
    // handle new urls and redirect to old url
    public function handle(Request $request, $url){
        $uri = $_SERVER['REQUEST_URI'];
        if ($uri == '') {
            return abort(404);
        }
        $url = Url::where('new_url','like','%'.$uri.'%')->get(["id","old_url","clicks"]);

        try {
            if ($url =='' || count($url) == 0) {
                return abort(404);
            }else{
                $urlFound = Url::find($url[0]['id']);
                $urlFound->clicks = $url[0]['clicks']+1;
                $urlFound->save();
                return redirect($url[0]['old_url']);
            }
        } catch (Exception $e) {
            dd($e);
        }
    }
    //
    public function dashboard(Request $request){
        try {
            if (auth()->user()->id) {
                $links = Url::where('user_id',auth()->user()->id)->get();
                return view('urldashboard',compact('links'));
            }
        } catch (Exception $e) {
            dd($e);
        }
    }
}
