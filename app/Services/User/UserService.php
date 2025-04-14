<?php

namespace App\Services\User;

use App\DTO\UserDto;
use App\Models\PasswordRecovery;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordRecoveryMail;
use Illuminate\Support\Facades\Validator;

class UserService
{
    private User $user;

    public function setUser(User $user){
        if(!isset($user)){
            throw new Exception('Usuário não encontrado');
        }

        $this->user = $user;

        return $this;
    }

    public function me(): array
    {
        try {
            $user = auth()->user();
    
            if (!isset($user)) {
                throw new Exception('Usuário não encontrado');
            }
    
            $user = $user instanceof User ? $user : User::find($user->id);
    
            return [
                'status' => true,
                'data' => $user
            ];
        } catch (Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => 400
            ];
        }
    }

    public function store(UserDto $data): array
    {
        try {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'cpf_cnpj' => $data->cpf_cnpj,
                'photo' => $data->photo,
                'password' => $data->password,
            ]);
    
            return [
                'status' => true,
                'data' => $user
            ];
        } catch (Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => 400
            ];
        }
    }

    public function update(UserDto $data): array
    {
        try {
            $this->user->update([
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'cpf_cnpj' => $data->cpf_cnpj,
                'photo' => $data->photo,
                'password' => $data->password,
            ]);
    
            return [
                'status' => true,
                'data' => $this->user
            ];
        } catch (Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => 400
            ];
        }
    }        

    public function delete(): array{
        try {
            if (Auth::id() !== $this->user->id){
                throw new Exception('Você não pode apagar esse usuário');
            }

            $userName = $this->user->name;
            $this->user->delete();

            return [
                'status' => true,
                'data' => ['deletedUser' => $userName]
            ];
        } catch (Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => 400
            ];
        }
    }

    public function requestRecoverPassword($request): array
    {
        try {
            $email = $request->email;
            $user = User::where('email', $email)->first();

            if (!isset($user)) throw new Exception('Usuário não encontrado.');

            $code = bin2hex(random_bytes(10));

            $recovery = PasswordRecovery::create([
                'code' => $code,
                'user_id' => $user->id
            ]);

            if (!$recovery) {
                throw new Exception('Erro ao tentar recuperar senha');
            }

            Mail::to($email)
                ->send(new PasswordRecoveryMail($code));

            return [
                'status' => true,
                'data' => $user
            ];
        } catch (Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => 400
            ];
        }
    }

    public function updatePassword($request): array
    {
        try{
            $code = $request->code;
            $password = $request->password;

            $recovery = PasswordRecovery::orderBy('id', 'desc')
                ->where('code', $code)
                ->first();

            if(!isset($recovery)) {
                throw new Exception('Código enviado não é válido.');
            }

            $user = User::find($recovery->user_id);
            $user->password = Hash::make($password);
            $user->save();
            $recovery->delete();

            return [
                'status' => true,
                'data' => $user
            ];
        }catch(Exception $error) {
            return [
                'status' => false,
                'error' => $error->getMessage(),
                'statusCode' => 400
            ];
        }
    }    
}
