<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->json('images')->nullable()->after('image_path');
        });

        $cars = DB::table('cars')
            ->select('id', 'image_path')
            ->whereNotNull('image_path')
            ->get();

        foreach ($cars as $car) {
            DB::table('cars')
                ->where('id', $car->id)
                ->update([
                    'images' => json_encode([$car->image_path]),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
