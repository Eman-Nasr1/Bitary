<?php

namespace App\Modules\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\User\Services\UserService;
use App\Modules\City\Services\CityService;
use App\Modules\User\Requests\StoreUserRequest;
use App\Modules\User\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function __construct(
        private UserService $userService,
        private CityService $cityService
    ) {}

    public function index(Request $request)
    {
        // Set default pagination values
        $limit = max(1, (int)$request->get('limit', 10));
        $offset = max(0, (int)$request->get('offset', 0));
        
        // Set default sorting by ID ascending if not specified
        $requestData = $request->all();
        if (!isset($requestData['sortBy'])) {
            $requestData['sortBy'] = 'id';
        }
        if (!isset($requestData['sort'])) {
            $requestData['sort'] = 'DESC';
        }
        
        $users = $this->userService->listAllUsers($requestData);
        $cities = $this->cityService->listAllCities([]);

        $totalCount = $users['count'];
        $totalPages = $totalCount > 0 ? max(1, ceil($totalCount / $limit)) : 1;
        $currentPage = ($offset / $limit) + 1;

        return view('dashboard.Users.index', [
            'users' => $users['data'],
            'cities' => $cities['data'],
            'totalCount' => $totalCount,
            'limit' => $limit,
            'offset' => $offset,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['is_verified'] = $request->has('is_verified') ? true : false;
        $data['is_provider'] = $request->has('is_provider') ? true : false;
        
        $this->userService->createUser($data);
        
        return redirect()->route('dashboard.users.index')
            ->with('success', 'User created successfully!');
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->validated();
        
        // Only update password if provided
        if (empty($data['password'])) {
            unset($data['password']);
        }
        
        $data['is_verified'] = $request->has('is_verified') ? true : false;
        $data['is_provider'] = $request->has('is_provider') ? true : false;
        
        $this->userService->updateUser($id, $data);
        
        return redirect()->route('dashboard.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        
        return redirect()->route('dashboard.users.index')
            ->with('success', 'User deleted successfully!');
    }

    public function toggleProviderStatus($id)
    {
        $user = $this->userService->toggleProviderStatus($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found!');
        }
        
        $status = $user->is_provider ? 'Provider' : 'Regular User';
        
        return redirect()->back()
            ->with('success', "User status changed to {$status}!");
    }

    public function toggleUserStatus($id)
    {
        $user = $this->userService->toggleUserStatus($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found!');
        }
        
        $status = $user->is_active ? 'Active' : 'Inactive';
        
        return redirect()->back()
            ->with('success', "User status changed to {$status}!");
    }
}

