<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Jobs\SendWelcomeEmail;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {


        $search = $request->get('search');

        $currentAdmin = Auth::user();

        $users = User::query()
            ->with(['responses' => function ($query) {
                $query->latest('created_at');
            }])
            ->where('admin_id', $currentAdmin->id)
            // TODO: Add search on the client side
            // ->where('name', 'LIKE', "%{$search}%")
            // ->orWhere('email', 'LIKE', "%{$search}%")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validatedUser = $request->validated();
        // dd($user);

        $adminUser = Auth::user();


        $user = User::create([
            'name' => $validatedUser['name'],
            'email' => $validatedUser['email'],
            'password' => bcrypt($validatedUser['password']),
            'mobile_number' => $validatedUser['mobile_number'],
            'role' => 'user',
            'admin_id' => $adminUser->id,
        ]);

        $password  = $validatedUser['password'];

        // Mail::to($user->email)->send(new WelcomeMail($user, $password));

        // Dispatch the job to send the email

        SendWelcomeEmail::dispatch($user, $password);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        // dd($user);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View

    {
        // dd($user);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        // dd($user);

        $user->fill($validated);
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', __('User updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // dd($user->role);
        if ($user->role === 'user') {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
        }
        return redirect()->route('admin.users.index')->with('error', 'You cant delete admin users');
    }
}
