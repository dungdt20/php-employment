<?php

namespace App\Http\Controllers;

use App\Models\Employment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class EmploymentController extends Controller
{
    public function getAll()
    {
        $data = User::with(['employments' => function ($query) {
            $query->orderBy('start_date', 'desc');
        }])->get();

        // Success
        return response()->json([
            'data' => $data
        ], Response::HTTP_ACCEPTED);
    }

    public function createEmployment(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'company_name' => 'required|string',
            'job_title' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        // Validator error
        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => $validator->errors(),
                ]
            ], Response::HTTP_BAD_REQUEST);
        }

        $reqData = $request->all();

        $user = User::find($reqData['user_id']);

        // User not found error
        if (is_null($user)) {
            return response()->json([
                'error' => [
                    'code' => Response::HTTP_NOT_FOUND,
                    'message' => "User id #{$reqData['user_id']} not found.",
                ]
            ], Response::HTTP_NOT_FOUND);
        }

        // User not available for new job error
        if (!$this->isUserAvailable($reqData, $user)) {
            return response()->json([
                'error' => [
                    'code' => Response::HTTP_FORBIDDEN,
                    'message' => "User id #{$reqData['user_id']} not available at that given time.",
                ]
            ], Response::HTTP_FORBIDDEN);
        }

        // Create new employment
        $employment = new Employment([
            'company_name' => $reqData['company_name'],
            'job_title' => $reqData['job_title'],
            'end_date' => isset($reqData['end_date']) ? $reqData['end_date'] : null,
        ]);
        if (isset($reqData['start_date'])) {
            $employment->start_date = $reqData['start_date'];
        }
        $employment = $user->employments()->save($employment);

        $data = [
            "id" => $employment->employment_id,
        ];

        // Success
        return response()->json([
            'data' => $data
        ], Response::HTTP_ACCEPTED);
    }

    private function isUserAvailable($reqData, $user) {
        $userEmployments = $user->employments()->orderBy('start_date', 'desc')->get();

        $CURRENT_DATE = date('Y-m-d');

        $startDate = !empty($reqData['start_date'])
        ? date('Y-m-d', strtotime($reqData['start_date']))
        : $CURRENT_DATE;
        $endDate = !empty($reqData['end_date'])
        ? date('Y-m-d', strtotime($reqData['end_date']))
        : null;

        if (is_null($endDate)) {
            $lastEmployment = !empty($userEmployments) ? $userEmployments[0] : null;
            if (!is_null($lastEmployment)
            && (is_null($lastEmployment['end_date']) || $lastEmployment['end_date'] >= $startDate)) {
                return false;
            }
        } else {
            if ($startDate > $endDate) {
                return false;
            }

            foreach ($userEmployments as $employmentValue) {
                if ($employmentValue['start_date'] <= $startDate
                && (is_null($employmentValue['end_date']) || $employmentValue['end_date'] >= $startDate)) {
                    return false;
                }

                if ($employmentValue['start_date'] <= $endDate
                && (!is_null($employmentValue['end_date']) && $employmentValue['end_date'] >= $endDate)) {
                    return false;
                }
            }
            unset($employmentValue);
        }

        return true;
    }
}
