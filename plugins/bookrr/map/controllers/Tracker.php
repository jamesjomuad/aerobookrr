<?php namespace Bookrr\Map\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use File;
use Storage;
use \Carbon\Carbon;
use Backend\Models\User;

/**
 * Tracker Back-end Controller
 */
class Tracker extends Controller
{
    public $assetPath = '/plugins/aeroparks/map/assets/';

    public $storageName = 'tracker';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Aeroparks.Map', 'map', 'tracker');
    }
    
    public function index()
    {
        $this->pageTitle = 'Tracker';
        
        if($this->user->isCustomer())
        {
           $this->prepareAssets(); 
        }
    }

    public function preview($id)
    {
        $this->pageTitle = 'Track User';
        $this->prepareAssets();
    }
    
    public function onSetPosition()
    {
        $date = Carbon::now()->format('y-m-d');
        $folder = $this->storageName.'/'.$this->user->login.'/';
        $filename = $date.'.map';
        $path = $folder.$filename;
        $content = post('latitude').','.post('longitude');
        
        return response()->json(Storage::prepend($path, $content));
    }

    public function onGetPosition($id)
    {
        
        $date       = Carbon::now()->format('y-m-d');
        $folder     = User::find($id)->login.'/';
        $filename   = $date.'.map';
        $path       = storage_path('app/'.$this->storageName.'/'.$folder.$filename);
    
        if(File::exists($path))
        {
            $content = str_replace(['\n','\r'],"",file($path,FILE_IGNORE_NEW_LINES)[0]);
            $latlon  = explode(',',$content);

            return response()->json([
                'latitude'  => $latlon[0],
                'longitude' => $latlon[1],
            ]);
        }

        return response()->json(false);
    }

    public function prepareAssets()
    {
        $this->addCss($this->assetPath.'map.css',str_random(5));
        // $this->addJs('https://maps.googleapis.com/maps/api/js?key=AIzaSyAn8v19Cdt5H4vSTdxbhUB6yRp7AiaHbPQ');
        $this->addJs('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false');
        $this->addJs($this->assetPath.'map.js',str_random(5));

        if($this->user->isCustomer()){
            $this->addJs($this->assetPath.'tracker.js',str_random(5));
        } else {
            $this->addJs($this->assetPath.'locator.js',str_random(5));
        }
    }

    public function storagePath()
    {
        return storage_path('app/'.$this->storageName.'/');
    }

}
