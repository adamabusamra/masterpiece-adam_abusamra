<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::all();
        return view('dashboard.admin.teachers.teacher_index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        require_once(__DIR__ . "/../../../public/assets/countries.php");
        $fields = Field::all();
        // $specialities = Speciality::all();
        return view('dashboard.admin.teachers.teacher_create', compact('fields', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'            => 'required|max:70',
            'last_name'             => 'required|max:70',
            'email'                 => 'required|email',
            'password'              => 'required|confirmed|min:8',
            'address1'              => 'required|max:70',
            'address2'              => 'sometimes|nullable|max:255',
            'country'               => 'required|max:100',
            'city'                  => 'required|max:100',
            'title'                 => 'required|max:255',
            'speciality'            => 'required|integer',
            'image'                 => 'sometimes|nullable|mimes:jpeg,jpg,png|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->route('teachers.create')
                ->with('warning', 'Some Errors in the form fields')
                ->withErrors($validator)
                ->withInput();
        }

        // Storing image in storage and setting the image's name in database
        if ($file = $request->file('image')) {
            $file_name =  rand() . $file->getClientOriginalName();
            $file->move('teacher/images', $file_name);
        } else {
            $file_name = "default-avatar.png";
        }

        #The new way of storing requests even if not all the inputs are the same as the database
        // Modifiying our request to add our file name as image and speciality_id instaed of speciality
        $request->request->add(['image' => $file_name, 'speciality_id' => $request->speciality, 'password' => Hash::make($request->password)]);
        // Submitting the request but without the inputs that don't have a column in the database
        Teacher::create($request->except(['password_confirmation', 'field', 'speciality']));

        #The old way
        // Teacher::create([
        //     'first_name'            => $request->first_name,
        //     'last_name'             => $request->last_name,
        //     'email'                 => $request->email,
        //     'password'              => $request->password,
        //     'address1'              => $request->address1,
        //     'address2'              => $request->address2,
        //     'country'               => $request->country,
        //     'city'                  => $request->city,
        //     'title'                 => $request->title,
        //     'speciality_id'         => $request->speciality,
        //     'image'                 => $file_name,
        // ]);

        return redirect()->route('teachers.index')
            ->with('toast_success', 'Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        require_once(__DIR__ . "/../../../public/assets/countries.php");
        $fields = Field::all();
        return view('dashboard.admin.teachers.teacher_edit', compact('teacher', 'fields', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validator = Validator::make($request->all(), [
            'first_name'            => 'required|max:70',
            'last_name'             => 'required|max:70',
            'email'                 => 'required|email',
            'password'              => 'sometimes|nullable|confirmed|min:8',
            'address1'              => 'required|max:70',
            'address2'              => 'sometimes|nullable|max:255',
            'country'               => 'required|max:100',
            'city'                  => 'required|max:100',
            'title'                 => 'required|max:255',
            'speciality'            => 'required|integer',
            'image'                 => 'sometimes|nullable|mimes:jpeg,jpg,png|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->route('teachers.edit', $teacher->id)
                ->with('warning', 'Some Errors in the form fields')
                ->withErrors($validator)
                ->withInput();
        }

        // Storing image in storage and setting the image's name in database
        if ($file = $request->file('image')) {
            $file_name =  rand() . $file->getClientOriginalName();
            $file->move('teacher/images', $file_name);
        } else {
            $file_name = "default-avatar.png";
        }

        //Getting old password or replacing with new one if exists
        if ($request->password) {
            $password = Hash::make($request->password);
        } else {
            $password = $teacher->password;
        }

        $teacher->update([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => $password,
            'address1'      => $request->address1,
            'address2'      => $request->address2,
            'country'       => $request->country,
            'city'          => $request->city,
            'title'         => $request->title,
            'speciality_id' => $request->speciality,
            'image'         => $file_name,
        ]);

        return redirect()->route('teachers.index')->with('toast_success', 'Teacher updated successfully.');

        #Tried mass update but apparently it dosent work like create([])
        // $request->request->add(['image' => $file_name, 'speciality_id' => $request->speciality, 'password' => $password]);
        // return $request->except(['password_confirmation', 'field', 'speciality']);
        // $teacher->fill($request->except(['password_confirmation', 'field', 'speciality']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect(route('teachers.index'))->with('success', 'Teacher deleted successfully.');
    }
}
