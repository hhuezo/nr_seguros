<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('poliza_seguro', function (Blueprint $table) {
            if (!Schema::hasColumn('poliza_seguro', 'ValorDeducible')) {
                $table->decimal('ValorDeducible', 12, 2)->nullable()->after('Deducible');
            }
        });
    }

    public function down()
    {
        Schema::table('poliza_seguro', function (Blueprint $table) {
            if (Schema::hasColumn('poliza_seguro', 'ValorDeducible')) {
                $table->dropColumn('ValorDeducible');
            }
        });
    }
};
