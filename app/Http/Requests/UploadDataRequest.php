<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadDataRequest extends FormRequest
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
        if ($this->method() == 'PUT' && $this->route()->getActionMethod() == 'updateTab') {
            return [
                'tab_name'   => 'required|in:tab_upload_data,tab_mutual,tab_mutual_detail,tab_mutual_followers,tab_node_graph',
                'tab_status' => 'required|in:enable,disabled,ongoing,finish',
            ];
        }
        if ($this->method() == 'PUT' && $this->route()->getActionMethod() == 'updateProgressStatus') {
            return [
                'progress_status' => 'required|in:ongoing,completed',
            ];
        }
        if ($this->method() == 'PUT' && $this->route()->getActionMethod() == 'setActiveTab') {
            return [
                'tab_name'   => 'required|in:tab_upload_data,tab_mutual,tab_mutual_detail,tab_mutual_followers,tab_node_graph',
            ];
        }
        if ($this->method() == 'POST' && $this->route()->getActionMethod() == 'importFollowersAndFollowing') {
            return [
                'file_followers' => 'required',
                'file_following' => 'required',
            ];
        }
        else {
            return [];
        }
    }

    /**
     * OVERIDE
     * =================================
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Bad Request',
            'data'    => [
                'errors' => $validator->errors(),
            ]
        ], 400));
    }

    public function messages()
    {
        return [
            'required'=> 'harus diisi',
            'unique'  => '(:input) sudah digunakan',
            'exists'  => ':attribute tidak ditemukan',
            'max'     => 'maximal :max karakter',
            'in'      => "nilai :attribute hanya boleh (:values)",
            'mimes'   => ":attribute hanya boleh bertipe: :values",
        ];
    }
}
