<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    // create an index function to show all the companies
    public function index()
    {
        $title = 'Company';
        $subtitle = 'Company Settings';
        // get all the companies from the database
        $companies = DB::table('companies')->first();

        return view('companies.index', compact('title', 'subtitle', 'companies'));
    }

    // create a function to store the company
    public function store(Request $request)
    {
        // ddd($request);
        // validate the request
        $validatedData = $request->validate([
            'company_name' => 'required',
            'company_address' => 'required',
            'company_phone' => 'required',
            'company_logo1' => 'image|file|max:1024',
            'company_logo2' => 'image|file|max:1024'
        ]);

        if($request->file('company_logo1') || $request->file('company_logo2')) {
            $validatedData['company_logo1'] = $request->file('company_logo1')->store('logos');
            $validatedData['company_logo2'] = $request->file('company_logo2')->store('logos');
        }
        // store the company
        DB::table('companies')->insert($validatedData);

        return redirect('companies')->with('status', 'Company has been added successfully');
    }

    //create a function to update the company
    public function update(Request $request, $id)
    {
        // ddd($request);
        // validate the request
        $rules =[
            'company_name' => 'required',
            'company_address' => 'required',
            'company_phone' => 'required',
            'company_logo1' => 'image|file|max:1024',
            'company_logo2' => 'image|file|max:1024'
        ];

        $validatedData = $request->validate($rules);

        // delete image before update
        if($request->file('company_logo1')) {
            if($request->oldLogo1){
                Storage::delete($request->oldLogo1);
            }
            $validatedData['company_logo1'] = $request->file('company_logo1')->store('logos');
        }
        if($request->file('company_logo2')) {
            if($request->oldLogo2){
                Storage::delete($request->oldLogo2);
            }
            $validatedData['company_logo2'] = $request->file('company_logo2')->store('logos');
        }

        // update the company
        DB::table('companies')->where('id', $id)->update($validatedData);

        return redirect('companies')->with('status', 'Company has been updated successfully');
    }

    // create a function to delete the company
    public function delete($id)
    {
        $companies = DB::table('companies')->first();   
        if($companies->company_logo1){
            Storage::delete($companies->company_logo1);
        }
        if($companies->company_logo2){
            Storage::delete($companies->company_logo2);
        }
        // delete the company
        DB::table('companies')->where('id', $id)->delete();

        return redirect('companies')->with('status', 'Company has been deleted successfully');
    }
}
