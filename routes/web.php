<?php

//use App\Models\Preference;
//use App\Models\User;

use App\Models\{
    Course,
    Permission,
    User,
    Preference
};

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/many-to-many-pivot', function () {
    $user = User::with('permissions')->find(1);
    // $user->permissions()->attach([
    //     1 => ['active' => false],
    //     3 => ['active' => false]
    // ]);

    echo "<b>{$user->name}</b><br>";
    foreach ($user->permissions as $permission) {
        echo "{$permission->name} - {$permission->pivot->active} <br>";
    }
});

Route::get('/many-to-many', function () {
    //dd(Permission::create(['name' => 'menu_xx']));
    $user = User::with('permissions')->find(1);

    //$permission = Permission::find(1);
    // 1 - $user->permissions()->save($permission);
    // 2 - $user->permissions()->saveMany([
    //     Permission::find(1),
    //     Permission::find(2),
    //     Permission::find(3)
    // ]);

    // 3 -   $user->permissions()->sync([2]);
    //$user->permissions()->attach([1, 3]);
    $user->permissions()->detach([1, 3]);



    $user->refresh();

    dd($user->permissions);
});

Route::get('/one-to-many', function () {
   // $course = Course::create(['name' => 'Curso de Laravelg']);

    $course = Course::with('modules.lessons')->first();

    echo $course->name;
    echo '<br>';
    foreach ($course->modules as $module) {
        echo "Módulo {$module->name} <br>";
        foreach ($module->lessons as $lesson) {
            echo "x Aula {$lesson->name} <br>";
            
        }
    }

    $data = [
        'name' => 'Módulo 2'
    ];

   

    //$course->modules()->create($data);

            //Module::find(2)->update(//$data//);

    //$course->modules()->get();
    $modules = $course->modules;

   // dd($modules);
});

Route::get('/one-to-one', function () {
    $user = User::with('preference')->find(2); //with()


    $data = [
        'background_color' => '#080',
    ];

    if ($user->preference) {
        $user->preference->update($data);
    } else {
        //$user->preference()->create($data);
        $preference = new Preference($data);
        $user->preference()->save($preference);
    }
    $user->refresh();

    var_dump($user->preference);

    $user->preference->delete();
    $user->refresh();

    dd($user->preference);
});

Route::get('/', function () {
    return view('welcome');
});
