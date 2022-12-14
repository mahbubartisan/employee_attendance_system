<?php

namespace App\Http\Controllers\Employee;

use DateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendence;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmployeeAttendenceController extends Controller
{
    public function storeEmployeeAttendence(Request $request)
    {
        $in_time = new DateTime($request->in_time);
        $in_time_format = $in_time->format('h:i A');
       
        EmployeeAttendence::create([

            'employee_id' => auth()->guard('employee')->user()->id,
            'attendence_date' => now()->format('d F Y'),
            'attendence_time' => $request->attendence_time,
            'in_time' =>  $in_time_format,
            'created_at' => now()
        ]);

        $notification = array(

            'message' => 'Employee has been created',
            'alert-type' => 'success'
        );

        return redirect()->route('employee.dashboard')->with($notification);
    }


    public function editEmployeeAttendence($id)
    {
        $employee_attendence = EmployeeAttendence::findOrFail($id);

        if ($employee_attendence) {
            return response()->json([
                'status' => '200',
                'employee_attendence' => $employee_attendence
            ]);
        } else {
            return response()->json([
                'status' => '404',
                'message' => 'Employee Attendence not found'
            ]);
        }
        
    }
    
    public function updateEmployeeAttendence(Request $request, $id)
    {
        
        $in_time = new DateTime($request->in_time);
        $in_time_format = $in_time->format('h:i A');
        $out_time = new DateTime($request->out_time);
        $out_time_format = $out_time->format('h:i A');
        $interval = $in_time->diff($out_time);

        EmployeeAttendence::findOrFail($id)->update([
            
            'employee_id' => auth()->guard('employee')->user()->id,
            'attendence_date' => now()->format('d F Y'),
            'attendence_time' => $request->attendence_time,
            'in_time' =>  $in_time_format,
            'out_time' => $out_time_format,
            'total_hours' =>  $interval->format('%h hours, %i minutes'),
            'updated_at' => now()
        ]);

        $notification = array(

            'message' => 'Employee has been created',
            'alert-type' => 'success',
        );

        return redirect()->route('dashboard')->with($notification);
    }
}
