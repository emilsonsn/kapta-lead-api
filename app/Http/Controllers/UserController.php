<?php

namespace App\Http\Controllers;

use App\DTO\UserDto;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function __construct(private UserService $userService)
    {
    }

    public function getUser()
    {
        $result = $this->userService
            ->me();

        if ($result['status']) {
            $result['message'] = "Usuário recuperado com sucesso";
        }

        return $this->response($result);
    }

    public function create(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
    
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $data['photo'] = $path;
        }
    
        $dto = UserDto::fromArray($data);
    
        $result = $this->userService->store(data: $dto);
    
        if ($result['status']) {
            $result['message'] = "Usuário criado com sucesso";
        }
    
        return $this->response($result);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();
    
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $data['photo'] = $path;
        } else {
            $data['photo'] = $user->photo;
        }
    
        $dto = UserDto::fromArray($data);
    
        $result = $this->userService
            ->setUser(user: $user)
            ->update(data: $dto);
    
        if ($result['status']) {
            $result['message'] = "Usuário atualizado com sucesso";
        }
    
        return $this->response($result);
    }    

    public function delete(Request $request, User $user): JsonResponse
    {
            $result = $this->userService
                ->setUser(user: $user)
                ->delete();

        if ($result['status']) {
            $result['message'] = "Usuário deletado com sucesso";
        }

        return $this->response($result);
    }

    public function passwordRecovery(Request $request): JsonResponse
    {
        $result = $this->userService
            ->requestRecoverPassword(request: $request);

        if ($result['status']) {
            $result['message'] = "Email de recuperação enviado com sucesso";
        }

        return $this->response($result);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $result = $this->userService
            ->updatePassword(request: $request);

        if ($result['status']) {
            $result['message'] = "Senha atualizada com sucesso";
        }

        return $this->response($result);
    }
}
