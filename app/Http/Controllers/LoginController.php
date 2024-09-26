<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Session;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    function index()
    {
        return view("login");
    }

    function registration()
    {
        $UserRoles = UserRole::orderBy("id", "desc")->get();
        return view("registration", compact("UserRoles"));
    }

    function validate_registration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:users",
            "email" => "required|email|unique:users",
            "password" => "required|min:6",
            "confirmPassword" => "required|same:password|min:6",
        ]);

        if ($validator->fails()) {
            session()->flash("password", $request->password);
            session()->flash("confirmPassword", $request->confirmPassword);

            // Redirect back with errors and input except passwords
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        $user = User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "password" => Hash::make($data["password"]),
            "user_role" => $data["userRole"],
        ]);
        $user->sendEmailVerificationNotification();

        return redirect("login")->with(
            "success",
            "Registration Completed, now you can login"
        );
    }

    function dashboard()
    {
        if (Auth::check()) {
            return view("dashboard");
        }

        return redirect("login")->with(
            "success",
            "you are not allowed to access"
        );
    }

    function logout()
    {
        Session::flush();
        Auth::logout();

        Session::regenerate();

        return Redirect("login");
    }

    function profile()
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $detail = [
                "userId" => Auth::user()->id,
                "name" => Auth::user()->name,
                "email" => Auth::user()->email,
            ];
            return view("profile", ["detail" => $detail]);
        }

        return redirect("login")->with(
            "success",
            "you are not allowed to access"
        );
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            "name" =>[ "required","string","max:225", Rule::unique("users")->ignore($user->id)],
            "email" => [
                "required",
                "email",
                Rule::unique("users")->ignore($user->id),
            ],
        ]);

        $user->name = $request->input("name");
        $user->email = $request->input("email");

        $user->save();

        return response()->json(["success" => "Profile updated successfully."]);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            "password" => "required|min:6",
            "confirmPassword" => "required|same:password|min:6",
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(
                    ["errors" => $validator->errors()],
                    422
                );
            }

            session()->flash("password", $request->password);
            session()->flash("confirmPassword", $request->confirmPassword);

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->password = Hash::make($request->input("password"));

        $user->save();

        return response()->json(["success" => "Profile updated successfully."]);
    }

    public function userList()
    {
        $users = DB::table("users")
            ->join("user_roles", "user_roles.id", "=", "users.user_role")
            ->select("users.*", "user_roles.user_type")
            ->paginate(10);

        $roles = UserRole::all();
        return view("userList", compact("users", "roles"));
    }

    public function validate_login(Request $request)
    {
        $request->validate([
            'email_or_username' => 'required', // Accept email or username
            'password' => 'required',
        ]);
    
        $throttleKey = Str::lower($request->input('email_or_username')) . '|' . $request->ip();
    
        if (RateLimiter::tooManyAttempts($throttleKey, 2)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return redirect()->back()->withErrors([
                'email_or_username' => "Too many login attempts. Please try again in {$seconds} seconds."
            ]);
        }
    
        $loginField = filter_var($request->input('email_or_username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $credentials = [
            $loginField => $request->input('email_or_username'),
            'password' => $request->input('password'),
        ];
    
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);
    
            // Redirect based on user role
            $user = Auth::user();
            if ($user->hasRole('Administrator')) {
                return redirect()->route('administrator.dashboard');
            } elseif ($user->hasRole('Teacher')) {
                return redirect()->route('teacher.dashboard');
            } elseif ($user->hasRole('Student')) {
                return redirect()->route('student.dashboard');
            } elseif ($user->hasRole('Parent')) {
                return redirect()->route('parent.dashboard');
            }
        }

        RateLimiter::hit($throttleKey);

        return redirect('login')->withErrors([
            'email_or_username' => 'Login details are not valid.',
        ]);
    }

    public function administratorDashboard()
    {
        $user = Auth::user();
        return view("administrator", compact("user"));
    }

    public function teacherDashboard()
    {
        $user = Auth::user();
        return view("teacher", compact("user"));
    }

    public function studentDashboard()
    {
        $user = Auth::user();
        return view("student", compact("user"));
    }

    public function parentDashboard()
    {
        $user = Auth::user();
        return view("parent", compact("user"));
    }


    public function editUser($id)
    {
        $user = User::findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json($user);
        }

        return view('users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required','string','max:255',  Rule::unique('users')->ignore($user->id)],
            'email' => ['required','email', Rule::unique('users')->ignore($user->id)],
            'user_role' => 'required|exists:user_roles,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'user_role' => $request->user_role,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
        ]);
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'user_role' => 'required|exists:user_roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_role' => $request->user_role,
            'email_verified_at' => Carbon::now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => 'User created successfully']);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while trying to delete the user.',
            ], 500);
        }
    }
}