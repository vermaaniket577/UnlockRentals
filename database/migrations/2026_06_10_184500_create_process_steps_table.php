<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('process_steps', function (Blueprint $table) {
            $table->id();
            $table->string('step_number')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('icon_svg')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pre-seed with original three steps
        DB::table('process_steps')->insert([
            [
                'step_number' => '01',
                'title' => 'Discover',
                'description' => "Explore India's most exclusive rental registry with intelligent lifestyle filters.",
                'icon_svg' => '<svg width="56" height="56" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.5 3.75A6.75 6.75 0 1010.5 17.25 6.75 6.75 0 0010.5 3.75zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" /></svg>',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'step_number' => '02',
                'title' => 'Concierge',
                'description' => 'Connect with premium owners or leverage our elite viewing concierge support.',
                'icon_svg' => '<svg width="56" height="56" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 003.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-9 0-5.03-4.428-9-9.75-9s-9.75 3.97-9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 01-.814 1.686.75.75 0 00.44 1.223zM8.25 10.875a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25zM10.875 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875-1.125a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25z" /></svg>',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'step_number' => '03',
                'title' => 'Finalize',
                'description' => 'Complete secure digital legalities and move into your handpicked luxury space.',
                'icon_svg' => '<svg width="56" height="56" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25c0 1.942.868 3.659 2.146 4.815a1 1 0 01.354.757v4.928a2 2 0 002 2h3a2 2 0 002-2v-1h2a2 2 0 002-2v-1h2a2 2 0 002-2v-2.5a.75.75 0 00-.75-.75h-7.766a1 1 0 01-.84-.457 5.25 5.25 0 00-6.142-2.043zM10.5 5.25a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /></svg>',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_steps');
    }
};
