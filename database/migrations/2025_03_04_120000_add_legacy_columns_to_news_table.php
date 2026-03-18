<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('news')) {
            return;
        }

        Schema::table('news', function (Blueprint $table) {
            if (! Schema::hasColumn('news', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }
            if (! Schema::hasColumn('news', 'category')) {
                $table->string('category')->nullable()->after('content');
            }
            if (! Schema::hasColumn('news', 'author')) {
                $table->string('author')->nullable()->after('category');
            }
            if (! Schema::hasColumn('news', 'imagelocation')) {
                $table->string('imagelocation')->nullable()->after('author');
            }
            if (! Schema::hasColumn('news', 'created_at')) {
                $table->dateTime('created_at')->nullable();
            }
            if (! Schema::hasColumn('news', 'updated_at')) {
                $table->dateTime('updated_at')->nullable();
            }
        });

        // Backfill created_at from date_added for existing rows
        if (Schema::hasColumn('news', 'date_added') && Schema::hasColumn('news', 'created_at')) {
            \DB::table('news')->whereNull('created_at')->whereNotNull('date_added')->update([
                'created_at' => \DB::raw('date_added'),
            ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('news')) {
            return;
        }

        Schema::table('news', function (Blueprint $table) {
            $columns = ['slug', 'category', 'author', 'imagelocation', 'created_at', 'updated_at'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('news', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
