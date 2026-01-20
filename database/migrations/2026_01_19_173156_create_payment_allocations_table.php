public function up(): void
{
    Schema::create('payment_allocations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
        $table->foreignId('installment_id')->nullable()->constrained()->nullOnDelete();

        $table->decimal('amount', 12, 2)->default(0);

        $table->foreignId('created_by')->nullable()->references('id')->on('users')->nullOnDelete();
        $table->timestamps();

        $table->index(['payment_id']);
        $table->index(['installment_id']);
    });
}
