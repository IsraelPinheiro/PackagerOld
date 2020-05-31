<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use Storage;
use Carbon\Carbon;
use App\Package;
use App\File;
use App\User;
use App\ChangeLog;
class OutboundPackageController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $packages = Auth::user()->sent;
        return view('pages.packages.outbounds.index',compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $users= User::all();
        return view('pages.packages.outbounds.new',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate([
            'Title' => 'bail|required|min:3|string',
            'Recipient' => 'bail|required'
        ]);
        if($request->DirectLinkStatus==2){
            $request->validate([
                'Password' => 'bail|required|min:6'
            ]);
        }
        if($request->ExpirationDateStatus==1){
            $request->validate([
                'ExpirationDate' => 'bail|required|date|after:yesterday'
            ]);
        }
        $package = new Package;
        $package->title = $request->Title;
        $package->description = $request->Description;
        if($request->DirectLinkStatus!=0){
            $package->key = md5(Carbon::now());
            if($request->DirectLinkStatus==2){
                $package->password = Hash::make($request->Password);
            }
        }
        $package->sender_id = Auth::user()->id;
        $package->recipient_id = $request->Recipient;
        $package->expires_at = Carbon::parse($request->ExpirationDate)->endOfDay();
        $package->save();
        
        if($request->hasfile('files')){
            foreach($request->file('files') as $upload){
                $file = new File;
                $file->originalName = $upload->getClientOriginalName();
                $file->package_id = $package->id;
                $file->size = $upload->getSize();
                $file->extension = $upload->extension();
                $file->file = Storage::putFile('files', $upload);
                $file->save();
            }
        }
        
        if(env('TRACK_CHANGES', true)){
            $log = new ChangeLog;
            $log->user_id = Auth::user()->id;
            $log->loggable_type = 'package';
            $log->loggable_id = $package->id;
            $log->target_action = 'create';
            $log->old_data = null;
            $log->save();
        }
        return response()->json(['message' => 'Pacote Enviado'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $package = Package::find($id);
        if($package){
            if($package->sender_id == Auth::user()->id){
                return view('pages.packages.outbounds.show', compact('package'));
            }
            else{
                abort(401);
            }
        }
        else{
            return response()->json(['message' => 'Pacote não encontrado'],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $package = Package::find($id);
        if($package){
            if($package->sender_id == Auth::user()->id){
                return view('pages.packages.outbounds.edit', compact('package'));
            }
            else{
                abort(401);
            }
        }
        else{
            return response()->json(['message' => 'Pacote não encontrado'],404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $package = Package::find($id);
        if($package){
            if($package->sender_id == Auth::user()->id){
                if(env('TRACK_CHANGES', true)){
                    $log = new ChangeLog;
                    $log->user_id = Auth::user()->id;
                    $log->loggable_type = 'Package';
                    $log->loggable_id = $id;
                    $log->target_action = 'delete';
                    $log->old_data = $package->toJson();
                    $log->save();
                }
                $package->delete();
                return response()->json(['level' => 'success','message' => 'Pacote Excluído'],200);

            }
            else{
                abort(401);
            }
        }
        else{
            return response()->json(['message' => 'Pacote não encontrado'],404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadPackage($id){
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadFile($id){
        
    }
}
