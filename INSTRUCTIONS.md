Rename the files: "timercontroller.php" and "timer.blade.php" from "time\*.php"

Rename references from "Time" to "Timer" in web.php, timercontroller.php, and main.blade.php.

Resolve the loading icon issue by using this timer.blade.php template:

```php
@extends('layouts.main')

<style type="text/css">
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.2/xlsx.full.min.js"></script>

@section('content')

<div style="margin-left: 0px">

</div>

<script>var imagesUrl = '{{ URL::asset('/image') }}';</script>
<script src="{{ asset('js/budgetWebform.js') . '?p=' . rand()  }}"></script>
@endsection
```

Run this command: `php artisan make:model UserTasks -m`

Change the migration file's 'up()' function to this:

```php
public function up()
{
    Schema::create('user_tasks', function (Blueprint $table) {
        $table->increments('id');
        $table->string('username');
        $table->string('client_name');
        $table->string('project_name');
        $table->timestamp('timer')->nullable();
        $table->timestamp('started_at')->nullable();
        $table->boolean('is_running')->default(false);
        $table->timestamps();
    });
}
```

Run the migration: `php artisan migrate --path=database/migrations/2024_12_14_145134_create_user_tasks_table.php`. Verify the table is created in phpmyadmin.
