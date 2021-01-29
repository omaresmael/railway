<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'depart_time'=>'required|date|after:arrival_date', 'arrival_time'=>'required|date','base_id'=>'required|numeric','destination_id'=>'required|numeric','train_id'=>'required|numeric'
        ];
    }
}
