<?php

namespace App\Http\Requests\Admin\Base\Line;

use App\Models\LineAccount;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
        $route_name = Route::currentRouteName();
        $rules = [];

        if ($this->isMethod("post")) {
            if ($route_name === "admin.api.line.account.create" || $route_name === "admin.api.line.account.check") {
                $rules = [
                    "channel_name" => [
                        "required",
                        "string",
                        "between:1,512",
                    ],
                    "channel_id" => [
                        "required",
                        "string",
                        function ($attribute, $value, $fail) {
                            $line_account = LineAccount::where([
                                "channel_id" => $value,
                                "channel_secret" => $this->input("channel_secret")
                            ])
                            ->get()
                            ->first();
                            if ($line_account !== null) {
                                $fail("The account which you posted has already registered on the application.");
                            }
                        }
                    ],
                    "channel_secret" => [
                        "required",
                        "string",
                        function ($attribute, $value, $fail) {
                            $line_account = LineAccount::where([
                                "channel_id" => $this->input("channel_id"),
                                "channel_secret" => $value,
                            ])
                            ->get()
                            ->first();
                            if ($line_account !== null) {
                                $fail("The account which you posted has already registered on the application.");
                            }
                        }
                    ],
                    "user_id" => [
                        "required",
                        "string",
                    ],
                    "messaging_channel_id" => [
                        "required",
                        "string",
                        function ($attribute, $value, $fail) {
                            $line_account = LineAccount::where([
                                "messaging_channel_id" => $value,
                                "messaging_channel_secret" => $this->input("messaging_channel_secret"),
                            ])
                            ->get()
                            ->first();
                            if ($line_account !== null) {
                                $fail("The account which you posted has already registered on the application.");
                            }
                        }
                    ],
                    "messaging_channel_secret" => [
                        "required",
                        "string",
                        function ($attribute, $value, $fail) {
                            $line_account = LineAccount::where([
                                "messaging_channel_id" => $this->input("messaging_channel_id"),
                                "messaging_channel_secret" => $value,
                            ])
                            ->get()
                            ->first();
                            if ($line_account !== null) {
                                $fail("The account which you posted has already registered on the application.");
                            }
                        }
                    ],
                    "messaging_user_id" => [
                        "required",
                        "string",
                    ],
                    "messaging_channel_access_token" => [
                        "required",
                        "string",
                    ],
                ];
            } elseif ($route_name === "admin.api.line.account.update") {
                $rules = [
                    "line_account_id" => [
                        "required",
                        "integer",
                        Rule::exists("line_accounts", "id"),
                    ],
                    "api_token" => [
                        "required",
                        "string",
                        function ($attribute, $value, $fail) {
                            $line_account_id = $this->input("line_account_id");
                            $line_account = LineAccount::where(["api_token" => $value])->find($line_account_id);

                            if ($line_account === null) {
                                $fail("Could not find the account which you specified.");
                                return false;
                            }
                            return true;
                        }
                    ],
                    "channel_id" => [
                        "required",
                        "string",
                        function ($attribute, $value, $fail) {
                            $line_account = LineAccount::where([
                                "channel_id" => $value,
                                "channel_secret" => $this->input("channel_secret")
                            ])
                            ->get()
                            ->first();
                            if ($line_account !== null) {
                                $fail("The account which you posted has already registered on the application.");
                            }
                        }
                    ],
                    "channel_secret" => [
                        "required",
                        "string",
                        function ($attribute, $value, $fail) {
                            $line_account = LineAccount::where([
                                "channel_id" => $this->input("channel_id"),
                                "channel_secret" => $value,
                            ])
                            ->get()
                            ->first();
                            if ($line_account !== null) {
                                $fail("The account which you posted has already registered on the application.");
                            }
                        }
                    ],
                    "user_id" => [
                        "required",
                        "string",
                    ],
                    "messaging_channel_id" => [
                        "required",
                        "string",
                        function ($attribute, $value, $fail) {
                            $line_account = LineAccount::where([
                                "messaging_channel_id" => $value,
                                "messaging_channel_secret" => $this->input("messaging_channel_secret"),
                            ])
                            ->get()
                            ->first();
                            if ($line_account !== null) {
                                $fail("The account which you posted has already registered on the application.");
                            }
                        }
                    ],
                    "messaging_channel_secret" => [
                        "required",
                        "string",
                        function ($attribute, $value, $fail) {
                            $line_account = LineAccount::where([
                                "messaging_channel_id" => $this->input("messaging_channel_id"),
                                "messaging_channel_secret" => $value,
                            ])
                            ->get()
                            ->first();
                            if ($line_account !== null) {
                                $fail("The account which you posted has already registered on the application.");
                            }
                        }
                    ],
                    "messaging_user_id" => [
                        "required",
                        "string",
                    ],
                    "message_channel_access_token" => [
                        "required",
                        "string",
                    ],
                ];
            }
        } elseif ($this->isMethod("get")) {
            if ($route_name === "admin.api.line.account.detail") {
                $rules = [
                    "line_account_id" => [
                        "required",
                        "integer",
                        Rule::exists("line_accounts", "id"),
                    ],
                    "api_token" => [
                        "required",
                        "string",
                        function ($attribute, $value, $fail) {
                            $line_account_id = $this->route()->parameter("line_account_id");
                            $line_account = LineAccount::where(["api_token" => $value])->find($line_account_id);

                            if ($line_account === null) {
                                $fail("Could not find the account which you specified.");
                                return false;
                            }
                            return true;
                        }
                    ],
                ];
            } elseif ($route_name === "admin.line.account.index") {
            } elseif ($route_name === "admin.line.account.detail") {
                $rules = [
                    "line_account_id" => [
                        "required",
                        "integer",
                        Rule::exists("line_accounts", "id"),
                    ],
                ];
            }
        }

        return $rules;
    }

    /**
     * validate対象のデータ
     *
     * @return array
     */
    public function validationData():array
    {
        $validation_data = array_merge(
            $this->all(),
            $this->input(),
            $this->route()->parameters(),
        );
        return $validation_data;
    }
}
