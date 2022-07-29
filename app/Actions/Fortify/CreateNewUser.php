<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert as FacadesAlert;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Log::channel('userdata')->info('validation Started');
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'dtDOB' => ['required', 'date'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();
        Log::channel('userdata')->info('validation Successfully Completed');
        Log::channel('userdata')->info('validation Successfully Created');
            FacadesAlert::success('Email Sent!','Check your mail and clock for verification');
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'dtDOB' => $input['dtDOB'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
