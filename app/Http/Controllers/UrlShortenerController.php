<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Url;
use Kreait\Firebase\Contract\Database;

class UrlShortenerController extends Controller{

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = 'urls';
    }
    //store old and new url in database
    public function store(Request $request){
        try {
            $date = date('Y-m-d');
            $postData = [
                'old_url' => $request->url,
                'new_url' => $request->shortlink,
                'id_user' => auth()->user()->id,
                'created_at'=> $date,
                'updated_at'=> $date,
                'clicks' => 0
            ];
            $res = $this->database->getReference($this->tablename)->push($postData);

            $query =$this->database->getReference($this->tablename)->orderByChild('id_user')->equalTo(auth()->user()->id);
            $result = $query->getValue();
            $urls = [];
            foreach ($result as $key => $value) {
                $url = new \stdClass();
                $url->id = $key;
                $url->old_url = $value['old_url'];
                $url->new_url = $value['new_url'];
                $url->id_user = $value['id_user'];
                $url->created_at = $value['created_at'];
                $url->updated_at = $value['updated_at'];
                $url->clicks = $value['clicks'];
                
                $urls[] = $url;
            }
            
            $links = $urls;

            return $links;
            
        } catch (Exception $e) {
            dd($e);
        }
    }
    // handle new urls and redirect to old url
    public function handle(Request $request,$url){
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
            if(auth()->user() && auth()->user()->id) {
                $query = $this->database->getReference($this->tablename)->orderByChild('id_user')->equalTo(auth()->user()->id);
                $data = $query->getValue();

                $urls = [];
                if (!$data) {
                    return view('urldashboard');
                }
                foreach ($data as $key => $value) {
                    $url = new \stdClass();
                    $url->id = $key;
                    $url->old_url = $value['old_url'];
                    $url->new_url = $value['new_url'];
                    $url->id_user = $value['id_user'];
                    $url->created_at = $value['created_at'];
                    $url->updated_at = $value['updated_at'];
                    $url->clicks = $value['clicks'];
                    
                    $urls[] = $url;
                }
                
                $links = json_encode($urls);
                return view('urldashboard',compact('links'));
            }else{
                return view('auth.login');
            }
        } catch (Exception $e) {

            dd($e);
        }
    }
    // 
    public function delete(Request $request,$id){
        try {
            $this->database->getReference('urls/'.$id)->remove();

            $query =$this->database->getReference($this->tablename)->orderByChild('id_user')->equalTo(auth()->user()->id);
            $result = $query->getValue();
            $urls = [];
            foreach ($result as $key => $value) {
                $url = new \stdClass();
                $url->id = $key;
                $url->old_url = $value['old_url'];
                $url->new_url = $value['new_url'];
                $url->id_user = $value['id_user'];
                $url->created_at = $value['created_at'];
                $url->updated_at = $value['updated_at'];
                $url->clicks = $value['clicks'];
                
                $urls[] = $url;
            }
            
            $links = $urls;

            return $links;
        } catch (\Exception $e) {
            dd($e);
        }
    }

}
