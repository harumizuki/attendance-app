<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\Rule;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * 会員登録（一般ユーザー）
     */
    public function create(array $input)
    {
        // バリデーション
        Validator::make(
            $input,
            [
                'name'          => ['required', 'string', 'max:255'],
                'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password'      => ['required', 'string', 'min:8', 'confirmed'],
                // 任意入力（存在チェックのみ）
                'department_id' => ['nullable', 'integer', 'exists:departments,id'],
                'position_id'   => ['nullable', 'integer', 'exists:positions,id'],
                // 基本は一般ユーザー固定（1）。もしフォームから来たら 1 のみ許可
                'role'          => ['nullable', Rule::in([1])],
            ],
            // ★評価対象の日本語メッセージ（指定文言）
            [
                'name.required'     => 'お名前を入力してください',
                'email.required'    => 'メールアドレスを入力してください',
                'password.required' => 'パスワードを入力してください',
                'password.min'      => 'パスワードは8文字以上で入力してください',
                'password.confirmed'=> 'パスワードと一致しません',
            ]
        )->validate();

        return User::create([
            'name'          => $input['name'],
            'email'         => $input['email'],
            'password'      => Hash::make($input['password']),
            'department_id' => $input['department_id'] ?? null,
            'position_id'   => $input['position_id'] ?? null,
            // フォームに role が来ても 1（一般）に寄せる。必要なら $input['role'] ?? 1 に変更可
            'role'          => 1,
        ]);
    }
}
