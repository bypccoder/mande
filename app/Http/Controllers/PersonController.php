<?php

namespace App\Http\Controllers;

use App\Models\No_Employee;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class PersonController
 * @package App\Http\Controllers
 */
class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $people = Person::paginate();

        return view('person.index', compact('people'))
            ->with('i', (request()->input('page', 1) - 1) * $people->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $person = new Person();
        return view('person.create', compact('person'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //request()->validate(Person::$rules);

        $response = ['code' => 400];
        if(!empty($request->person_id)){
            $person = Person::find($request->person_id);
            $person->email = $request->email;
            $person->save();
            //$response['code'] = 200;
            //return response()->json($response);
            //exit();
        }else{
            $person = Person::create($request->all());
        }

        if ($person->id) {
            $data = [
                'person_id' => $person->id
            ];
            $no_employee = No_Employee::create($data);

            $queryRaw = DB::table('people')
                ->leftJoin('companies', 'companies.person_id', '=', 'people.id')
                ->leftJoin('employees', 'employees.person_id', '=', 'people.id')
                ->leftJoin('no__employees', 'no__employees.person_id', '=', 'people.id')
                ->where('people.document', $person->document)
                ->select(
                    'companies.id as company_id',
                    'employees.id as employee_id',
                    'companies.company as company_name',
                    'no__employees.id as no_employee_id',
                    'people.name',
                    'people.document',
                    'people.id',
                    'people.document_type_id',
                    'people.document',
                    DB::raw('CONCAT(people.lastname_1, " " ,people.lastname_2) as last_name'),
                    'employees.contract_start_date',
                    'employees.company',
                    'employees.type_employee'
                );

            if ($queryRaw->count() > 0) {
                $query = $queryRaw->first();

                if ($query->company_id) {
                    $response['code'] = 200;
                    session()->put('person', $query);
                } elseif ($query->employee_id) {
                    $response['code'] = 200;
                    session()->put('person', $query);
                } elseif ($query->no_employee_id) {
                    $response['code'] = 200;
                    session()->put('person', $query);
                }
            } else {
                $response['code'] = 500;
                $response['status'] = 'info';
                $response['message'] = 'Completa la siguiente informacion, por unica vez.';
            }

            //$response['code'] = 200;

        }

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $person = Person::find($id);

        return view('person.show', compact('person'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$person = new Person();
        $person = Person::find($id);

        return view('person.edit', compact('person'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Person $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Person $person)
    {
        request()->validate(Person::$rules);

        $person->update($request->all());

        return redirect()->route('people.index')
            ->with('success', 'Person updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $person = Person::find($id)->delete();

        return redirect()->route('people.index')
            ->with('success', 'Person deleted successfully');
    }
}
