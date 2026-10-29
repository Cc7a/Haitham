<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $library = User::firstOrCreate(
            ['email' => 'library@afaq.test'],
            [
                'name' => 'مكتبة أفق',
                'password' => Str::random(64),
            ],
        );

        $quotes = [
            ['text' => 'الكلمة التي تلهمك لا تكتمل إلا بخطوة منك.', 'theme' => 'lilac'],
            ['text' => 'خطوة صغيرة اليوم أصدق من وعد كبير مؤجل.', 'theme' => 'lemon'],
            ['text' => 'حين يتحول الاختيار إلى فعل، يبدأ التغيير من مكان واضح.', 'theme' => 'sea'],
        ];

        foreach ($quotes as $quote) {
            $library->quotes()->updateOrCreate(['text' => $quote['text']], ['theme' => $quote['theme']]);
        }
    }
}