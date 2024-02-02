<?php

use App\Enums\TransferStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('track_number', 26)->nullable()->index();
            $table->foreignUlid('source')->constrained('cards');
            $table->foreignUlid('destination')->constrained('cards');
            $table->decimal('amount', 30, 2);
            $table->string('status')->default(TransferStatusEnum::Init->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
