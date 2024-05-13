<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    function search (Request $request){
    $input=$request->validate([
        'search' => 'required',
    ]);
    $search=$input['search'];
    $clients = Client::where('full_name', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->paginate(10);
     return view('Clients.client.index',compact('clients'));

    } 

    /*     
    function pdf_info (int $client_id){
        $client=Client::findOrfail($client_id);
        return view('client.pdf.info',compact('client'));
    } 
    */
    
    function pdf_payment (int $client_id){
        $client=Client::findOrfail($client_id);
        $products=$client->products;
        $Todaydate=date('Y-m-d');
        return view('Clients.client.pdf.payment',compact('client','products','Todaydate'));
    }
    function index(){
       $clients=Client::paginate(8);
        return view('Clients.client.index',compact('clients'));
    }
    function new_view(){
        return view('Clients.client.create');
    }
    function create(Request $request){
        $data=$request->validate([
            'full_name' => 'required',
            'phone' => 'required',
        ]);
        $data['created_by']=Auth::user()->id;
        Client::create($data);
        return redirect()->route('clients.index')
        ->with('message', 'تمت إضافة زبون جديد بنجاح')
        ->with('msg-color','success');
    }
    function single_view(int $id)  {
        $client=Client::findOrfail($id);        
        return view('Clients.client.view',compact('client'));
    }
    function edit(int $id) {
        $client=Client::find($id);
        $this->authorize('update', $client);
        return view('Clients.client.edit',compact('client')); 
    }
    function update(Request $request,int $id)  {
        $client=Client::find($id);
        $this->authorize('update', $client);
        $data=$request->validate([
            'full_name' => 'required',
            'phone' => 'required',
        ]);
        $client->update($data);
        return redirect()->route('clients.index')
        ->with('msg-color','success')
        ->with( 'message','تم تعديل معلومات الزبون بنجاح');
    }
    function destroy(int $id){
        $client=Client::find($id);
        $this->authorize('delete', $client);
        $client->delete();
        return redirect()->route('clients.index')
        ->with('msg-color','danger')
        ->with('message','تم حذف معلومات الزبون بنجاح');

    }
}
