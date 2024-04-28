<?php

namespace App\Http\Controllers;

use App\Models\client;
use Illuminate\Http\Request;

class clientController extends Controller
{
    function search (Request $request){
    $input=$request->validate([
        'search' => 'required',
    ]);
    $search=$input['search'];
    $clients = client::where('full_name', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->paginate(10);
     return view('client.index',compact('clients'));

    } 

    function pdf_info (int $client_id){
        $client=client::findOrfail($client_id);
        return view('client.pdf.info',compact('client'));
    }
    
    function pdf_payment (int $client_id){
        $client=client::findOrfail($client_id);
        $products=$client->products;
        return view('client.pdf.payment',compact('client','products'));
    }
    function index(){
       $clients=client::paginate(8);
        return view('client.index',compact('clients'));
    }
    function new_view(){
        return view('client.create');
    }
    function create(Request $request){
        $data=$request->validate([
            'full_name' => 'required',
            'phone' => 'required',
        ]);
        client::create($data);
        return redirect()->route('clients.index')
        ->with('message', 'تمت إضافة زبون جديد بنجاح')
        ->with('msg-color','success');
    }
    function single_view(int $id)  {
        $client=client::findOrfail($id);        
        return view('client.view',compact('client'));
    }
    function edit(int $id) {
        $client=client::find($id);
        return view('client.edit',compact('client')); 
    }
    function update(Request $request,int $id)  {
        $data=$request->validate([
            'full_name' => 'required',
            'phone' => 'required',
        ]);
        $client=client::find($id);
        $client->update($data);
        return redirect()->route('clients.index')
        ->with('msg-color','success')
        ->with( 'message','تم تعديل معلومات الزبون بنجاح');
    }
    function destroy(int $id){
        $client=client::find($id);
        $client->delete();
        return redirect()->route('clients.index')
        ->with('msg-color','danger')
        ->with('message','تم حذف معلومات الزبون بنجاح');

    }
}