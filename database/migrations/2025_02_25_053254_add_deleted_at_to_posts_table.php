<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->softDeletes(); // Tambahkan kolom deleted_at
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Hapus jika rollback
        });
    }
};

